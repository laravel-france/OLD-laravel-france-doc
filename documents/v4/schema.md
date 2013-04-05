# Le constructeur de schéma

- [Introduction](#introduction)
- [Création et suppression de table](#creating-and-dropping-tables)
- [Ajout de colonnes](#adding-columns)
- [Suppression de colonnes](#dropping-columns)
- [Ajout d'index](#adding-indexes)
- [Suppression d'index](#dropping-indexes)

<a name="introduction"></a>
## Introduction

La classe `Schema` de Laravel fournit une manière indépendante du type de base de données pour manipuler les tables. Le constructeur marche bien avec toutes les bases de données supportées par Laravel, et a une API unifiée pour tous ces systèmes.

<a name="creating-and-dropping-tables"></a>
## Création et suppression de table

Pour créer une nouvelle table, la méthode `Schema::create` est utilisée :

    Schema::create('users', function($table)
    {
        $table->increments('id');
    });

Le premier argument passé à la méthode `create` est le nom de la tabnle, et le second argument est une fonction anonyme, qui va recevoir un objet `Blueprint` qui doit être utilisé pour définir la nouvelle table.

Pour spécifier quelle connexion doit être utilisée par le constructeur de schéma, utilisez la méthode `Schema::connection` :

    Schema::connection('foo')->create('users', function($table)
    {
        $table->increments('id'):
    });

Pour supprimer une table, vous pouvez utiliser la méthode `Schema::drop` :

    Schema::drop('users');

    Schema::dropIfExists('users');

<a name="adding-columns"></a>
## Ajout de colonnes

Pour éditer une table existante, nous utiliserons la méthode `Schema::table` :

    Schema::table('users', function($table)
    {
        $table->string('email');
    });

Le constructeur de table contient une variété de type de colonne que vous pouvez utiliser pour construire vos tables :

Commande  | Description
------------- | -------------
`$table->increments('id');`  |  Clé primaire de type auto-incrémentale.
`$table->string('email');`  |  Equivalent de VARCHAR
`$table->string('name', 100);`  |  Equivalent de VARCHAR avec une taille
`$table->integer('votes');`  |  Equivalent d'INTEGER
`$table->float('amount');`  |  Equivalent de FLOAT
`$table->decimal('amount', 5, 2);`  |  Equivalent de DECIMAL avec une précision et une echelle
`$table->boolean('confirmed');`  |  Equivalent de BOOLEAN
`$table->date('created_at');`  |  Equivalent de DATE
`$table->dateTime('created_at');`  |  Equivalent de DATETIME
`$table->time('sunrise');`  |  Equivalent de TIME
`$table->timestamp('added_on');`  |  Equivalent de TIMESTAMP
`$table->timestamps();`  |  Ajoute les colonnes **created\_at** et **updated\_at**
`$table->text('description');`  |  Equivalent de TEXT
`$table->binary('data');`  |  Equivalent de BLOB
`$table->enum('choices', array('foo', 'bar'));` | Equivalent de ENUM
`->nullable()`  |  Designe une colonne qui autorise NULL
`->default($value)`  |  Déclare une valeur par défaut pour la colonne
`->unsigned()`  |  Défini un INTEGER comme étant UNSIGNED

Si vous utilisez une base de données MySQL, vous pouvez utiliser la méthode `after` pour spécifier l'ordre des colonnes :

**Utilisation de after sur MySQL**

    $table->string('name')->after('email');

<a name="dropping-columns"></a>
## Suppression de colonnes

**Suppression d'une colonne d'une table**

    Schema::table('users', function($table)
    {
        $table->dropColumn('votes');
    });

**Suppression de plusieurs colonnes d'une table**

    Schema::table('users', function($table)
    {
        $table->dropColumns('votes', 'avatar', 'location');
    });

<a name="adding-indexes"></a>
## Ajout d'index

Le constructeur de schema supporte plusieurs types d'indices. Il y a deux manières de les ajouter. La première est de manière fluide, lors de la définition d'une colonne :

**Crée de manière fluide une colonne et un index**

    $table->string('email')->unique();

Ou, vous pouvez choisir d'ajouter les indices sur des lignes séparés. Vous trouverez ci-dessous une liste des types d'index:

Commande  | Description
------------- | -------------
`$table->primary('id');`  |  Ajout d'une clé primaire
`$table->primary(array('first', 'last'));`  |  Ajout d'une clé primaire composite
`$table->unique('email');`  |  Ajout d'un index d'unicité
`$table->index('state');`  |  Ajout d'un index basique

<a name="dropping-indexes"></a>
## Suppression d'index

Pour supprimer un index, vous devez spécifier le nom de l'index. Laravel assigne un nom raisonnable aux indices par défaut. Concatenez simplement le nom de la table, le nom des colonnes dans l'index, et le type d'index. Voici quelques exemples :

Command  | Description
------------- | -------------
`$table->dropPrimary('users_id_primary');`  |  Supprime une clé primaire de la table "users"
`$table->dropUnique('users_email_unique');`  |  Supprime une clé d'unicité de la table "users"
`$table->dropIndex('geo_state_index');`  |  Supprime une clé basique de la table "geo"
