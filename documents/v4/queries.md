# Query Builder

- [Introduction](#introduction)
- [Selection](#selects)
- [Jointure](#joins)
- [Clauses WHERE avancées](#advanced-wheres)
- [Aggrégats](#aggregates)
- [Expressions brutes](#raw-expressions)
- [Insertions](#inserts)
- [Mises à jour](#updates)
- [Suppressions](#deletes)

<a name="introduction"></a>
## Introduction

Le Query Builder (Constructeur de requête) de Laravel fournit une interface pratique et fluide pour créer et executer des requêtes pour vos bases de données. Il peut être utilisé avec la plupart des opérations sur votre base de données dans votre application, et fonctionne sur toutes les bases de données supportées.

> **Note:** Le Query builder de Laravel utilise la méthode de bind de PDO pour proteger votre application contre les injections SQL. Il n'y a donc pas besoin de faire des traitements sur les données avant de les passer au Query Builder.

<a name="selects"></a>
## Selection

**Récuperer toutes les lignes d'une table**

	$users = DB::table('users')->get();

	foreach ($users as $user)
	{
		var_dump($user->name);
	}

**Récuperer une ligne d'une table**

	$user = DB::table('users')->where('name', 'John')->first();

	var_dump($user->name);

**Retrouve une seule colonne d'une ligne**

	$name = DB::table('users')->where('name', 'John')->pluck('name');

**Spécification d'une clause SELECT**

	$users = DB::table('users')->select('name', 'email')->get();

	$users = DB::table('users')->distinct()->get();

	$users = DB::table('users')->select('name as user_name')->get();

**Ajout d'une clause SELECT à une requête existante**

    $query = DB::table('users')->select('name');

    $users = $query->addSelect('age')->get();

**Utilisation de la clause WHERE**

	$users = DB::table('users')->where('votes', '>', 100)->get();

**Utilisation de OR WHERE**

	$users = DB::table('users')
	                    ->where('votes', '>', 100)
	                    ->orWhere('name', 'John')
	                    ->get();

**Utilisation de WHERE BETWEEN**

	$users = DB::table('users')
	                    ->whereBetween('votes', array(1, 100))->get();

**Utilisation de WHERE IN avec un tableau**

	$users = DB::table('users')
	                    ->whereIn('id', array(1, 2, 3))->get();

	$users = DB::table('users')
	                    ->whereNotIn('id', array(1, 2, 3))->get();

**Utilisation de WHERE NULL pour trouver des valeurs non définies**

	$users = DB::table('users')
	                    ->whereNull('updated_at')->get();

**Utilisation de ORDER BY, GROUP BY, And HAVING**

	$users = DB::table('users')
	                    ->orderBy('name', 'desc')
	                    ->groupBy('count')
	                    ->having('count', '>', 100)
	                    ->get();

**Offset & Limite**

	$users = DB::table('users')->skip(10)->take(5)->get();

<a name="joins"></a>
## Jointure

Le Query Builder peut être utilisé pour faire des jointures. Regardez l'exemple suivant :

**Jointure basique**

	DB::table('users')
	            ->join('contacts', 'users.id', '=', 'contacts.user_id')
	            ->join('orders', 'users.id', '=', 'orders.user_id')
	            ->select('users.id', 'contacts.phone', 'orders.price');

Vous pouvez également spécifier des clauses de jointures plus complexes :

	DB::table('users')
	        ->join('contacts', function($join)
	        {
	        	$join->on('users.id', '=', 'contacts.user_id')->orOn(...);
	        })
	        ->get();

<a name="advanced-wheres"></a>
## Clauses WHERE avancées

Parfois vous pouvez avoir besoin de créer des clauses WHERE avancées comme des "WHERE EXISTS" ou des avec des clauses imbriquées. Le Query Builder le Laravel peut gférer cela également :

**Groupage de paramètre**

	DB::table('users')
	            ->where('name', '=', 'John')
	            ->orWhere(function($query)
	            {
	            	$query->where('votes', '>', 100)
	            	      ->where('title', '<>', 'Admin');
	            })
	            ->get();

La requête ci dessus éxecutera la requête suivante :

	select * from users where name = 'John' or (votes > 100 and title <> 'Admin')

**Clause EXISTS**

	DB::table('users')
	            ->whereExists(function($query)
	            {
	            	$query->select(DB::raw(1))
	            	      ->from('orders')
	            	      ->whereRaw('orders.user_id = users.id');
	            })
	            ->get();

La requête ci dessus éxecutera la requête suivante:

	select * from users
	where exists (
		select 1 from orders where orders.user_id = users.id
	)

<a name="aggregates"></a>
## Aggrégats

Le Query Builder fournit naturellement des méthodes d'aggrégats, tel que `count`, `max`, `min`, `avg`, et `sum`.

**Utilisation des méthodes d'aggrégats**

	$users = DB::table('users')->count();

	$price = DB::table('orders')->max('price');

	$price = DB::table('orders')->min('price');

	$price = DB::table('orders')->avg('price');

	$total = DB::table('users')->sum('votes');

<a name="raw-expressions"></a>
## Expressions brutes

Parfois vous aurez besoin d'utiliser des expressions brutes dans une requête. Ces expressions seront injectées dans la requête en tant que simple chaine, faites donc attention aux injections SQL! Pour créer une requête bruite, utilisez la méthode `DB::raw`:

**Utilisation d'une expression brute**

	$users = DB::table('users')
	                     ->select(DB::raw('count(*) as user_count, status'))
	                     ->where('status', '<>', 1)
	                     ->groupBy('status')
	                     ->get();

**Incrémente ou décrémente une valeur**

	DB::table('users')->increment('votes');

	DB::table('users')->decrement('votes');

<a name="inserts"></a>
## Insertions

**Insertion d'une ligne dans une table**

	DB::table('users')->insert(
		array('email' => 'john@example.com', 'votes' => 0),
	);

Si la table a un identifiant de type qui s'auto-incrémente, utilisez la méthode `insertGetId` pour insérer la ligne et récuperer son Id :

**Insertion d'une ligne dans une table et récupération de l'ID dans la ligne créée**

	$id = DB::table('users')->insertGetId(
		array('email' => 'john@example.com', 'votes' => 0),
	);

> **Note:** Si vous utilisez PostgreSQL, la méthode insertGetId assume que la colonne auto-incrémentée s'appelle "id".

**Insertion de plusieurs lignes**

	DB::table('users')->insert(array(
		array('email' => 'taylor@example.com', 'votes' => 0),
		array('email' => 'dayle@example.com', 'votes' => 0),
	));

<a name="updates"></a>
## Mises à jour

**Mise à jour d'une ligne dans une table**

	DB::table('users')
	            ->where('id', 1)
	            ->update(array('votes' => 1));

<a name="deletes"></a>
## Suppressions

**Suppression d'une ligne dans une table**

	DB::table('users')->where('votes', '<', 100)->delete();

**Suppression de toutes les lignes d'une table**

	DB::table('users')->delete();

**Tronque une table**

	DB::table('users')->truncate();
