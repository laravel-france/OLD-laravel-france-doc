# Génération d'URLs

## Au menu

- [Les bases](#the-basics)
- [URLs vers une route](#urls-to-routes)
- [URLs vers une action de contrôleur](#urls-to-controller-actions)
- [URLs vers un asset](#urls-to-assets)
- [Helpers d'URL](#url-helpers)

<a name="the-basics"></a>
## Les bases

#### Retourne l'URL de l'application :

	$url = URL::base();

#### Retourne une URL relative à la base de l'application :

	$url = URL::to('user/profile');

#### Génére une URL HTTPS :

	$url = URL::to_secure('user/login');

#### Retourne l'URL courante :

	$url = URL::current();

#### Retourne l'URL courante, avec les query string:

	$url = URL::full();

<a name="urls-to-routes"></a>
## URLs vers une route

#### Génération d'une URL vers une route nommée :

	$url = URL::to_route('profile');

Vous devrez parfois fournir à une route des arguments, pour ce faire, passez les en tant que tableau en second argument :
#### Génére une URL vers une route nommée avec des arguments :

	$url = URL::to_route('profile', array($username));

*Voir aussi:*

- [Route nommées](/guides/doc/v3/routes#named-routes)

<a name="urls-to-controller-actions"></a>
## URLs vers une action de contrôleur

#### Genere une URL vers une action de contrôleur :

	$url = URL::to_action('user@profile');

#### Genere une URL vers une action de contrôleur avec des paramètres :

	$url = URL::to_action('user@profile', array($username));

<a name="urls-to-a-different-language"></a>
## URLs vers un langage différent

#### Génère une url vers la même page dans un langage différent :

    $url = URL::to_language('fr');

#### Génère une url vers la page d'accueil dans un langage différent:

    $url = URL::to_language('fr', true);

<a name="urls-to-assets"></a>
## URLs vers des assets

Les URLs générés generated for assets will not contain the "application.index" configuration option.

#### Generating a URL to an asset:

	$url = URL::to_asset('js/jquery.js');

<a name="url-helpers"></a>
## URL Helpers

There are several global functions for generating URLs designed to make your life easier and your code cleaner:

#### Generating a URL relative to the base URL:

	$url = url('user/profile');

#### Generating a URL to an asset:

	$url = asset('js/jquery.js');

#### Generating a URL to a named route:

	$url = route('profile');

#### Generating a URL to a named route with wildcard values:

	$url = route('profile', array($username));

#### Generating a URL to a controller action:

	$url = action('user@profile');

#### Generating a URL to an action with wildcard values:

	$url = action('user@profile', array($username));