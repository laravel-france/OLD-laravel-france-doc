# La pagination

- [Configuration](#configuration)
- [Utilisation](#usage)

<a name="configuration"></a>
## Configuration

Dans d'autres frameworks, mettre en place une pagination peut être difficile. Laravel facilite cette tâche. Il suffit de valoriser l'option `pagination` du fichier `app/config/view.php` avec la vue à utiliser pour créer des liens de pagination. Par défaut, Laravel inclut deux vues.

La vue `pagination::slider` a pour fonction de produire une restitution élaborée d'une série de liens sur la page courante tandis que la vue `pagination::simple` affiche les boutons "précédent" et "suivant". **Ces deux vues sont compatibles avec Twitter Bootstrap.**

<a name="usage"></a>
## Utilisation

Il existe plusieurs manières de paginer des éléments. La plus simple est d'utiliser la méthode `paginate` du requêteur ou d'un modèle Eloquent.

**Paginer le résultat d'une requête de base de données**

	$users = DB::table('users')->paginate(15);

Vous pouvez aussi paginer un modèle [Eloquent](/docs/eloquent) :

**Paginer un modèle Eloquent**

	$users = User::where('votes', '>', 100)->paginate(15);

L'argument transmis à la méthode `paginate` est le nombre d'éléments à afficher par page. Vous pouvez placer les éléments à afficher dans une vue et créer les liens de pagination à l'aide de la méthode `links` : 

	<div class="container">
		<?php foreach ($users as $user): ?>
			<?php echo $user->name; ?>
		<?php endforeach; ?>
	</div>

	<?php echo $users->links(); ?>

Créer un système de pagination est aussi simple que cela ! Notez qu'il n'est pas nécessaire d'indiquer l'identité de la page courante au framework. Laravel le détermine automatiquement.

Vous pouvez également accéder à des informations addtionelles sur la pagination en utilisation les méthodes suivantes :

- `getCurrentPage`
- `getLastPage`
- `getPerPage`
- `getTotal`

Si vous souhaitez créer une pagination manuellement en fournissant un tableau d'éléments, utilisez la méthode `Paginator::make` :

**Créer manuellement une pagination**

	$paginator = Paginator::make($items, $totalItems, $perPage);
