<?php

use App\Models\User;
use App\Services\Front\ProfileService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_profile_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->unique();
            $table->unsignedBigInteger('source_agent_id');
            $table->unsignedBigInteger('assigned_agent_id')->nullable();
            $table->string('status', 20)->default('exclusive');
            $table->timestamp('exclusive_until')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamp('opened_at')->nullable();
            $table->timestamp('locked_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'exclusive_until']);
            $table->index('source_agent_id');
            $table->index('assigned_agent_id');
        });

        $profileService = app(ProfileService::class);
        $now = now();

        User::query()
            ->where('type', 'user')
            ->whereNotNull('referred_by_id')
            ->orderBy('id')
            ->chunkById(100, function ($users) use ($profileService, $now) {
                foreach ($users as $user) {
                    $exclusiveUntil = ($user->created_at ?? $now)->copy()->addHours(48);
                    $isGreen = $profileService->completionLevel($user) === 'complete';
                    $expired = $exclusiveUntil->lte($now);

                    if ($isGreen) {
                        $status = 'green';
                        $assigned = $user->referred_by_id;
                        $completedAt = $now;
                        $openedAt = null;
                    } elseif ($expired) {
                        $status = 'open';
                        $assigned = null;
                        $completedAt = null;
                        $openedAt = $now;
                    } else {
                        $status = 'exclusive';
                        $assigned = $user->referred_by_id;
                        $completedAt = null;
                        $openedAt = null;
                    }

                    DB::table('agent_profile_tasks')->insert([
                        'customer_id' => $user->id,
                        'source_agent_id' => $user->referred_by_id,
                        'assigned_agent_id' => $assigned,
                        'status' => $status,
                        'exclusive_until' => $exclusiveUntil,
                        'opened_at' => $openedAt,
                        'completed_at' => $completedAt,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_profile_tasks');
    }
};
