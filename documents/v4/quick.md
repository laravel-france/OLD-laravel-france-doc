# Démarage rapide avec Laravel

- [Installation](#installation)
- [Routage](#routing)
- [Création d'une vue](#creating-a-view)
- [Création d'une migration](#creating-a-migration)
- [L'ORM Eloquent](#eloquent-orm)
- [Affichage de données dans la vue](#displaying-data)

<a name="installation"></a>
## Installation

Pour installer le framework Laravel, téléchargez une copie [sur le dépôt Github](https://github.com/laravel/laravel/archive/master.zip). Ensuite, [installez composer](http://getcomposer.org), lancez la commande `composer install` depuis la racine du projet. Cette commande va télécharger et installer les dépendences du framework.

Après avoir installer le framework, After installing the framework, jetez un coup d'oeil autour du projet pour vous familiariser avec la structure des dossiers. Le dossier `app` contient des dossiers tels que `views`, `controllers` et `models`. La majorité du code de votre application va résider dans ce dossier. Vous pouvez également explorer le dossier `app/config` et les options de configurations qui s'offrent à vous.

<a name="routing"></a>
## Routage

Pour commencer, créons notre première route. Avec Laravel, la route la plus simple est la route vers une fonction anonyme. Ouvez le fichier `app/routes.php` et ajoutez la route suivante à la fin du fichier :

    Route::get('users', function()
    {
        return 'Users!';
    });

Maintenant, visitez la route `/users` dans votre navigateur, vous devriez voir `Users!` affiché en tant que réponse. Bien ! Vous venez de créer votre première route.

Les routes peuvent également être attachées à un contrôleur. Par exemple :

    Route::get('users', 'UserController@getIndex');

Cette route informe le framework que la requête  vers la route `/users` doit appeller la méthode `getIndex` de la classe `UserController`. Pour plus d'informations à propos du routage vers un contrôleur, lisez la [documentation sur les contrôleurs](/docs/v4/doc/controllers).

<a name="creating-a-view"></a>
## Création d'une vue

Ensuite, nous allons créer une simple vue pour afficher nos données utilisateur. Les vues résident dans le dossier `app/views` et contiennent le code HTML de notre application. Nous allons placer deux nouvelles vues dans ce dossier : `layout.blade.php` et `users.blade.php`. Premièrement, créons notre fichier `layout.blade.php` :

    <html>
        <body>
            <h1>Laravel Quickstart</h1>

            @yield('content')
        </body>
    </html>

Ensuite, créons notre vue `users.blade.php` :

    @extends('layout')

    @section('content')
        Users!
    @stop

Certaines parties de cette syntaxe doit vous sembler étrange. C'est parce que nous utilisons le système de templating de Laravel : Blade. Blade est très rapide, cas c'est simplement une poignée d'expressions régulières executées sur votre template pour le compiler en du PHP pur. Blade fournit des fonctionnalités puissantes comme l'héritage de template, ainsi qu'une syntaxe embellie sur les structures de contrôles de PHP tel que `if` et `for`. Lisez la [ocumentation de Blade](/docs/v4/doc/templates) pour plus de détail.

Maintenant que nous avons nos vues, retournons la depuis notre route `/users`. Plutôt que de retourner `Users!` depuis la route, retournez la vue :

    Route::get('users', function()
    {
        return View::make('users');
    });

Magnifique ! Maintenat vous avez mise en place une vue simple qui hérite d'un layout. Ensuite, travaillons sur la base de données.

<a name="creating-a-migration"></a>
## Création d'une migration

Pour créer une table qui garde nos données, nous allons utiliser la système de migration de Laravel. Les mirgations vous laisse définir des modifications sur votre base de données de manière expressive, et de les partager facilement avec le reste de votre équipe.

Premièrement, configurons une connexion à la base de données. Vous pouvez configurer vos connexions à la base données depuis le fichier `app/config/database.php`. Par défault, Laravel est configuré pour utiliser SQLite, et une base de données SQLite est inclue dans le dossier `app/database`. Si vous le souhaitez, vous pouvez changer l'option `driver` pour `mysql` et configurer la connexion `mysql` dans le fichier de configuration des bases de données.

Ensuite, pour créer une migration, nous allons utiliser [Artisan](/docs/v4/doc/artisan). Depuis la racine de votre projet, éxécutez la ligne suivante dans votre terminal :

    php artisan migrate:make create_users_table

Ensuitez, trouvez le fichier de migration généré dans le dossier `app/database/migrations`. Ce fichier contient une classe avec deux méthodes : `up` et `down`. Dans la méthode `up`, vous devez faire les changements désirés sur votre base de données, et dans la méthode `down`, vous faites de quoi supprimer vos modifications.

Définissons une migrations qui ressemble à cela :

    public function up()
    {
        Schema::create('users', function($table)
        {
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('users');
    }

Maintenant, nous pouvons éxécuter nos migrations depuis notre terminal en utilisant la commande `migrate`. Executez simplement cette commande depuis la racine de votre projet :

    php artisan migrate

Si vous souhaitez annuler une migration, vous pouvez utiliser la commande `migrate:rollback`. Maintenant que nous avons notre table en base de données, récupérons quelques données !

<a name="eloquent-orm"></a>
## L'ORM Eloquent

Laravel est founit avec un superbe ORM : Eloquent. Si vous avez utiliser le framework Ruby on Rails, vous allez trouvé Eloquent familier, car il suit la style d'ORM ActiveRecord pour les interactions avec la base de données.

Premièrement, définissons un modèle. Un modèle Eloquent peut être utilisé pour requêter une table associée dans la base de données, mais aussi pour représenter une ligne dans la table. Ne vous inquietez pas. Cela fera du sens très rapidement ! Les modèles sont généralements dans le dossier `app/models`. Définissons un modèle `User.php` dans ce dossier comme ceci :

    class User extends Eloquent {}

Notre que nous n'avons pas à indiquer à Eloquent quel table utiliser. Eloquent à une variété de conventions, une d'entre elle est d'utiliser le pluriel du nom du modèle en tant que nom de table. Pratique !

En utilisant votre outil d'administration de base de données préféré, inserez quelques lignes dans votre table `users`, et nous utiliserons Eloquent pour les récuperer et les passer à notre vue.

Maintenant, modifiez la route `/users` pour ressembler à ceci :

    Route::get('users', function()
    {
        $users = User::all();

        return View::make('users')->with('users', $users);
    });

Analysons dans le détail cette route. Premièrement, la méthode `all` sur le modèle `User` va retourner toutes les lignes de la table `users`. Ensuitez, nous passons nos enregistrements à la vue avec la méthode `with`. La méthode `with` accepte une clé et une valeur, et est utilisée pour rendre des données disponibles dans la vue.

Génial. Maintenant nous sommes prêt à afficher les utilisateurs dans notre vue !

<a name="displaying-data"></a>
## Affichage de données dans la vue

Maintenant que nous avons rendu les `users` disponibles pour notre vue, nous pouvons les afficher comme ceci :

    @extends('layout')

    @section('content')
        @foreach($users as $user)
            <p>{{ $user->name }}</p>
        @endforeach
    @stop

Vous devez vous demander où se trouvent la directive `echo`. Lorsque vous utilisez Blade, vous pouvez afficher des données en les entourants par des doubles accolades. Un vrai jeu d'enfant. Maintenant, vous devez être capable d'aller sur la route `/users` et de voir vos utilisateurs affichés dans la réponse.

Ce n'est que le début. Dans ce tutoriel, vous avez vu les bases de Laravel, mais il y a encore tellement de choses excitantes à apprendre ! Continuez lde lire la documentaiton et attaquer plus en profondeur les fonctionnalités puissantes offertes par [Eloquent](/docs/v4/doc/eloquent) et [Blade](/docs/v4/doc/templates). Ou, peut être vous serez plus intéréssé par les [queues](/docs/v4/doc/queues) et les [tests unitaires](/docs/v4/doc/testing). Et ensuite, peut être que vous souhaitez rendre plus souple votre architecture avec le [conteneur IoC](/docs/v4/doc/ioc). Le choix est votre !
