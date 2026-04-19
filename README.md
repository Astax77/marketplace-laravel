# 🏷️ PetitesAnnonces – Plateforme C2C Laravel

Plateforme de petites annonces C2C (Consumer-to-Consumer) similaire à Avito, développée avec **Laravel 11**, Bootstrap 5 et Eloquent ORM.

---

## 📋 Fonctionnalités

| Module | Détails |
|---|---|
| **Authentification** | Inscription, Connexion, Déconnexion, Souvenir de moi |
| **Profil** | Modifier infos, photo de profil, changement mot de passe |
| **Annonces** | CRUD complet, upload photos (5 max), catégorisation hiérarchique |
| **Statuts** | Actif, Vendu, Suspendu – changeable par le propriétaire |
| **Recherche** | Multicritères : mot-clé, catégorie, ville, fourchette prix, état |
| **Messagerie** | Conversations entre acheteurs et vendeurs, compteur non-lus |
| **Sécurité** | Policies, Form Requests, Middlewares Auth & Guest |

---

## 🏗️ Architecture

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/         # LoginController, RegisterController, ProfileController
│   │   ├── AnnouncementController.php
│   │   ├── MessageController.php
│   │   ├── SearchController.php
│   │   └── HomeController.php
│   ├── Requests/         # StoreAnnouncement, UpdateAnnouncement, SendMessage
│   └── Middleware/
├── Models/               # User, Announcement, Category, City, Conversation, Message
├── Policies/             # AnnouncementPolicy
└── Services/             # AnnouncementService, SearchService, MessageService
```

**Pattern MVC strict** – toute la logique métier est extraite des contrôleurs dans des Services.

---

## 🚀 Installation

### Prérequis
- PHP >= 8.2
- Composer
- MySQL 8+ ou MariaDB 10.6+
- Node.js (optionnel)

### Étapes

```bash
# 1. Cloner le projet
git clone https://github.com/votre-repo/avito-clone.git
cd avito-clone

# 2. Installer les dépendances PHP
composer install

# 3. Copier le fichier d'environnement
cp .env.example .env

# 4. Générer la clé d'application
php artisan key:generate

# 5. Configurer la base de données dans .env
# DB_DATABASE=avito_clone
# DB_USERNAME=root
# DB_PASSWORD=votre_mdp

# 6. Créer la base de données
mysql -u root -p -e "CREATE DATABASE avito_clone CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"

# 7. Exécuter les migrations
php artisan migrate

# 8. Injecter les données de test
php artisan db:seed

# 9. Créer le lien symbolique pour le storage
php artisan storage:link

# 10. Démarrer le serveur de développement
php artisan serve
```

Accéder à : **http://localhost:8000**

### Compte démo
- **Email :** admin@demo.ma
- **Mot de passe :** password

---

## 🗄️ Modèle de données

```
cities          id, name, region, is_active
categories      id, parent_id*, name, slug, icon, is_active
users           id, city_id*, name, email, password, phone, avatar, bio
announcements   id, user_id*, category_id*, city_id*, title, slug,
                description, price, condition, status, images(JSON), views_count
conversations   id, announcement_id*, buyer_id*, seller_id*, last_message_at
messages        id, conversation_id*, sender_id*, body, is_read
```

---

## 🔒 Sécurité

- **Middlewares** : routes protégées via `auth` et `guest`
- **Policies** : `AnnouncementPolicy` – seul le propriétaire peut modifier/supprimer
- **Form Requests** : validation stricte sur toutes les entrées
- **CSRF** : protection sur tous les formulaires POST/PUT/DELETE
- **XSS** : échappement automatique via Blade `{{ }}`
- **Mass assignment** : `$fillable` défini sur tous les modèles

---

## 📁 Stack technique

| Couche | Technologie |
|---|---|
| Framework | Laravel 11 |
| Frontend | Blade + Bootstrap 5.3 + Bootstrap Icons |
| ORM | Eloquent |
| Base de données | MySQL 8 |
| Styles | CSS custom + Bootstrap utilities |
| JS | Vanilla JS (autocomplete, prévisualisation) |

---

## 🧪 Données de test (Seeders)

| Seeder | Contenu |
|---|---|
| `CitySeeder` | 20 villes marocaines |
| `CategorySeeder` | 8 catégories parentes + ~40 sous-catégories |
| `UserSeeder` | 1 compte admin + 30 utilisateurs aléatoires |
| `AnnouncementSeeder` | 20 annonces réalistes + 80 aléatoires |
| `ConversationSeeder` | 15 conversations avec échanges simulés |

---

## 📝 Standards de code

- **PSR-12** : formatage et style de code
- **Clean Code** : nommage explicite, méthodes courtes, responsabilité unique
- **Git** : commits sémantiques (`feat:`, `fix:`, `refactor:`, `docs:`)

---

## 🛣️ Routes principales

| Méthode | URI | Nom | Auth |
|---|---|---|---|
| GET | `/` | home | – |
| GET | `/search` | search | – |
| GET | `/annonces/{slug}` | announcements.show | – |
| GET | `/annonces/creer` | announcements.create | ✅ |
| POST | `/annonces` | announcements.store | ✅ |
| PUT | `/annonces/{slug}` | announcements.update | ✅ owner |
| DELETE | `/annonces/{slug}` | announcements.destroy | ✅ owner |
| GET | `/messages` | messages.index | ✅ |
| POST | `/messages` | messages.store | ✅ |
| GET | `/profil` | profile.show | ✅ |

---

*Projet réalisé dans le cadre d'un cours Laravel – Architecture MVC + Services + Policies*
