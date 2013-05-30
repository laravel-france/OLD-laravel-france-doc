# Les migrations & et la population

- [Introduction](#introduction)
- [Création de migrations](#creating-migrations)
- [Execution de migration](#running-migrations)
- [Annulation de migration](#rolling-back-migrations)
- [Population de base de donnée](#database-seeding)

<a name="introduction"></a>
## Introduction

Les migrations sont une sorte de contrôle de version pour votre base de données. Elles permettent de modifier le schema des tables et de rester à jour sur l'état courant du schema des tables. Les migrations sont souvent couplés avec le [Constructeur de Schéma](/docs/4/schema) pour gérer facilement les schemas de votre application.

<a name="creating-migrations"></a>
## Création de migrations

Pour créer une migration, vous pouvez utiliser la commande `migrate:make` d'Artisan en ligne de commande :

**Création d'une migration**

	php artisan migrate:make create_users_table

La migration sera placée dans votre dossier `app/database/migrations`, et contiendra un timestamp pour permettre au framework de déterminer l'ordre de vos migrations.

Vous pouvez également spécifier une option `--path` lorsque vous créez la migration. Le chemin doit être relatif à la racine de votre installation :

	php artisan migrate:make foo --path=app/migrations

Les otpions `--table` et `--create` peuvent être également utilisé pour indiquer le nom de la table, et si la migration va créer une nouvelle table :

	php artisan migrate:make create_users_table --table=users --create

<a name="running-migrations"></a>
## Execution de migration

**Execute toutes les migrations non lancées**

	php artisan migrate

**Execute toutes les migrations non lancées d'un chemin**

	php artisan migrate --path=app/foo/migrations

**Execute toutes les migrations non lancées d'un package**

	php artisan migrate --package=vendor/package

> **Note:** Si vous recevez une erreur "class not found" lors de l'execution des migrations, essayez de lancer la commande `composer update`.

<a name="rolling-back-migrations"></a>
## Annulation de migration

**Annule la dernière opération de migration**

	php artisan migrate:rollback

**Annule toutes les migrations**

	php artisan migrate:reset

**Annule toutes les migrations et les relances toutes**

	php artisan migrate:refresh

	php artisan migrate:refresh --seed

<a name="database-seeding"></a>
## Population de base de données

Laravel fournit également une manière simple de peupler votre base de données avec des données de tests en utilisant des classes de populations. Toutes les classes de populations sont stockées dans le dossier `app/database/seeds`. Les classes de populations peuvent avoir le nom que vous souhaitez, mais devrez probablement suivre une convention, tel que `UserTableSeeder`, etc. Par défaut, une classe `DatabaseSeeder` est définie pour vous. Depuis cette classe, vous pouvez utiliser la méthode `call` pour executer d'autres classes de population, vous permettant de contrôler l'ordre de de la population.

**Exemple de classe de population de base de donnée**

    class DatabaseSeeder extends Seeder {

        public function run()
        {
             $this->call('UserTableSeeder');

             $this->command->info('User table seeded!');
        }
     }

    class UserTableSeeder extends Seeder {

         public function run()
         {
             DB::table('users')->delete();

             User::create(array('email' => 'foo@bar.com'));
         }

    }

Pour peupler votre base de données, vous pouvez utiliser la commande `db:seed` avec Artisan en ligne de commande :

	php artisan db:seed

Vous pouvez également peupler votre base de données en utilisant la commande `migrate:refresh`, qui va également annuler les migrations et les relancer :

	php artisan migrate:refresh --seed
