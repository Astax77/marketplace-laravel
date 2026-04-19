<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $casablancaId = City::where('name', 'Casablanca')->value('id');
        $rabatId      = City::where('name', 'Rabat')->value('id');
        $marrakechId  = City::where('name', 'Marrakech')->value('id');

        $users = [
            [
                'name'    => 'Administrateur Demo',
                'email'   => 'admin@demo.ma',
                'phone'   => '0600000000',
                'city_id' => $casablancaId,
            ],
            [
                'name'    => 'Ahmed Benali',
                'email'   => 'ahmed@demo.ma',
                'phone'   => '0611111111',
                'city_id' => $casablancaId,
            ],
            [
                'name'    => 'Fatima Zahra',
                'email'   => 'fatima@demo.ma',
                'phone'   => '0622222222',
                'city_id' => $rabatId,
            ],
            [
                'name'    => 'Youssef Alami',
                'email'   => 'youssef@demo.ma',
                'phone'   => '0633333333',
                'city_id' => $marrakechId,
            ],
            [
                'name'    => 'Sara Idrissi',
                'email'   => 'sara@demo.ma',
                'phone'   => '0644444444',
                'city_id' => $casablancaId,
            ],
            [
                'name'    => 'Mohammed Tazi',
                'email'   => 'mo@demo.ma',
                'phone'   => '0655555555',
                'city_id' => $rabatId,
            ],
            [
                'name'    => 'Karim Mansouri',
                'email'   => 'karim@demo.ma',
                'phone'   => '0677777777',
                'city_id' => $marrakechId,
            ],
            [
                'name'    => 'Leila Berrada',
                'email'   => 'leila@demo.ma',
                'phone'   => '0688888888',
                'city_id' => $casablancaId,
            ],
        ];

        foreach ($users as $data) {
            User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'              => $data['name'],
                    'password'          => Hash::make('password'),
                    'phone'             => $data['phone'],
                    'city_id'           => $data['city_id'],
                    'email_verified_at' => now(),
                    'is_active'         => true,
                ]
            );
        }
    }
}
