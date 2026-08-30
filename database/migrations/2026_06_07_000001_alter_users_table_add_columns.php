<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'type')) {
                $table->enum('type', ['admin', 'user', 'agent'])->default('user')->after('id');
            }
            if (! Schema::hasColumn('users', 'fname')) {
                $table->string('fname', 100)->nullable()->after(Schema::hasColumn('users', 'type') ? 'type' : 'id');
            }
            if (! Schema::hasColumn('users', 'lname')) {
                $table->string('lname', 100)->nullable()->after('fname');
            }
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('password');
            }
            if (! Schema::hasColumn('users', 'country')) {
                $table->string('country', 100)->nullable()->after('phone');
            }
            if (! Schema::hasColumn('users', 'state')) {
                $table->string('state', 100)->nullable()->after('country');
            }
            if (! Schema::hasColumn('users', 'city')) {
                $table->string('city', 100)->nullable()->after('state');
            }
            if (! Schema::hasColumn('users', 'profile')) {
                $table->string('profile')->nullable();
            }
            if (! Schema::hasColumn('users', 'status')) {
                $table->unsignedTinyInteger('status')->default(1);
            }
            if (! Schema::hasColumn('users', 'referral_code')) {
                $table->string('referral_code', 20)->nullable()->unique()->after('remember_token');
            }
            if (! Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
        });

        if (! Schema::hasColumn('users', 'category_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('category_id')->nullable();
            });
        }

        if (! Schema::hasColumn('users', 'sub_category_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('sub_category_id', 255)->nullable();
            });
        }

        if (Schema::hasColumn('users', 'name')) {
            DB::table('users')->whereNotNull('name')->whereNull('fname')->update([
                'fname' => DB::raw('name'),
                'lname' => '',
            ]);

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }

        $indexNames = collect(DB::select('SHOW INDEX FROM users'))->pluck('Key_name')->map(fn ($n) => strtolower($n));

        Schema::table('users', function (Blueprint $table) use ($indexNames) {
            if (! $indexNames->contains('users_type_status_deleted_at_index')) {
                $table->index(['type', 'status', 'deleted_at']);
            }
            if (! $indexNames->contains('users_category_id_sub_category_id_index')) {
                $table->index(['category_id', 'sub_category_id']);
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasColumn('users', 'name')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('name')->nullable();
            });

            DB::table('users')->update([
                'name' => DB::raw("TRIM(CONCAT(COALESCE(fname, ''), ' ', COALESCE(lname, '')))"),
            ]);
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'sub_category_id')) {
                $table->dropColumn('sub_category_id');
            }
            if (Schema::hasColumn('users', 'category_id')) {
                $table->dropColumn('category_id');
            }
            $columns = ['fname', 'lname', 'phone', 'country', 'state', 'city', 'profile', 'status', 'referral_code'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
        });
    }
};
