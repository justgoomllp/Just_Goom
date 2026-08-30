<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\Admin\UserService;
use Illuminate\Console\Command;

class SyncCompanyProfilesCommand extends Command
{
    protected $signature = 'justgoom:sync-company-profiles';

    protected $description = 'Create missing company profiles for front users and verify admin-created accounts';

    public function handle(UserService $userService): int
    {
        $created = $userService->syncMissingCompanyProfiles();

        $verified = User::query()
            ->whereIn('type', ['user', 'agent'])
            ->where('status', 1)
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);

        $this->info("Created {$created} company profile(s).");
        $this->info("Verified {$verified} user account(s).");

        return self::SUCCESS;
    }
}
