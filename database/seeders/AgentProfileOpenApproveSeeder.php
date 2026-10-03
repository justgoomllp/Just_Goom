<?php

namespace Database\Seeders;

use App\Models\AgentCommissionRate;
use App\Models\AgentProfileTask;
use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\Plan;
use App\Models\User;
use App\Services\Admin\UserService;
use App\Services\Front\AgentProfileTaskService;
use App\Support\PricingCatalog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Demo agents + customers for profile No (Open) and Approve (lock).
 *
 * php artisan db:seed --class=AgentProfileOpenApproveSeeder
 *
 * @author KP PATEL
 */
class AgentProfileOpenApproveSeeder extends Seeder
{
    public const PASSWORD = 'password123';

    public function run(): void
    {
        $category = Category::query()->where('status', 1)->orderBy('id')->first();
        $subCategoryId = $category?->subCategories()->where('status', 1)->orderBy('id')->value('id');
        $userService = app(UserService::class);
        $tasks = app(AgentProfileTaskService::class);

        $ramesh = $this->agent([
            'email' => 'ramesh.agent@justgoom.test',
            'fname' => 'Ramesh',
            'lname' => 'Patel',
            'phone' => '9100000001',
            'referral_code' => 'DEMI0001',
            'city' => 'Ahmedabad',
        ], $category, $subCategoryId, $userService);

        $sneha = $this->agent([
            'email' => 'sneha.agent@justgoom.test',
            'fname' => 'Sneha',
            'lname' => 'Shah',
            'phone' => '9100000002',
            'referral_code' => 'DEMI0002',
            'city' => 'Surat',
        ], $category, $subCategoryId, $userService);

        $amit = $this->agent([
            'email' => 'amit.agent@justgoom.test',
            'fname' => 'Amit',
            'lname' => 'Mehta',
            'phone' => '9100000003',
            'referral_code' => 'DEMI0003',
            'city' => 'Vadodara',
        ], $category, $subCategoryId, $userService);

        foreach ([$ramesh, $sneha, $amit] as $agent) {
            $this->ensureRates($agent);
        }

        $noCustomer = $this->customer($ramesh, [
            'email' => 'mehta.traders@justgoom.test',
            'fname' => 'Kiran',
            'lname' => 'Mehta',
            'company' => 'Mehta Traders',
            'phone' => '9200000001',
            'city' => 'Ahmedabad',
        ], $category, $subCategoryId);

        $approveCustomer = $this->customer($ramesh, [
            'email' => 'desai.exports@justgoom.test',
            'fname' => 'Nisha',
            'lname' => 'Desai',
            'company' => 'Desai Exports',
            'phone' => '9200000002',
            'city' => 'Rajkot',
        ], $category, $subCategoryId);

        $noTask = $tasks->ensureForCustomer($noCustomer);
        $noTask->update([
            'status' => AgentProfileTask::STATUS_EXCLUSIVE,
            'source_agent_id' => $ramesh->id,
            'assigned_agent_id' => $ramesh->id,
            'exclusive_until' => now()->addHours(40),
            'declined_at' => null,
            'opened_at' => null,
            'locked_at' => null,
            'completed_at' => null,
        ]);

        $approveTask = $tasks->ensureForCustomer($approveCustomer);
        $approveTask->update([
            'status' => AgentProfileTask::STATUS_OPEN,
            'source_agent_id' => $ramesh->id,
            'assigned_agent_id' => null,
            'exclusive_until' => now()->subHours(2),
            'declined_at' => now()->subMinutes(10),
            'opened_at' => now()->subMinutes(10),
            'locked_at' => null,
            'completed_at' => null,
        ]);

        $this->command->newLine();
        $this->command->info('Demo agents (password: '.self::PASSWORD.')');
        $this->command->table(
            ['Agent', 'Email / Agent ID', 'Role in demo'],
            [
                ['Ramesh Patel', $ramesh->email.' / '.$ramesh->referral_code, 'Original agent (brings registrations)'],
                ['Sneha Shah', $sneha->email.' / '.$sneha->referral_code, 'Other agent — Approve lock'],
                ['Amit Mehta', $amit->email.' / '.$amit->referral_code, 'Other agent — sees listing vanish'],
            ]
        );

        $this->command->info('Point 2 — No (release as Open)');
        $this->command->line('  1. Login as Ramesh: '.$ramesh->email.' / '.self::PASSWORD);
        $this->command->line('  2. Open Customers. Mehta Traders is Exclusive (~40 hours left).');
        $this->command->line('  3. Tap No → Release. It becomes Open for every agent.');
        $this->command->line('  Customer login: '.$noCustomer->email.' / '.self::PASSWORD);

        $this->command->newLine();
        $this->command->info('Point 3 — Approve (lock to one agent)');
        $this->command->line('  1. Login as Sneha: '.$sneha->email.' / '.self::PASSWORD);
        $this->command->line('  2. Open profiles shows Desai Exports (Ramesh already tapped No).');
        $this->command->line('  3. Tap Approve. It locks to Sneha and leaves Amit’s Open list.');
        $this->command->line('  4. Login as Amit ('.$amit->email.') — Desai Exports is gone from Open profiles.');
        $this->command->line('  Customer login: '.$approveCustomer->email.' / '.self::PASSWORD);
        $this->command->newLine();
    }

    /**
     * @param  array{email: string, fname: string, lname: string, phone: string, referral_code: string, city: string}  $row
     */
    private function agent(array $row, ?Category $category, mixed $subCategoryId, UserService $userService): User
    {
        $taken = User::query()
            ->where('referral_code', $row['referral_code'])
            ->where('email', '!=', $row['email'])
            ->exists();

        $code = $taken ? $this->uniqueCode() : $row['referral_code'];

        $user = User::updateOrCreate(
            ['email' => $row['email']],
            [
                'type' => 'agent',
                'fname' => $row['fname'],
                'lname' => $row['lname'],
                'password' => Hash::make(self::PASSWORD),
                'phone' => $row['phone'],
                'country' => 'India',
                'state' => 'Gujarat',
                'city' => $row['city'],
                'category_id' => $category?->id,
                'sub_category_id' => $subCategoryId ? (string) $subCategoryId : null,
                'status' => 1,
                'email_verified_at' => now(),
                'referral_code' => $code,
                'referred_by_id' => null,
            ]
        );

        $userService->ensureCompanyProfile($user);

        return $user->fresh();
    }

    /**
     * @param  array{email: string, fname: string, lname: string, company: string, phone: string, city: string}  $row
     */
    private function customer(User $agent, array $row, ?Category $category, mixed $subCategoryId): User
    {
        $user = User::updateOrCreate(
            ['email' => $row['email']],
            [
                'type' => 'user',
                'fname' => $row['fname'],
                'lname' => $row['lname'],
                'password' => Hash::make(self::PASSWORD),
                'phone' => $row['phone'],
                'country' => 'India',
                'state' => 'Gujarat',
                'city' => $row['city'],
                'category_id' => $category?->id,
                'sub_category_id' => $subCategoryId ? (string) $subCategoryId : null,
                'status' => 1,
                'email_verified_at' => now(),
                'referral_code' => null,
                'referred_by_id' => $agent->id,
            ]
        );

        $profile = CompanyProfile::query()->where('user_id', $user->id)->first();
        CompanyProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'company_name' => $row['company'],
                'slug' => $profile?->slug ?: CompanyProfile::uniqueSlug(Str::slug($row['company']).'-'.$user->id),
                'owner_name' => trim($row['fname'].' '.$row['lname']),
                'phone' => $row['phone'],
                'email' => $row['email'],
                'city' => $row['city'],
                'state' => 'Gujarat',
                'country' => 'India',
                'tagline' => 'Demo referred by '.$agent->referral_code,
            ]
        );

        return $user->fresh(['companyProfile']);
    }

    private function ensureRates(User $agent): void
    {
        $plans = Plan::query()->whereIn('name', PricingCatalog::purchasableNames())->get();

        foreach ($plans as $plan) {
            AgentCommissionRate::query()->updateOrCreate(
                [
                    'agent_id' => $agent->id,
                    'plan_id' => $plan->id,
                ],
                [
                    'india_percent' => 5,
                    'india_profile_percent' => 5,
                    'global_percent' => 5,
                    'global_profile_percent' => 5,
                ]
            );
        }
    }

    private function uniqueCode(): string
    {
        do {
            $code = 'DEM'.strtoupper(Str::random(5));
        } while (User::query()->where('referral_code', $code)->exists());

        return $code;
    }
}
