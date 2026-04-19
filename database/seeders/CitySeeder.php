<?php

namespace Database\Seeders;

use App\Models\City;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        $cities = [
            ['name' => 'Casablanca',   'region' => 'Casablanca-Settat'],
            ['name' => 'Rabat',        'region' => 'Rabat-Salé-Kénitra'],
            ['name' => 'Marrakech',    'region' => 'Marrakech-Safi'],
            ['name' => 'Fès',          'region' => 'Fès-Meknès'],
            ['name' => 'Tanger',       'region' => 'Tanger-Tétouan-Al Hoceïma'],
            ['name' => 'Agadir',       'region' => 'Souss-Massa'],
            ['name' => 'Meknès',       'region' => 'Fès-Meknès'],
            ['name' => 'Oujda',        'region' => 'Oriental'],
            ['name' => 'Kenitra',      'region' => 'Rabat-Salé-Kénitra'],
            ['name' => 'Tétouan',      'region' => 'Tanger-Tétouan-Al Hoceïma'],
            ['name' => 'Safi',         'region' => 'Marrakech-Safi'],
            ['name' => 'Mohammedia',   'region' => 'Casablanca-Settat'],
            ['name' => 'Khouribga',    'region' => 'Béni Mellal-Khénifra'],
            ['name' => 'El Jadida',    'region' => 'Casablanca-Settat'],
            ['name' => 'Béni Mellal',  'region' => 'Béni Mellal-Khénifra'],
            ['name' => 'Nador',        'region' => 'Oriental'],
            ['name' => 'Settat',       'region' => 'Casablanca-Settat'],
            ['name' => 'Berrechid',    'region' => 'Casablanca-Settat'],
            ['name' => 'Khémisset',    'region' => 'Rabat-Salé-Kénitra'],
            ['name' => 'Larache',      'region' => 'Tanger-Tétouan-Al Hoceïma'],
        ];

        foreach ($cities as $city) {
            City::firstOrCreate(['name' => $city['name']], $city + ['is_active' => true]);
        }
    }
}
