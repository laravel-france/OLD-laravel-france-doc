# Configuration des sessions

## Au menu

- [Les bases](#the-basics)
- [Sessions en cookie](#cookie)
- [Sessions dans le système de fichier](#file)
- [Sessions en base de données](#database)
- [Sessions avec Memcached](#memcached)
- [Session avec Redis](#redis)
- [Sessions en mémoire](#memory)

<a name="the-basics"></a>
## Les bases

Le web est un environnement sans état. Cela signifie que chaque requête sur votre application est considérée comme n'ayant aucun lien avec aucune autre requête. Cependant, les **sessions** permettent de conserver des données arbitraires pour chaque visiteur de votre application, tandis qu'un **cookie** contenant votre **ID de session** est stocké sur la machine du client. Ce cookie permet à l'application de se souvenir de la session d'un utilisateur et de retrouver ses données d'un requête à l'autre.

> **Note:** Avant d'utiliser les sessions, soyez sur d'avoir configuré une clé d'application dans le fichier **application/config/application.php**.

Il existe six drivers fournis avec Laravel :

- Cookie
- Système de fichier
- Base de données
- Memcached
- Redis
- Mémoire (Tableau)

<a name="cookie"></a>
## Sessions en cookie

Les sessions en cookie fournissent un mechanisme de stockage de session simple et rapide. Ils sont sécurisés, car chaque cookie est chiffrer en utilisant AES-256. Le seul inconvéniant est que les cookie sont une capacité de stockage de qutre kilo octet, Si vous avez une grande quantité d'informations à stocker, vous devriez envisager une autre solution.

Pour utiliser les cookies, parametrez simplement le driver à utiliser dans le fichier **application/config/session.php** :

    'driver' => 'cookie'

<a name="file"></a>
## Sessions dans le système de fichier

Votre application tournera bien avec les cookies en fichier. Cependant, si votre application reçoit un lourd traffic ou tourne sur une batterie de serveurs, alors utilisez la base de donnée ou Memcached

Pour commencer à utiliser le système de fichier en tant que stockage de session, indiquez simplement le mot clé **file** dans l'option driver du fichier **application/config/session.php** :

    'driver' => 'file'

> **Note:** Les sessions en fichiers sont stockés dans le dossier **storage/sessions**, veuillez vous assurer qu'il est inscriptible.

<a name="database"></a>
## Sessions en base de données

Pour commencer, vous devrez [configurer votre connexion à la base de données](/docs/v3/doc/database/config).

Ensuite, vous devrez créer une table session. Vous trouverez ci dessous la commande Artisan pour générer la table, et en tant qu'alternative les requpetes SQL pour SQLite et MySQL. Nous vous recommandons bien entendu d'utilier Artisan pour générer les tables à votre place !

### Artisan

    php artisan session:table

### SQLite

    CREATE TABLE "sessions" (
         "id" VARCHAR PRIMARY KEY NOT NULL UNIQUE,
         "last_activity" INTEGER NOT NULL,
         "data" TEXT NOT NULL
    );

### MySQL

    CREATE TABLE `sessions` (
         `id` VARCHAR(40) NOT NULL,
         `last_activity` INT(10) NOT NULL,
         `data` TEXT NOT NULL,
         PRIMARY KEY (`id`)
    );

Si vous utilisez un nom de table différent, précisez le simplement dans l'option **table** du fichier **application/config/session.php** :

    'table' => 'sessions'

Dans ce même fichier, il ne vous reste qu'à passer l'option **driver** à **database** :

    'driver' => 'database'

<a name="memcached"></a>
## Sessions avec Memcached

Avant d'utiliser les sessions avec Memcached, vous devez [configurer votre serveur Memcached](/docs/v3/doc/database/config#memcached).

Réglez simplement le driver dans le fichier **application/config/session.php** :

    'driver' => 'memcached'

<a name="redis"></a>
## Sessions avec Redis

Avant d'utiliser les sessions avec Redis, vous devez [configurer votre serveur Redis](/docs/v3/doc/database/redis#config).

Réglez simplement le driver dans le fichier **application/config/session.php** :

    'driver' => 'redis'

<a name="memory"></a>
## Sessions en mémoire

Le driver de session "memory" utilise simplement un tableau en mémoire pour stocker votre données de sessions pour la requête courrante. Ce driver est parfait pour les tests unitaires, mais ne devrait jamais être utilisé en tant que vrai driver de session.