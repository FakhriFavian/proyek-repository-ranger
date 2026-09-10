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
                ->where('identitas', '24763')
                ->first();

            if (!$user) {
                $user = User::create([
                    'name' => 'Grycelda Nabeeha Zahra',
                    'identitas' => '24763',
                    'email' => 'grycelda.nabeeha@example.com',
                    'identitas' => '24763',
                    'kelas' => 'XII PPLG 2',
                    'password' => Hash::make('icel234'),
                ]);
            } else {
                $user->update([
                    'name' => 'Grycelda Nabeeha Zahra',
                    'identitas' => '24763',
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
                    'name' => 'Fakhri Favian Ramadhan',
                    'email' => 'fakhri.favian@example.com',
                    'identitas' => '24761',
                    'kelas' => 'XII PPLG 2',
                    'password' => 'Fakhri123',
                ],
                [
                    'name' => 'Adinda Pertiwi',
                    'identitas' => '24750',
                    'email' => 'adinda.pertiwi@example.com',
                    'identitas' => '24750',
                    'kelas' => 'XII PPLG 2',
                    'password' => 'dinda750',
                ],
                [
                    'name' => 'Khansa',
                    'identitas' => '24768',
                    'email' => 'khansa@example.com',
                    'identitas' => '24768',
                    'kelas' => 'XII PPLG 2',
                    'password' => 'saa4321',
                ],
                [
                    'name' => 'Najla Abida',
                    'identitas' => '24773',
                    'email' => 'najla.abida@example.com',
                    'identitas' => '24773',
                    'kelas' => 'XII PPLG 2',
                    'password' => 'najla345',
                ],
                [
                    'name' => 'Aura Naylus Sava',
                    'identitas' => '24756',
                    'email' => 'aura.naylus@example.com',
                    'identitas' => '24756',
                    'kelas' => 'XII PPLG 2',
                    'password' => 'Aura111',
                ],
                [
                    'name' => 'Anindia Rosa Viona Bachtiar',
                    'identitas' => '24755',
                    'email' => 'anindia.rosa@example.com',
                    'kelas' => 'XII PPLG 2',
                    'password' => 'vio089',
                ],
            ];

            foreach ($students as $student) {
                $user = User::withTrashed()
                    ->where('identitas', $student['identitas'])
                    ->first();

                $userData = [
                    'name' => $student['name'],
                    'identitas' => $student['identitas'],
                    'email' => $student['email'],
                    'identitas' => $student['identitas'],
                    'kelas' => $student['kelas'],
                    'password' => Hash::make($student['password']),
                ];

                if (!$user) {
                    $user = User::create($userData);
                } else {
                    if ($user->trashed()) {
                        $user->restore();
                    }

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

        $this->command?->info('Akun siswa Fakhri, Grycelda, Adinda, Khansa, Najla, Aura, dan Anindia siap digunakan.');
    }
}
