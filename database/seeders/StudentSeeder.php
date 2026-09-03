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

            $user = User::withTrashed()
                ->where('identitas', '24763')
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => 'Grycelda Nabeeha Zahra',
                    'username' => 'grycelda24763',
                    'email' => 'grycelda.nabeeha@example.com',
                    'identitas' => '24763',
                    'kelas' => 'XII PPLG 2',
                    'password' => Hash::make('icel234'),
                ]);
            } else {
                $user->update([
                    'name' => 'Grycelda Nabeeha Zahra',
                    'username' => 'grycelda24763',
                    'email' => 'grycelda.nabeeha@example.com',
                    'identitas' => '24763',
                    'kelas' => 'XII PPLG 2',
                    'password' => Hash::make('icel234'),
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

            $students = [
                [
                    'name' => 'Adinda Pertiwi',
                    'username' => 'adinda24750',
                    'email' => 'adinda.pertiwi@example.com',
                    'identitas' => '24750',
                    'kelas' => 'XII PPLG 2',
                    'password' => 'dinda750',
                ],
                [
                    'name' => 'Khansa',
                    'username' => 'khansa24768',
                    'email' => 'khansa@example.com',
                    'identitas' => '24768',
                    'kelas' => 'XII PPLG 2',
                    'password' => 'saa4321',
                ],
                [
                    'name' => 'Najla Abida',
                    'username' => 'najla24773',
                    'email' => 'najla.abida@example.com',
                    'identitas' => '24773',
                    'kelas' => 'XII PPLG 2',
                    'password' => 'najla345',
                ],
                [
                    'name' => 'Aura Naylus Sava',
                    'username' => 'aura24756',
                    'email' => 'aura.naylus@example.com',
                    'identitas' => '24756',
                    'kelas' => 'XII PPLG 2',
                    'password' => 'Aura111',
                ],
            ];

            foreach ($students as $student) {
                $user = User::withTrashed()
                    ->where('identitas', $student['identitas'])
                    ->first();

                $userData = [
                    'name' => $student['name'],
                    'username' => $student['username'],
                    'email' => $student['email'],
                    'identitas' => $student['identitas'],
                    'kelas' => $student['kelas'],
                    'password' => Hash::make($student['password']),
                ];

                if (!$user) {
                    $user = User::create($userData);
                } else {
                    $user->update($userData);
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
            }
        });

        $this->command?->info('Akun siswa Fakhri, Grycelda, Adinda, Khansa, Najla, dan Aura siap digunakan.');
    }
}
