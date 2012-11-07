# Requêtes

## Au menu

- [Travail avec les URI](#working-with-the-uri)
- [Autres helpers de requpetes](#other-request-helpers)

<a name="working-with-the-uri"></a>
## Travail avec les URI

#### Obtient l'URI courrant de la requête :

	echo URI::current();

#### Obtient une segment spéficique de l'URI :

	echo URI::segment(1);

#### Retourne une valeur pas défaut si ne segment n'existe pas :

	echo URI::segment(10, 'Foo');

#### Obtient l'URL complète, y compris la query string :

	echo URI::full();

Si vous souhaitez déterminé si l'URI de la requête est une chaine de caractères donnée ou commence par une chaine de caractère donnée, alors vous pouvez utiliser la méthode **is** pour faire cela :

#### Determine si l'URI est "home" :

	if (URI::is('home'))
	{
		// The current URI is "home"!
	}

#### Determine si l'URI commence par "guides/v3/" :

	if URI::is('guides/v3/*'))
	{
		// The current URI begins with "guides/v3/"!
	}

<a name="other-request-helpers"></a>
## Autres helpers de requpetes

#### Retourne le verbe HTTP utilisé (GET, POST, ...)

	echo Request::method();

#### accès au tableau globale $_SERVER :

	echo Request::server('http_referer');

#### Retourne l'adresse IP du client :

	echo Request::ip();

#### Determine si la requête utilise HTTPS :

	if (Request::secure())
	{
		// This request is over HTTPS!
	}

#### Determine si la requête courante est une requête AJAX :

	if (Request::ajax())
	{
		// This request is using AJAX!
	}

#### Determine si la requête courrant se fait via Artisan :

	if (Request::cli())
	{
		// This request came from the CLI!
	}