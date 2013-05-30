# Facades

- [Introduction](#introduction)
- [Explication](#explanation)
- [Cas pratique](#practical-usage)
- [Création de Facades](#creating-facades)
- [Mockage de Facades](#mocking-facades)

<a name="introduction"></a>
## Introduction

Les Facades fournissent une interface "static" vers des classes qui sont accessibles dans le [conteneur IoC](/docs/v4/doc/ioc). Laravel est livré avec plusieurs Facades et vous en avez probablement utilisé sans même le savoir !

Occasionnellement, vous pourriez souhaiter créer vos propres facades pour vos applications et vos pacakges, donc voysons le concept, le développement et l'utilisation de ces classes.

> **Note:** Avant s'attaquer aux Facades, il est fortement recommandé d'être familiarisé avec le [conteneur IoC](/docs/v4/doc/ioc) de Laravel.

<a name="explanation"></a>
## Explication

Dans le contexte d'une application Laravel, une facade est une classe qui fournit un accès à un object depuis le conteneur. Le mecanisme qui fait marcher tout cela se trouve dans le classe `Facade`. Les facades de Laravel et n'importe quelle façade que vous souhaitez créer, devra hériter de la classe `Facade`.

Vos classes facades doivent uniquement contenir une méthode, `getFacadeAccessor`. C'est la mission de la méthode `getFacadeAccessor` de définir ce qui doit être résolu depuis le conteneur. La classe de base `Facade` fait appel à la méthode magique `__callStatic` pour transmettre les appels de votre facade vers l'objet résolu.

<a name="practical-usage"></a>
## Cas pratique

Dans l'exemple ci dessous, un appel est fait au système de cache de Laravel. En jetant un oeil à ce code, quelqu'un pourrait dire que la méthode static `get` est appellée sur le classe `Cache`.

    $value = Cache::get('key');

Cependant, si vous regardons cette classe `Illuminate\Support\Facades\Cache`, vous verrez qu'il n'y a pas de méthode statique `get` :

    class Cache extends Facade {

        /**
         * Get the registered name of the component.
         *
         * @return string
         */
        protected static function getFacadeAccessor() { return 'cache'; }

    }

La classe `Cache` hérite de la classe de base `Facade`, et définie une méthode `getFacadeAccessor()`. Souvenez vous, le boulot de cette méthode est de retourner le nom d'une liaison IoC.

Lorsqu'un utilisateur fait un appel à une méthode static sur la classe `Cache`, Laravel résout le liaison `cache` depuis le conteneur et exécute la méthode désirée (dans ce cas, `get`) sur cet objet.

Donc, notre appel `Cache::get` pourrait être réécrit comme cela :

    $value = $app->make('cache')->get('key');

<a name="creating-facades"></a>
## Création de Facades

Créer une facade pour votre application ou package est simple. Vous avez besoin de seulements 3 choses.

- Une liaison IoC.
- Une classe Facade.
- Un Alias de Facade dans la configuration.

Regardons un exemple. Ici nous avons une classe qui définie en tant que `\PaymentGateway\Payment`.

    namespace PaymentGateway;

    class Payment {

        public function process()
        {
            //
        }

    }

Nous devons être capable de résoudre cette classe depuis le conteneur IoC. Alors, ajoutons une liaison :

    App::bind('payment', function()
    {
        return new \PaymentGateway\Payment;
    });

Un bon endroit pour enregistrer cette liaison peut être de créer un [fournisseur de service](/docs/v4/doc/ioc#service-providers) nommé `PaymentServiceProvider`. La laision sera ajouté dans la méthode `register()`. Vous pouvez configurer Laravel pour charger vos fournisseurs de services dans le fichier de configuration `app/config/app.php`.

Ensuite, nous pouvons créer notre propre classe façade :

    use Illuminate\Support\Facades\Facade;

    class Payment extends Facade {

        protected static function getFacadeAccessor() { return 'payment'; }

    }

Finalement, si nous le souhaitons, nous pouvons ajouter un alias pour notre Facade dans le tableau `aliases` du fichier de configuration `app/config/app.php`. Maintenant, nous pouvons appeller la méthode `process` sur une instance de notre classe `Payment` :

    Payment::process();

<a name="mocking-facades"></a>
## Mockage de Facades

Les tests unitaires sont un aspect important de pourquoi les facades marchent comme cela. En fait, la testabilité est la raison pour laquelle les Facades existent. Regardez la section [Mockage de Facades](/docs/v4/doc/testing#mocking-facades) de la documentation.
