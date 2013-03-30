# Les requêtes et les entrées

- [Lecture des entrées utilisateur](#basic-input)
- [Utilisation de cookies](#cookies)
- [Les données anciennes](#old-input)
- [Utilisation de fichiers](#files)
- [Les éléments de requête](#request-information)

<a name="basic-input"></a>
## Lecture des entrées utilisateur

Les entrées utilisateurs sont accessibles facilement à l'aide de quelques méthodes. Ces méthodes sont disponibles et utilisables de la même manière quelque soit la commande HTTP.

**Lire la valeur d'une entrée**

	$name = Input::get('name');

**Retourner une valeur par défaut si une entrée n'a pas de valeur**

	$name = Input::get('name', 'Sally');

**Déterminer si une entrée possède une valeur**

	if (Input::has('name'))
	{
		//
	}

**Obtenir toutes les entrées de la requête**

	$input = Input::all();

**Obtenir certaines entrées de la requête**

	$input = Input::only('username', 'password');

	$input = Input::except('credit_card');

Certaines librairies Javascript comme Backbone peuvent transmettre à l'application les entrées au format JSON. Vous pouvez accéder à ces données via `Input::get()` comme d'habitude.

<a name="cookies"></a>
## Utilisation de cookies

Les cookies créés par Laravel sont cryptés et signés avec un code d'authentification. Par conséquent, les cookies sont considérés invalides dès lors qu'il sont modifiés par le client.

**Lire le contenu d'un cookie**

	$value = Cookie::get('name');

**Attacher un cookie à une réponse**

	$response = Response::make('Hello World');

	$response->withCookie(Cookie::make('name', 'value', $minutes));

**Créer un cookie permanent**

	$cookie = Cookie::forever('name', 'value');

<a name="old-input"></a>
## Les entrées anciennes

Supposons que vous devez conserver une entrée d'une requête à l'autre. Par exemple, vous devez réafficher un formulaire après sa validation.

**Enregistrer les entrées dans la session**

	Input::flash();

**Enregistrer certaines entrées dans la session**

	Input::flashOnly('username', 'email');

	Input::flashExcept('password');

Puisqu'il est souvent nécessaire de combiner l'enregistrement des entrées avec à la redirection vers la page précédente, il est possible d'enchaîner l'enregistrement des entrées avec la redirection.

	return Redirect::to('form')->withInput();

	return Redirect::to('form')->withInput(Input::except('password'));

> **Remarque:** Vous pouvez transmettre d'autres données à l'aide de la classe [Session](/docs/session).

**Lire une donnée ancienne**

	Input::old('username');

<a name="files"></a>
## Utilisation de fichiers

**Lire un fichier téléchargé**

	$file = Input::file('photo');

**Déterminer si un fichier est téléchargé**

	if (Input::hasFile('photo'))
	{
		//
	}

L'objet retourné par la méthode `file` est une instance de la classe `Symfony\Component\HttpFoundation\File\UploadedFile`. Cette classe est une extension de la classe PHP `SplFileInfo` fournissant un ensemble de méthodes permettant d'intéragir avec le fichier.

**Déplacer un ficher téléchargé**

	Input::file('photo')->move($destinationPath);

	Input::file('photo')->move($destinationPath, $fileName);

**Obtenir le chemin d'un ficher téléchargé**

	$path = Input::file('photo')->getRealPath();

**Obtenir la taille d'un ficher téléchargé**

	$size = Input::file('photo')->getSize();

**Obtenir le type MIME d'un ficher téléchargé**

	$mime = Input::file('photo')->getMimeType();

<a name="request-information"></a>
## Les éléments de requête

La classe `Request` fournit beaucoup de méthodes permettant d'examiner les éléments de requête HTTP. Cette classe est une extension de la classe `Symfony\Component\HttpFoundation\Request`. Voici quelques méthodes majeures.

**Obtenir l'URI d'une requête**

	$uri = Request::path();

**Déterminer si le chemin d'une requête respecte un motif**

	if (Request::is('admin/*'))
	{
		//
	}

**Obtenir l'URL d'une requête**

	$url = Request::url();

**Obtenir un des segments d'un URI**

	$segment = Request::segment(1);

**Obtenir l'entête d'une requête**

	$value = Request::header('Content-Type');

**Lire une valeur dans le tableau $_SERVER**

	$value = Request::server('PATH_INFO');

**Déterminer si une requête est de type AJAX**

	if (Request::ajax())
	{
		//
	}

**Déterminer si le protocole de la requête est HTTPS**

	if (Request::secure())
	{
		//
	}
