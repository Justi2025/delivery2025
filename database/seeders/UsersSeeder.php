<?php

namespace Database\Seeders;

use App\Khranilischa\RoliPolzovatelei;
use App\Khranilischa\UserStatus;
use Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     *
     */
    public function run(): void
    {
        $users = [
            [
                'phone' => '79407777777',
                'password' => Hash::make('79407777777'),
                'creator_id' => null,
                'full_name' => 'Главный админ',
                'year_of_birth' => '1997',
                'city_id' => 5,
                'street' => 'Qwerty',
                'house' => '100',
                'flat' => '1',
                'role' => RoliPolzovatelei::Ahmad,
                'status' => UserStatus::Activated,
                'phone_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'phone' => '79400000000',
                'password' => Hash::make('79400000000'),
                'creator_id' => null,
                'full_name' => 'Иванов Иван Иванович',
                'year_of_birth' => '1985',
                'city_id' => 1,
                'street' => 'Абазгаа',
                'house' => '14',
                'flat' => '1',
                'role' => RoliPolzovatelei::Klient,
                'status' => UserStatus::Activated,
                'phone_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'phone' => '79400000005',
                'password' => Hash::make('79400000005'),
                'creator_id' => null,
                'full_name' => 'Андреев Андрей Андреевич',
                'year_of_birth' => '1985',
                'city_id' => 1,
                'street' => 'Абазгаа',
                'house' => '14',
                'flat' => '1',
                'role' => RoliPolzovatelei::Klient,
                'status' => UserStatus::Activated,
                'phone_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'phone' => '79400000001',
                'password' => Hash::make('79400000001'),
                'creator_id' => null,
                'full_name' => 'Дамиров Дамир Дамирович',
                'year_of_birth' => '1998',
                'city_id' => 2,
                'street' => 'Ардзинба',
                'house' => '102',
                'flat' => '1',
                'role' => RoliPolzovatelei::Voditel,
                'status' => UserStatus::Activated,
                'phone_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'phone' => '79400000002',
                'password' => Hash::make('79400000002'),
                'creator_id' => null,
                'full_name' => 'Петров Петр Петрович',
                'year_of_birth' => '1978',
                'city_id' => 3,
                'street' => 'Ленина',
                'house' => '55',
                'flat' => '12',
                'role' => RoliPolzovatelei::ZamAhmada,
                'status' => UserStatus::Activated,
                'phone_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'phone' => '79400000003',
                'password' => Hash::make('79400000003'),
                'creator_id' => null,
                'full_name' => 'Сергеев Сергей Сергеевич',
                'year_of_birth' => '1978',
                'city_id' => 3,
                'street' => 'Бубнова',
                'house' => '12',
                'flat' => '1',
                'role' => RoliPolzovatelei::Skladovschik,
                'status' => UserStatus::Activated,
                'phone_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        DB::table('users')->insert($users);
    }
}
