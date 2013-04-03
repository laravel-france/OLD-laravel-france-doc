# Les événements

- [Utilisation basique](#basic-usage)
- [Utilisation de classes en tant qu'écouteur](#using-classes-as-listeners)
- [Mise en attente d'un événement](#queued-events)
- [Classes d'abonnements](#event-subscribers)

<a name="basic-usage"></a>
## Utilisation basique

La classe `Event` du framework Laravel vous permet de souscrire et d'écouter des évenements dans votre applications.

**Enregistrement à un événement**

    Event::listen('user.login', function($user)
    {
        $user->last_login = new DateTime;

        $user->save();
    });

**Déclencher un événement**

    $event = Event::fire('user.login', array($user));

Vous pouvez spécifier une priorité pour vos écouteurs d'événements. Les écouteurs ayants une plus grande priorité seront éxécutés en premier. tandis que les écouteurs qui ont la même priorité seront executés dans leur ordre d'enregistrement.

**Enregistrement à un événement avec priorité**

    Event::listen('user.login', 'LoginHandler', 10);

    Event::listen('user.login', 'OtherHandler', 5);

Vous pouvez stopper la propagation d'un évenement aux autres, en retournant 'false' depuis l'écouteur :

**Stop la propagation d'un événement**

    Event::listen('user.login', function($event)
    {
        // Handle the event...

        return false;
    });

<a name="using-classes-as-listeners"></a>
## Utilisation de classes en tant qu'écouteur

Dans certains cas, vous pourriez vouloir utiliser une classe pour gérer un événement plutôt qu'une fonction anonyme. Les événements de classes sont résolus grâce au [conteneur IoC de Laravel](/docs/v4/doc/ioc), vous fournissant ainsi la puissance de l'injecteur de dépendance à votre classe.

**Enregistrement d'une classe écouteur**

    Event::listen('user.login', 'LoginHandler');

Par défaut, la méthode `handle` de la classe `LoginHandler` sera appellée:

**Définition d'une classe écouteur d'événement**

    class LoginHandler {

        public function handle($data)
        {
            //
        }

    }

Si vous ne souhaitez pas utiliser la méthode par défaut `handle`, vous pouvez préciser le nom d'une méthode que vous souhaitez utiliser:

**Spécifie quelle méthode doit être utilisée**

    Event::listen('user.login', 'LoginHandler@onLogin');

<a name="queued-events"></a>
## Événements en file d'attente

En utilisant les méthodes `queue` et `flush`, vous pouvez mettre en attente un événement à déclarer, mais sans le lancer tout de suite :

**Enregistrement d'un événement dans la file d'attente**

    Event::queue('foo', array($user));

**Enregistrement d'un videur**

    Event::flusher('foo', function($user)
    {
        //
    });

Finalement, vous pouvez executer le "videur" et vider tous les événements en attente avec la méthode `flush` :

  Event::flush('foo');

<a name="event-subscribers"></a>
## Classes d'abonnements

Les classes d'abonnements sont des classes qui peuvent souscrire à plusieurs événements, enregistrés au seins même de ces classe. Ces classes doivent définir une méthode `subscribe`, qui reçoit en unique argument une instance du répartiteur d'événement:

**Définition d'une classe d'abonnements**

    class UserEventHandler {

        /**
         * Handle user login events.
         */
        public function onUserLogin($event)
        {
            //
        }

        /**
         * Handle user logout events.
         */
        public function onUserLogout($event)
        {
            //
        }

        /**
         * Register the listeners for the subscriber.
         *
         * @param  Illuminate\Events\Dispatcher  $events
         * @return array
         */
        public static function subscribe($events)
        {
            $events->listen('user.login', 'UserEventHandler@onUserLogin');

            $events->listen('user.logout', 'UserEventHandler@onUserLogout');
        }

    }

Une fois que la classe a été définie, elle doit être enregistrée avec la classe `Event`.

**Enregistrement d'une classe d'abonnements**

    $subscriber = new UserEventHandler;

    Event::subscribe($subscriber);
