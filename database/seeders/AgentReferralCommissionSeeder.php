<?php

namespace Database\Seeders;

use App\Models\AgentCommissionRate;
use App\Models\Article;
use App\Models\Category;
use App\Models\CompanyProfile;
use App\Models\Document;
use App\Models\Offer;
use App\Models\PaymentLog;
use App\Models\Plan;
use App\Models\Project;
use App\Models\Service;
use App\Models\Team;
use App\Models\User;
use App\Models\UserPlan;
use App\Models\Video;
use App\Services\Commission\CommissionCalculator;
use App\Support\PricingCatalog;
use App\Support\ProjectSection;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * Imports 10 customers referred by agent code DEMI0001, with Silver payments
 * and mixed profile completion so commission slices can be checked.
 *
 * php artisan db:seed --class=AgentReferralCommissionSeeder
 *
 * @author KP PATEL
 */
class AgentReferralCommissionSeeder extends Seeder
{
    public const REFERRAL_CODE = 'DEMI0001';

    public const CUSTOMER_PASSWORD = 'password123';

    public function run(): void
    {
        $agent = User::query()
            ->where('type', 'agent')
            ->where('status', 1)
            ->whereRaw('UPPER(referral_code) = ?', [self::REFERRAL_CODE])
            ->first();

        if (! $agent) {
            $this->command->error('No active agent found with referral code '.self::REFERRAL_CODE.'. Create the agent in Admin → Users first.');

            return;
        }

        $plan = Plan::query()->where('name', 'Silver')->first();
        if (! $plan) {
            $this->command->error('Silver plan is missing. Run PlanSeeder first.');

            return;
        }

        $category = Category::query()->where('status', 1)->orderBy('id')->first();
        $subCategoryId = $category?->subCategories()->where('status', 1)->orderBy('id')->value('id');

        $this->ensureRates($agent);

        $calculator = app(CommissionCalculator::class);
        $amount = (float) $plan->rate;

        $customers = [
            ['fname' => 'Aarav', 'lname' => 'Shah', 'city' => 'Ahmedabad', 'sections' => 0],
            ['fname' => 'Diya', 'lname' => 'Patel', 'city' => 'Surat', 'sections' => 0],
            ['fname' => 'Kabir', 'lname' => 'Mehta', 'city' => 'Vadodara', 'sections' => 0],
            ['fname' => 'Anaya', 'lname' => 'Desai', 'city' => 'Rajkot', 'sections' => 4],
            ['fname' => 'Vivaan', 'lname' => 'Joshi', 'city' => 'Ahmedabad', 'sections' => 4],
            ['fname' => 'Isha', 'lname' => 'Trivedi', 'city' => 'Gandhinagar', 'sections' => 4],
            ['fname' => 'Reyansh', 'lname' => 'Dave', 'city' => 'Bhavnagar', 'sections' => 6],
            ['fname' => 'Myra', 'lname' => 'Raval', 'city' => 'Jamnagar', 'sections' => 6],
            ['fname' => 'Advait', 'lname' => 'Parikh', 'city' => 'Anand', 'sections' => 8],
            ['fname' => 'Kiara', 'lname' => 'Gandhi', 'city' => 'Navsari', 'sections' => 8],
        ];

        foreach ($customers as $index => $row) {
            $n = $index + 1;
            $email = sprintf('demi.ref.%02d@justgoom.test', $n);
            $phone = '90000'.str_pad((string) $n, 5, '0', STR_PAD_LEFT);
            $companyName = $row['fname'].' '.$row['lname'].' Traders';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'type' => 'user',
                    'fname' => $row['fname'],
                    'lname' => $row['lname'],
                    'password' => Hash::make(self::CUSTOMER_PASSWORD),
                    'phone' => $phone,
                    'country' => 'India',
                    'state' => 'Gujarat',
                    'city' => $row['city'],
                    'category_id' => $category?->id,
                    'sub_category_id' => $subCategoryId ? (string) $subCategoryId : null,
                    'status' => 1,
                    'email_verified_at' => now(),
                    'referral_code' => $this->customerCode($email, $n),
                    'referred_by_id' => $agent->id,
                ]
            );

            $profile = CompanyProfile::query()->where('user_id', $user->id)->first();
            CompanyProfile::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'company_name' => $companyName,
                    'slug' => $profile?->slug ?: CompanyProfile::uniqueSlug(Str::slug($companyName).'-'.$user->id),
                    'owner_name' => trim($row['fname'].' '.$row['lname']),
                    'phone' => $phone,
                    'email' => $email,
                    'city' => $row['city'],
                    'state' => 'Gujarat',
                    'country' => 'India',
                    'tagline' => 'Referred by '.self::REFERRAL_CODE,
                ]
            );

            $this->fillProfileSections($user, (int) $row['sections']);

            $userPlan = UserPlan::query()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'plan_id' => $plan->id,
                    'purchase_date' => now()->toDateString(),
                    'next_purchase_date' => now()->addDays((int) $plan->duration_days)->toDateString(),
                ]
            );

            $orderId = 'seed_demi0001_u'.$user->id;
            $log = PaymentLog::query()->firstOrCreate(
                ['razorpay_order_id' => $orderId],
                [
                    'user_id' => $user->id,
                    'plan_id' => $plan->id,
                    'user_plan_id' => $userPlan->id,
                    'gateway' => 'razorpay',
                    'razorpay_payment_id' => 'pay_seed_demi_'.$user->id,
                    'amount' => $amount,
                    'amount_paise' => (int) round($amount * 100),
                    'currency' => 'INR',
                    'status' => PaymentLog::STATUS_PAID,
                    'method' => 'seed',
                    'email' => $email,
                    'contact' => $phone,
                    'receipt' => 'SEED-DEMI-'.$user->id,
                    'paid_at' => now(),
                    'payload' => ['source' => 'AgentReferralCommissionSeeder'],
                ]
            );

            if ($log->status !== PaymentLog::STATUS_PAID) {
                $log->update([
                    'status' => PaymentLog::STATUS_PAID,
                    'user_plan_id' => $userPlan->id,
                    'paid_at' => $log->paid_at ?: now(),
                ]);
            }

            if (blank($log->invoice_number)) {
                $log->assignInvoiceNumber();
            }

            $calculator->creditPayment($user->fresh(), $plan, $log->fresh());
            $calculator->creditProfileMilestones($user->fresh());
        }

        $this->command->info('Agent: '.$agent->fullName().' ('.self::REFERRAL_CODE.')');
        $this->command->info('Created/updated 10 referred Silver customers (₹3000 each).');
        $this->command->info('Login: demi.ref.01@justgoom.test … demi.ref.10@justgoom.test / '.self::CUSTOMER_PASSWORD);
        $this->command->info('Profile mix: 1–3 at 0% (₹150), 4–6 at ~56% (₹210), 7–10 at 70%+ (₹300).');
        $this->command->info('Check agent portal earnings after logging in as the DEMI0001 agent.');
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
                    'india_percent' => 10,
                    'global_percent' => 10,
                ]
            );
        }
    }

    private function customerCode(string $email, int $n): string
    {
        $existing = User::query()->where('email', $email)->value('referral_code');
        if (is_string($existing) && $existing !== '') {
            return $existing;
        }

        $code = 'DR'.str_pad((string) $n, 6, '0', STR_PAD_LEFT);

        if (! User::query()->where('referral_code', $code)->exists()) {
            return $code;
        }

        do {
            $code = 'DR'.strtoupper(Str::random(6));
        } while (User::query()->where('referral_code', $code)->exists());

        return $code;
    }

    private function fillProfileSections(User $user, int $sections): void
    {
        if ($sections < 1) {
            return;
        }

        $label = $user->fullName();

        if ($sections >= 1 && $user->teams()->doesntExist()) {
            Team::query()->create([
                'user_id' => $user->id,
                'name' => $label.' Team',
                'designation' => 'Owner',
                'email' => $user->email,
                'phone' => $user->phone,
                'status' => 1,
                'is_primary' => true,
            ]);
        }

        if ($sections >= 2 && $user->services()->where('type', 'service')->doesntExist()) {
            Service::query()->create([
                'user_id' => $user->id,
                'type' => 'service',
                'product_name' => $label.' Consulting',
                'product_desc' => 'Seeded service for commission testing.',
                'price' => '5000',
            ]);
        }

        if ($sections >= 3 && $user->services()->where('type', 'product')->doesntExist()) {
            Service::query()->create([
                'user_id' => $user->id,
                'type' => 'product',
                'product_name' => $label.' Product',
                'product_desc' => 'Seeded product for commission testing.',
                'price' => '2500',
            ]);
        }

        if ($sections >= 4 && $user->projects()->doesntExist()) {
            Project::query()->create([
                'user_id' => $user->id,
                'title' => $label.' Project',
                'description' => 'Seeded project for commission testing.',
                'type' => 'link',
                'section_type' => ProjectSection::NORMAL,
                'status' => 1,
                'external_url' => 'https://justgoom.test',
            ]);
        }

        if ($sections >= 5 && $user->documents()->doesntExist()) {
            Document::query()->create([
                'user_id' => $user->id,
                'title' => $label.' Brochure',
                'attachment' => 'seeded-document.pdf',
                'file_type' => 'pdf',
                'status' => 1,
            ]);
        }

        if ($sections >= 6 && $user->videos()->doesntExist()) {
            Video::query()->create([
                'user_id' => $user->id,
                'title' => $label.' Intro',
                'link' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                'status' => 1,
            ]);
        }

        if ($sections >= 7 && $user->articles()->doesntExist()) {
            Article::query()->create([
                'user_id' => $user->id,
                'title' => $label.' Article',
                'slug' => Article::generateSlug($label.' article '.$user->id),
                'body' => 'Seeded article for commission testing.',
                'status' => 'published',
                'published_at' => now(),
            ]);
        }

        if ($sections >= 8 && $user->offers()->doesntExist()) {
            Offer::query()->create([
                'user_id' => $user->id,
                'title' => $label.' Offer',
                'description' => 'Seeded offer for commission testing.',
                'start_date' => now()->toDateString(),
                'end_date' => now()->addMonth()->toDateString(),
                'status' => 'active',
                'is_featured' => false,
            ]);
        }
    }
}
