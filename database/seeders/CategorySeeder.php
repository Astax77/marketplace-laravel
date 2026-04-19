<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $tree = [
            ['name' => 'Immobilier',       'icon' => 'bi-house-door',      'children' => [
                'Appartements à vendre', 'Appartements à louer',
                'Maisons & Villas', 'Terrains & Fermes', 'Bureaux & Locaux',
            ]],
            ['name' => 'Véhicules',        'icon' => 'bi-car-front',       'children' => [
                'Voitures', 'Motos', 'Camions & Bus', 'Bateaux',
                'Pièces & Accessoires auto',
            ]],
            ['name' => 'Électronique',     'icon' => 'bi-phone',           'children' => [
                'Téléphones & Tablettes', 'Ordinateurs & Laptops',
                'TV & Audio', 'Électroménager', 'Consoles & Jeux vidéo',
            ]],
            ['name' => 'Mode & Beauté',    'icon' => 'bi-bag',             'children' => [
                'Vêtements Femmes', 'Vêtements Hommes', 'Chaussures',
                'Accessoires & Montres', 'Beauté & Santé',
            ]],
            ['name' => 'Maison & Jardin',  'icon' => 'bi-tree',            'children' => [
                'Meubles', 'Décoration', 'Électroménager', 'Jardinage',
            ]],
            ['name' => 'Animaux',          'icon' => 'bi-heart',           'children' => [
                'Chiens', 'Chats', 'Oiseaux', 'Poissons', 'Accessoires animaux',
            ]],
            ['name' => 'Emploi',           'icon' => 'bi-briefcase',       'children' => [
                'Offres d\'emploi', 'Services proposés', 'Cours particuliers',
            ]],
            ['name' => 'Loisirs & Sports', 'icon' => 'bi-bicycle',        'children' => [
                'Livres & Magazines', 'Sport & Fitness', 'Jeux & Jouets',
                'Instruments de musique', 'Voyages',
            ]],
        ];

        foreach ($tree as $parentData) {
            $parent = Category::firstOrCreate(
                ['slug' => Str::slug($parentData['name'])],
                [
                    'name'      => $parentData['name'],
                    'icon'      => $parentData['icon'],
                    'is_active' => true,
                ]
            );

            foreach ($parentData['children'] as $childName) {
                Category::firstOrCreate(
                    ['slug' => Str::slug($childName)],
                    [
                        'name'      => $childName,
                        'parent_id' => $parent->id,
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
