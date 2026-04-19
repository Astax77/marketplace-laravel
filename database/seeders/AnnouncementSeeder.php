<?php

namespace Database\Seeders;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\City;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AnnouncementSeeder extends Seeder
{
    /**
     * Real Picsum photos — always available online, no download needed.
     * Format: https://picsum.photos/seed/{word}/800/600
     */
    private array $samples = [
        [
            'title'       => 'iPhone 15 Pro Max 256GB comme neuf',
            'price'       => 12500,
            'condition'   => 'like_new',
            'description' => 'iPhone 15 Pro Max 256GB titane naturel. Acheté il y a 3 mois, jamais tombé, toujours avec coque. Batterie 98%. Boîte et accessoires originaux inclus. Raison vente : passage Android.',
            'images'      => [
                'https://images.unsplash.com/photo-1695048133142-1a20484bce71?w=800',
                'https://images.unsplash.com/photo-1592750475338-74b7b21085ab?w=800',
            ],
        ],
        [
            'title'       => 'Appartement 3 chambres a louer Casablanca Maarif',
            'price'       => 8500,
            'condition'   => 'good',
            'description' => 'Bel appartement 3 chambres au coeur du Maarif. Cuisine équipée, 2 salles de bain, parking. Résidence sécurisée avec gardien. Disponible immédiatement.',
            'images'      => [
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
                'https://images.unsplash.com/photo-1484154218962-a197022b5858?w=800',
            ],
        ],
        [
            'title'       => 'Toyota Corolla 2019 automatique',
            'price'       => 175000,
            'condition'   => 'good',
            'description' => 'Toyota Corolla 2019 boite automatique, essence, 85000km. Première main, carnet entretien complet chez Toyota. Climatisation, GPS, caméra de recul. Pas d\'accident.',
            'images'      => [
                'https://images.unsplash.com/photo-1590362891991-f776e747a588?w=800',
                'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800',
            ],
        ],
        [
            'title'       => 'MacBook Pro M3 14 pouces 16GB',
            'price'       => 22000,
            'condition'   => 'like_new',
            'description' => 'MacBook Pro M3 14 pouces, 16GB RAM, 512GB SSD. Acheté il y a 2 mois. Parfait état, batterie 97%. Vendu avec chargeur et boîte originale.',
            'images'      => [
                'https://images.unsplash.com/photo-1517336714731-489689fd1ca8?w=800',
                'https://images.unsplash.com/photo-1611186871348-b1ce696e52c9?w=800',
            ],
        ],
        [
            'title'       => 'Canape angle en cuir marron 5 places',
            'price'       => 3500,
            'condition'   => 'good',
            'description' => 'Canapé d\'angle en cuir véritable marron, 5 places, très bon état. Acheté 9000 DH. Déménagement oblige. A récupérer sur place à Casablanca.',
            'images'      => [
                'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=800',
                'https://images.unsplash.com/photo-1493663284031-b7e3aefcae8e?w=800',
            ],
        ],
        [
            'title'       => 'Velo de route Trek Domane 21 vitesses',
            'price'       => 2800,
            'condition'   => 'good',
            'description' => 'Vélo de route Trek Domane aluminium, 21 vitesses Shimano. Très bon état, pneus neufs. Idéal pour le sport et les trajets quotidiens.',
            'images'      => [
                'https://images.unsplash.com/photo-1558618666-fcd25c85cd64?w=800',
                'https://images.unsplash.com/photo-1507035895480-2b3156c31fc8?w=800',
            ],
        ],
        [
            'title'       => 'Samsung Galaxy S24 Ultra neuf scelle',
            'price'       => 14000,
            'condition'   => 'new',
            'description' => 'Samsung Galaxy S24 Ultra 256GB noir, neuf scellé jamais ouvert. Garantie officielle Samsung Maroc 1 an. Facture incluse.',
            'images'      => [
                'https://images.unsplash.com/photo-1610945265064-0e34e5519bbf?w=800',
                'https://images.unsplash.com/photo-1583573636894-e608f6a5aff4?w=800',
            ],
        ],
        [
            'title'       => 'Villa 5 pieces avec piscine Marrakech Palmeraie',
            'price'       => 2500000,
            'condition'   => 'good',
            'description' => 'Magnifique villa 5 pièces avec piscine privée dans la Palmeraie de Marrakech. 350m² habitables, jardin paysager, double garage. Vue imprenable sur l\'Atlas.',
            'images'      => [
                'https://images.unsplash.com/photo-1613977257363-707ba9348227?w=800',
                'https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=800',
            ],
        ],
        [
            'title'       => 'Dacia Sandero Stepway 2021 essence 60000km',
            'price'       => 95000,
            'condition'   => 'good',
            'description' => 'Dacia Sandero Stepway 2021, essence, 60000km réels. Entretien à jour, carnet complet. Équipée GPS, bluetooth, caméra. Couleur orange.',
            'images'      => [
                'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800',
                'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=800',
            ],
        ],
        [
            'title'       => 'PlayStation 5 avec 3 jeux inclus',
            'price'       => 5500,
            'condition'   => 'like_new',
            'description' => 'PS5 édition standard parfait état, utilisée 6 mois. Inclus : FIFA 24, Spider-Man 2, God of War Ragnarok. Manette originale. Boite et câbles inclus.',
            'images'      => [
                'https://images.unsplash.com/photo-1607853202273-797f1c22a38e?w=800',
                'https://images.unsplash.com/photo-1622297845775-5ff3fef71d13?w=800',
            ],
        ],
        [
            'title'       => 'Machine a laver LG 8kg Inverter Direct Drive',
            'price'       => 2200,
            'condition'   => 'good',
            'description' => 'Lave-linge LG 8kg Inverter Direct Drive, A+++. 3 ans d\'utilisation, parfait état de fonctionnement. Raison : déménagement dans appartement déjà équipé.',
            'images'      => [
                'https://images.unsplash.com/photo-1626806787461-102c1bfaaea1?w=800',
                'https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=800',
            ],
        ],
        [
            'title'       => 'Bureau en bois massif avec caissons de rangement',
            'price'       => 1800,
            'condition'   => 'good',
            'description' => 'Grand bureau en bois massif 180x80cm avec 3 caissons de rangement. Très solide, peu utilisé. Idéal pour télétravail ou bureau professionnel.',
            'images'      => [
                'https://images.unsplash.com/photo-1518455027359-f3f8164ba6bd?w=800',
                'https://images.unsplash.com/photo-1593642632559-0c6d3fc62b89?w=800',
            ],
        ],
        [
            'title'       => 'Television Samsung 65 pouces 4K QLED',
            'price'       => 7500,
            'condition'   => 'like_new',
            'description' => 'TV Samsung 65 pouces QLED 4K. Achetée il y a 1 an, parfait état. SmartTV avec Netflix, YouTube intégrés. Télécommande et support mural inclus.',
            'images'      => [
                'https://images.unsplash.com/photo-1593359677879-a4bb92f4834c?w=800',
                'https://images.unsplash.com/photo-1461151304267-38535e780c79?w=800',
            ],
        ],
        [
            'title'       => 'Appareil photo Canon EOS 250D avec objectif',
            'price'       => 4500,
            'condition'   => 'like_new',
            'description' => 'Reflex Canon EOS 250D + objectif 18-55mm IS STM. Moins de 5000 déclenchements. Parfait pour débutant. Livré avec sac, chargeur, 2 batteries et carte 64GB.',
            'images'      => [
                'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?w=800',
                'https://images.unsplash.com/photo-1502920917128-1aa500764cbd?w=800',
            ],
        ],
        [
            'title'       => 'Trottinette electrique Xiaomi Pro 2',
            'price'       => 3200,
            'condition'   => 'good',
            'description' => 'Trottinette électrique Xiaomi Mi Scooter Pro 2, autonomie 45km. 2 ans d\'utilisation, batterie en bonne santé. Idéale pour trajets urbains.',
            'images'      => [
                'https://images.unsplash.com/photo-1601758174493-45d0a4d3e407?w=800',
                'https://images.unsplash.com/photo-1598300042247-d088f8ab3a91?w=800',
            ],
        ],
        [
            'title'       => 'Climatiseur Daikin 12000 BTU Inverter',
            'price'       => 5500,
            'condition'   => 'like_new',
            'description' => 'Climatiseur Daikin 12000 BTU Inverter, chaud/froid. Installé il y a 2 ans, parfait fonctionnement. Inclus télécommande et support. Pose non incluse.',
            'images'      => [
                'https://images.unsplash.com/photo-1585771724684-38269d6639fd?w=800',
            ],
        ],
        [
            'title'       => 'Motocyclette Yamaha MT-07 2020 bleu',
            'price'       => 65000,
            'condition'   => 'good',
            'description' => 'Yamaha MT-07 2020, 35000km, toujours révisée chez concessionnaire. Equipée sacoches, protections. Moto parfaitement entretenue, aucun accident.',
            'images'      => [
                'https://images.unsplash.com/photo-1558981285-6f0c8f7e3f0b?w=800',
                'https://images.unsplash.com/photo-1568772585407-9361f9bf3a87?w=800',
            ],
        ],
        [
            'title'       => 'Drone DJI Mini 3 Pro avec telecommande RC',
            'price'       => 8500,
            'condition'   => 'like_new',
            'description' => 'DJI Mini 3 Pro avec télécommande RC écran intégré. 3 batteries, sac de transport, filtres ND inclus. Moins de 2h de vol. Parfait état.',
            'images'      => [
                'https://images.unsplash.com/photo-1473968512647-3e447244af8f?w=800',
                'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800',
            ],
        ],
        [
            'title'       => 'Appartement studio meuble Rabat Agdal',
            'price'       => 3500,
            'condition'   => 'good',
            'description' => 'Studio meublé 35m² à Agdal Rabat. Cuisine équipée, WiFi inclus, eau chaude. Proche tramway et commerces. Libre immédiatement.',
            'images'      => [
                'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?w=800',
                'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?w=800',
            ],
        ],
        [
            'title'       => 'Chaise de bureau ergonomique Herman Miller',
            'price'       => 4500,
            'condition'   => 'good',
            'description' => 'Chaise de bureau ergonomique haut de gamme, réglable en hauteur, accoudoirs 4D. Très bon état, utilisée 2 ans en télétravail. Idéale pour longues sessions.',
            'images'      => [
                'https://images.unsplash.com/photo-1589712431706-aa2e8d3df9bb?w=800',
                'https://images.unsplash.com/photo-1527515637462-cff94eecc1ac?w=800',
            ],
        ],
        [
            'title'       => 'Renault Clio 5 RS Line 2022 essence',
            'price'       => 185000,
            'condition'   => 'like_new',
            'description' => 'Renault Clio 5 RS Line 2022, essence, 25000km. Première main, garanti Renault jusqu\'en 2025. Full options : GPS, caméra, capteurs parking.',
            'images'      => [
                'https://images.unsplash.com/photo-1541899481282-d53bffe3c35d?w=800',
                'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800',
            ],
        ],
        [
            'title'       => 'Frigidaire Brandt 350L No Frost double porte',
            'price'       => 2800,
            'condition'   => 'like_new',
            'description' => 'Réfrigérateur-congélateur Brandt 350L No Frost, A++. 2 ans d\'utilisation, parfait état. Couleur inox. Dégivrage automatique. Raison : déménagement.',
            'images'      => [
                'https://images.unsplash.com/photo-1584568694244-14fbdf83bd30?w=800',
            ],
        ],
        [
            'title'       => 'Terrain 600m2 zone villa titre foncier El Jadida',
            'price'       => 350000,
            'condition'   => 'new',
            'description' => 'Terrain 600m² en zone villa à El Jadida, titre foncier en main. Viabilisé : eau, électricité, assainissement. Permis de construire R+1 possible.',
            'images'      => [
                'https://images.unsplash.com/photo-1500382017468-9049fed747ef?w=800',
                'https://images.unsplash.com/photo-1416331108676-a22ccb276e35?w=800',
            ],
        ],
        [
            'title'       => 'Guitare acoustique Yamaha F310 avec housse',
            'price'       => 600,
            'condition'   => 'good',
            'description' => 'Guitare acoustique Yamaha F310, cordes neuves. Parfaite pour débutant ou intermédiaire. Livrée avec housse de transport et accordeur.',
            'images'      => [
                'https://images.unsplash.com/photo-1510915361894-db8b60106cb1?w=800',
                'https://images.unsplash.com/photo-1525201548942-d8732f6617a0?w=800',
            ],
        ],
        [
            'title'       => 'Imprimante HP LaserJet Pro M404dn',
            'price'       => 1800,
            'condition'   => 'like_new',
            'description' => 'Imprimante laser HP LaserJet Pro M404dn, recto-verso automatique, réseau. Toner neuf inclus (5000 pages). Parfaite pour bureau ou télétravail.',
            'images'      => [
                'https://images.unsplash.com/photo-1612815154858-60aa4c59eaa6?w=800',
            ],
        ],
    ];

    public function run(): void
    {
        $users      = User::all();
        $categories = Category::whereNotNull('parent_id')->get();
        $cities     = City::all();

        if ($users->isEmpty() || $categories->isEmpty() || $cities->isEmpty()) {
            $this->command->warn('Skipping: missing users, categories, or cities.');
            return;
        }

        foreach ($this->samples as $sample) {
            $slug = Str::slug($sample['title']) . '-' . Str::random(5);

            // Avoid duplicate slugs
            while (Announcement::where('slug', $slug)->exists()) {
                $slug = Str::slug($sample['title']) . '-' . Str::random(8);
            }

            Announcement::create([
                'user_id'       => $users->random()->id,
                'category_id'   => $categories->random()->id,
                'city_id'       => $cities->random()->id,
                'title'         => $sample['title'],
                'slug'          => $slug,
                'description'   => $sample['description'],
                'price'         => $sample['price'],
                'condition'     => $sample['condition'],
                'status'        => 'active',
                'is_negotiable' => (bool) rand(0, 1),
                'views_count'   => rand(10, 900),
                // Store full URLs directly — no local files needed
                'images'        => $sample['images'],
            ]);
        }

        $this->command->info(count($this->samples) . ' announcements seeded with images.');
    }
}
