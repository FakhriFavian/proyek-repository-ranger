<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('users') || !Schema::hasColumn('users', 'username')) {
            return;
        }

        if (Schema::hasColumn('users', 'identitas')) {
            Schema::table('users', function (Blueprint $table): void {
                $table->string('identitas')->nullable()->change();
            });

            DB::table('users')
                ->whereNull('identitas')
                ->whereNotNull('username')
                ->update(['identitas' => DB::raw('username')]);

            Schema::table('users', function (Blueprint $table): void {
                $table->dropColumn('username');
            });

            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->renameColumn('username', 'identitas');
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('users') || Schema::hasColumn('users', 'username')) {
            return;
        }

        Schema::table('users', function (Blueprint $table): void {
            $table->string('username')->nullable();
        });

        DB::table('users')
            ->whereNull('username')
            ->whereNotNull('identitas')
            ->update(['username' => DB::raw('identitas')]);
    }
};
