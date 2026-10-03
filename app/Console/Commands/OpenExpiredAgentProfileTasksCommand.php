<?php

namespace App\Console\Commands;

use App\Services\Front\AgentProfileTaskService;
use Illuminate\Console\Command;

class OpenExpiredAgentProfileTasksCommand extends Command
{
    protected $signature = 'agent:open-expired-profiles';

    protected $description = 'Move exclusive referred profiles to Open after the 48-hour window';

    public function handle(AgentProfileTaskService $tasks): int
    {
        $opened = $tasks->expireExclusive();
        $this->info("Opened {$opened} expired exclusive profile(s).");

        return self::SUCCESS;
    }
}
