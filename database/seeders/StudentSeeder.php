<?php

namespace Database\Seeders;

use App\Models\User;
use App\Modules\Role\Models\Role;
use App\Modules\UserRole\Models\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function (): void {
            $role = Role::where('role', 'Siswa')
                ->whereNull('deleted_at')
                ->first();

            if (!$role) {
                throw new RuntimeException('Role Siswa tidak ditemukan.');
            }

            $user = User::withTrashed()
                ->where('identitas', '123456789')
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => 'Siswa Percobaan',
                    'username' => 'siswa123456789',
                    'email' => 'siswa123456789@example.com',
                    'identitas' => '123456789',
                    'kelas' => 'XII PPLG 1',
                    'password' => Hash::make('12345678'),
                ]);
            }

            $userRole = UserRole::withTrashed()
                ->where('id_user', $user->id)
                ->where('id_role', $role->id)
                ->first();

            if (!$userRole) {
                UserRole::create([
                    'id_user' => $user->id,
                    'id_role' => $role->id,
                ]);
            } elseif ($userRole->trashed()) {
                $userRole->restore();
            }
        });

        $this->command?->info('Akun siswa siap digunakan.');
    }
}
