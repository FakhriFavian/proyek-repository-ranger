<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('users', 'kelas')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('kelas')->nullable()->after('identitas');
            });
        }

        if (!DB::table('role')->whereRaw('LOWER(role) = ?', ['siswa'])->whereNull('deleted_at')->exists()) {
            DB::table('role')->insert([
                'id' => (string) Str::uuid(),
                'role' => 'Siswa',
                'level' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('users', 'kelas')) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('kelas');
            });
        }
    }
};
