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
                ->where('identitas', '24761')
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => 'Fakhri Favian Ramadhan',
                    'username' => 'fakhri24761',
                    'email' => 'fakhri.favian@example.com',
                    'identitas' => '24761',
                    'kelas' => 'XII PPLG 1',
                    'password' => Hash::make('Fakhri123'),
                ]);
            } else {
                $user->update([
                    'name' => 'Fakhri Favian Ramadhan',
                    'username' => 'fakhri24761',
                    'email' => 'fakhri.favian@example.com',
                    'identitas' => '24761',
                    'kelas' => 'XII PPLG 1',
                    'password' => Hash::make('Fakhri123'),
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

        $this->command?->info('Akun siswa Fakhri Favian Ramadhan siap digunakan.');
    }
}
