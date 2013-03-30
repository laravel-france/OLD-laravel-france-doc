# Sécurité

- [Configuration](#configuration)
- [Stockage de mot de passe](#storing-passwords)
- [Identifier les utilisateurs](#authenticating-users)
- [Protection de routes](#protecting-routes)
- [Réinitialisation du mot de passe](#password-reminders-and-reset)
- [Chiffrage](#encryption)

<a name="configuration"></a>
## Configuration

Laravel cherche à rendre l'authentification très simple. En fait, presque tout est déjà configuré pour vous dès le début. Le fichier de configuration de l'authentification se situe dans  `app/config/auth.php`, il contient plusieurs options bien documentées pour personnaliser le comporter de la solution d'authentification.

Par défaut, Laravel inclus un modèle `User` dans votre dossier `app/models` qui peut être utilisé avec le driver par défaut : Eloquent. Souvenez vous lorsque vous construisez la table pour ce modèle que le champs mot de passe doit faire au minimum 60 caractères.

Si votre application n'utilise pas, vous pouvez utiliser le driver d'authentification `database` qui utilise le constructeur de requête Laravel.

<a name="storing-passwords"></a>
## Stockage de mot de passe

La classe Laravel `Hash` fournit un cryptage sécurisé Bcrypt :

**Cryptage d'un mot de passe en utilisant Bcrypt**

	$password = Hash::make('secret');

**Vérification d'un mot de passe contre son équivalent crypté**

	if (Hash::check('secret', $hashedPassword))
	{
		// The passwords match...
	}

<a name="authenticating-users"></a>
## Identifier les utilisateurs

Pour connecter un utilisateur dans votre application, vous devez utiliser la méthode `Auth::attempt`.

	if (Auth::attempt(array('email' => $email, 'password' => $password)))
	{
		// The user's credentials are valid...
	}

Notez que `email` n'est pas requis, il est utilisé simplement en tant qu'exemple. Vous devez utiliser la colonne qui correspond à votre "nom d'utilisateur" dans votre base de donnnées.

Si vous souhaitez fournir la fonctionnalité "Se souvenir de moi" dans votre application, vous devez passer `true` en tant que second argument à la méthode `attempt`, cela gardera l'utilisateur connécté indéfiniement (ou jusqu'à ce qu'il se déconnecte) :

**Identifier un utiliser et se souvenir de lui**

	if (Auth::attempt(array('email' => $email, 'password' => $password), true))
	{
		// The user is being remembered...
	}

**Note:** Si la méthode `attempt` retourne `true`, alors l'utilisateur est connécté à votre application.

**Connecter un utilisateur avec des conditions**

Vous pouvez ajouter des conditions particulières pour vous assurer qu'un utiliseur est par exemple actif, et non suspendu :

    if (Auth::attempt(array('email' => $email, 'password' => $password, 'active' => 1, 'suspended' => 0)))
    {
        // The user is active, not suspended, and exists.
    }

Une fois qu'un utilisateur est connécté, vous pouvez accéder à son modèle/enregistrement :

**Accès à l'utilisateur connécté**

	$email = Auth::user()->email;

Pour connecter simplement un utilisateur dans votre application en utilisant son Id, utilisez la méthode `loginUsingId` :

  Auth::loginUsingId(1);

La méthode `validate` vous permet de valider que les identifiants d'un utilisateur sont corrects sans le connecter à l'application :

**Valide les identifiants d'un utilisateur sans le connecter**

	if (Auth::validate($credentials))
	{
		//
	}

Vous pouvez également utiliser la méthode `stateless` pour connecter un utilisateur le temps d'une seule requête. Il n'y aura ni session ni cookie pour cet utilisateur.

**Connecte un utilisateur pour une seule requête**

	if (Auth::stateless($credentials))
	{
		//
	}

**Déconnecte un utilisateur**

	Auth::logout();

<a name="protecting-routes"></a>
## Protection de routes

Les filtres de routes peuvent être utilisés pour autoriser uniquement les utilisateurs connéctés à acceder à certaines routes. Laravel fournit un filtre `auth` par défaut, qui se situe dans le fichier `app/filters.php`.

**Protection d'une route**

	Route::get('profile', array('before' => 'auth', function()
	{
		// Only authenticated users may enter...
	}));

### Protection CSRF

Laravel fournit une méthode simple de proteger votre application contre les attaques de type [CSRF](http://fr.wikipedia.org/wiki/Cross-site_request_forgery).

**Insertion du jeton CSRF dans votre formulaire** en utilisant `csrf_token()` ou `Session::getToken()`

    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">

**Validation du jeton CSRF envoyé**

    Route::post('register', array('before' => 'csrf', function()
    {
        return 'You gave a valid CSRF token!';
    }));

<a name="password-reminders-and-reset"></a>
## Réinitialisation du mot de passe

### Réinitialiser un mot de passe

La plupart des sites fournissent la possibilité à l'utilisateur de réinitialiser son mot de passe. Plutôt que de vous forcer à réimplémenter cela sur chaque application, Laravel fournit des méthodes pratiques pour envoyer un rappel de mot de passe ou réinitialiser ce dernier. Pour commencer, vérifiez que votre modèle `User` implémente l'interface `Illuminate\Auth\RemindableInterface`. Bien sur, le modèle par défaut `User` inclut dans le framework l'implémente déjà.

**Implémentation de l'interface RemindableInterface**

	class User extends Eloquent implements RemindableInterface {

		public function getReminderEmail()
		{
			return $this->email;
		}

	}

Ensuite, une table doit être créée pour stocker le jeton de réinitialisation du mot de passe. Pour généré une migration pour cette table, executez simplement la commande artisan `auth:reminders` :

**Génération de la migration pour la table de rappel**

	php artisan auth:reminders

	php artisan migrate

Pour envoyer un rappel de mot de passe, nous pouvons utiliser la méthode `Password::remind` :

**Envoi d'un rappel de mot de passe**

	Route::post('password/remind', function()
	{
		$credentials = array('email' => Input::get('email'));

		return Password::remind($credentials);
	});

Notez que les arguments passés à la méthode `remind` ressemblent à ceux de la méthode `Auth::attempt`. Cette méthode va retrouver un `User` et lui envoyé un un lien de réinitialisation de mot de passe par email. L'email contiendra un jeton `token` qui sera utilisé pour construire le lien vers le formulaire de réinitialisation du mot de passe.

> **Note:** Vous pouvez spécifié la vue qui sera utilisée dans l'email en changeant l'option de configuration `auth.reminder.email`. Bien entendu, une vue par défaut vous est fournie.

Vous pouvez modifier l'instance du message qui va être envoyé en passant une fonction anonyme en tant que second argument de la méthode `remind` :

	return Password::remind($credentials, function($m)
	{
		$m->subject('Your Password Reminder');
	});

Vous pouvez également remarqué que nous avons retourné le résultat de la méthode `remind` directement depuis une route. Par défaut, la méthode `remind` retournera un `Redirect` vers l'URI courante. Si une erreur se produit durant l'essai de réinitialisation du mot de passe, une variable `error` sera mise en session pour la requête suivante uniquement, ainsi qu'une variable `reason`, qui peut être utilisée pour extraire une ligne de langue depuis la fichier de langue `reminders`. Donc, votre formulaire de réinitialisation de mot de passe peut ressembler à cela s:

	@if (Session::has('error'))
		{{ trans(Session::get('reason')) }}
	@endif

	<input type="text" name="email">
	<input type="submit" value="Send Reminder">

### Réinitialisation du mot de passe

Une fois qu'un utilisateur à cliqué sur le lien de réinitialisation de l'email de rappel, ils sont redirigé vers un formulaire qui inclut un champ caché `token`, ainsi que les champs `password` et `password_confirmation`. Vous trouverez ci dessous un exemple de route pour un formulaire de réinitialisation de mot de passe :

	Route::get('password/reset/{token}', function($token)
	{
		return View::make('auth.reset')->with('token', $token);
	});

Et, un formulaire de réinitialisation peut ressembler à cela :

	@if (Session::has('error'))
		{{ trans(Session::get('reason')) }}
	@endif

	<input type="hidden" name="token" value="{{ $token }}">
	<input type="text" name="email">
	<input type="password" name="password">
	<input type="password" name="password_confirmation">

Une fois de plus, remarquez que nous utilisons `Session` pour afficher les erreurs qui pourraient être détéctées par le framework lors de la procédure de réinitialisation du mot de passe. Ensuite, nous pouvons définir une route `POST` pour gérer la réinitialisation :

	Route::post('password/reset/{token}', function()
	{
		$credentials = array('email' => Input::get('email'));

		return Password::reset($credentials, function($user, $password)
		{
			$user->password = Hash::make($password);

			$user->save();

			return Redirect::to('home');
		});
	});

Si le password est correctement réinitialisé, une instance de `User` et le mot de passe vous serons fournis dans la fonction anonyme, vous permettant d'effectuer la sauvegarde. Ensuite, vous pouvez retourner un `Redirect` ou ce que vous souhaitez, le contenu sera renvoyé par la méthode `reset`. Notez que la méthode `reset` vérifiera automatiquement pour un jeton valide dans la requête, un utilisateur valide, ainsi que des mots de passes identiques.

Pour finir, de la même manière que la méthode `remind`, si une erreur se produit, la méthode `reset` retournera un `Redirect` vers l'URI en cours avec les variables `error` et `reason`.

<a name="encryption"></a>
## Chiffrage

Laravel fournit une solution pour du chiffrage fort AES-256 avec l'extension PHP mcrypt:

**Chiffrage d'une valeur**

	$encrypted = Crypt::encrypt('secret');

> **Note:** Veuillez vous assurer d'avoir défini une clé de 32 caractères aléatoires dans l'option `key` du fichier de configuration `app/config/app.php`. Sans cela, la chiffrage ne sera pas assez fort.

**Déchiffrage d'une valeur**

	$decrypted = Crypt::decrypt($encryptedValue);
