# Tests unitaires

- [Introduction](#introduction)
- [Définir et lancer des tests](#defining-and-running-tests)
- [Environnement de test](#test-environment)
- [Appeler des routes depuis les tests](#calling-routes-from-tests)
- [Méthodes "Helper"](#helper-methods)

<a name="introduction"></a>
## Introduction

Laravel donne une grande importance aux tests unitaires. En fait, le support pour PHPUnit est inclus nativement, et un fichier `phpunit.xml` est déjà configuré pour votre application. En plus de PHPUnit, Laravel utilise aussi les composants Symfony HttpKernel, DomCrawler, and BrowserKit pour vous permettre de simuler un navigateur Web afin d'inspecter et de manipuler vos vues pendant les tests.

Un fichier d'exemple est fourni dans le répertoire `app/tests`. Après avoir installé une nouvelle application Laravel, il vous suffit de lancer `phpunit` sur la ligne de commande pour faire tourner vos tests.

<a name="defining-and-running-tests"></a>
## Définir et lancer des tests

Pour créer un cas de test, il suffit de créer un nouveau fichier de test dans le répertoire `app/tests`. La classe du test doit étendre `TestCase`. Vous pouvez ensuite définir vos méthodes de test comme vous le faites normalement avec PHPUnit.

**Un exemple de classe de test**

	class FooTest extends TestCase {

		public function testSomethingIsTrue()
		{
			$this->assertTrue(true);
		}

	}

Vous pouvez lancer tous les tests de votre application en exécutant la commande `phpunit` depuis votre terminal.

> **Note:** Si vous définissez votre propre méthode `setUp`, n'oubliez pas d'appeler `parent::setUp`.

<a name="test-environment"></a>
## Environnement de test

Lorsque vous lancez des tests unitaires, Laravel configure automatiquement votre environnement à `testing`. Laravel inclut aussi par défaut les fichiers de configuration `session` et `cache` dans l'environnement de test. Ces deux drivers sont configurés sur `array` pendant les tests, ce qui veut dire qu'aucune donnée de session ou de cache ne sera persistée pendant les tests. Vous pouvez aussi créer d'autres environnements de test si nécessaire.

<a name="calling-routes-from-tests"></a>
## Appeler des routes depuis les tests

Vous pouvez facilement appeler une de vos routes depuis un test, en appelant la méthode `call`:

**Appeler une route depuis un test**

	$response = $this->call('GET', 'user/profile');

	$response = $this->call($method, $uri, $parameters, $files, $server, $content);

Vous pouvez ensuite inspecter l'objet `Illuminate\Http\Response`:

	$this->assertEquals('Hello World', $response->getContent());

Vous pouvez aussi appeler un controller depuis un test:

**Appeler un controlleur depuis un test**

	$response = $this->action('GET', 'HomeController@index');

	$response = $this->action('GET', 'UserController@profile', array('user' => 1));

La méthode `getContent` retournera le contenu de la réponse sous forme de chaîne de caractères. Si votre route retourne une `View`, vous pouvez y accéder à l'aide de la propriété `original` :

	$view = $response->original;

	$this->assertEquals('John', $view['name']);

### DOM Crawler

Vous pouvez aussi appeler une route et recevoir une instance de DOM Crawler que vous pouvez utiliser pour inspecter le contenu de la réponse :

	$crawler = $this->client->request('GET', '/');

	$this->assertTrue($this->client->getResponse()->isOk());

	$this->assertCount(1, $crawler->filter('h1:contains("Hello World!")'));

Pour plus d'information sur le fonctionnement du crawler, veuillez vous référer à la [documentation officielle](http://symfony.com/doc/master/components/dom_crawler.html).

<a name="helper-methods"></a>
## Méthodes "Helper"

La classe `TestCase` contient plusieurs méthodes "helper" pour vous permettre de tester votre application plus facilement.

Vous pourrez définir l'utilisateur courant en utilisant la méthode `be` :

**Définir l'utilisateur courant**

	$user = new User(array('name' => 'John'));

	$this->be($user);

Vous pourrez aussi re-charger la base de données depuis un test en utilisant la méthode `seed` :

**Re-charger la base de données depuis un test**

	$this->seed();

	$this->seed($connection);

Plus d'information sur les migrations et re-chargements dans la section [migrations and seeding](/docs/migrations#database-seeding) de la documentation.
