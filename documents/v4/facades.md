# Facades

- [Introduction](#introduction)
- [Explication](#explanation)
- [Cas pratique](#practical-usage)
- [Création de Facades](#creating-facades)
- [Mockage de Facades](#mocking-facades)

<a name="introduction"></a>
## Introduction

Les Facades sont des classes spéciales qui sont conçues pour simplifier votre code. Laravel est livré avec plusieurs Facades, et vous en avez probablement utilisé sans même le savoir. Lorsque vous developpez votre application ou vos packages, vous pourriez vouloir utiliser les facaces pour raccourcir votre code. Ici, nous allons couvrir le concept, le développement et l'utilisation des classes Facade.

> **Note:** Avant s'attaquer aux Facades, il est fortement recommandé d'être familiarisé avec le [conteneur IoC](/docs/v4/doc/ioc) de Laravel.

<a name="explanation"></a>
## Explication

Les Facades en général ne contiennent que deux méthodes, une méthode `getFacadeAccessor` et une méthode `__callStatic`. La méthode `getFacadeAccessor` retourne simplement une chaîne de caractères qui peut être utilisée pour résoudre une classe depuis le [conteneur IoC](/docs/v4/doc/ioc). Cette classe resolue en utilisant la clé retournée par `getFacadeAccessor` sera appellée par la méthode `__callStatic` lorsque qu'une méthode est appellée sur la Facade.

Donc, les Facades ne sont rien de plus qu'une manière de fournir une syntaxe plus courte pour appeller des classes disponibles dans le contenur IoC de l'application.

+<a name="practical-usage"></a>
+## Cas pratique

Dans l'exemple ci dessous, un appel est fait au système de cache de Laravel. Dans ce cas, on dirait que la méthode static `get` est appellée sur le classe `Cache`.

    $value = Cache::get('key');

Cependant, si vous regardons cette classe `Illuminate\Support\Facades\Cache`,

    class Cache extends Facade {

        /**
         * Get the registered name of the component.
         *
         * @return string
         */
        protected static function getFacadeAccessor() { return 'cache'; }

    }

Notez que la classe Facade `Cache` hérite de la classe Facade, et définie une méthode `getFacadeAccessor()`. Souvenez vous, le boulot de cette méthode est de retourner le nom d'une liaison IoC.

Lorsqu'un utilisateur fait un appel à une méthode static sur la classe `Cache`, Laravel résout cette liaison IoC depuis le conteneur et exécute la méthode désirée (dans ce cas, `get`) sur cet objet.

Donc, notre appel `Cache::get` pourrait être réécrit comme cela :

    $value = $app->make('cache')->get('key');

<a name="creating-facades"></a>
## Création de Facades

Créer une Facade pour votre application ou package est simple. Vous avez besoin de seulements 3 choses.

- Une liaison IoC.
- Une classe Facade.
- Un Alias de Facade dans la configuration.

Regardons un exemple. Ici nous avons une classe qui peut être référencée en tant que `\PaymentGateway\Payment`.

    namespace PaymentGateway;

    class Payment {

        public function process()
        {
            //
        }

    }

Une Facade pour cette classe pourrait ressembler à cela :

    use Illuminate\Support\Facades\Facade;

    class Payment extends Facade {

        protected static function getFacadeAccessor() { return 'payment'; }

    }

Finallement, nous ajoutons notre liaison IoC, qui dit à Laravel sur quel objet travailler en utilisant cette Facade.

    App::bind('payment', function()
    {
        return new \PaymentGateway\Payment;
    });

Un bon endroit pour enregistrer cette liaison peut être de créer un [fournisseur de service](/docs/v4/doc/ioc#service-providers) nommé `PaymentServiceProvider`. La laision sera ajouté dans la méthode `register()`. Vous pouvez configurer Laravel pour charger vos fournisseurs de services dans le fichier de configuration `app/config/app.php`.

Ensuite, si nous le souhaitons, nous pouvons ajouter un alias pour notre Facade dans le tableau `aliases` du fichier de configuration `app/config/app.php`. Maintenant, nous pouvons appeller la méthode `process` sur une instance de notre classe `Payment` avec :

    Payment::process();

<a name="mocking-facades"></a>
## Mockage de Facades

Les tests unitaires sont un aspect important de pourquoi les Facades marchent comme cela. En fait, la testabilité est la raison pour laquelle les Facades existent. Regardez la section [Mockage de Facades](/docs/v4/doc/testing#mocking-facades) de la documentation.
