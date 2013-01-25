# Profiler

## Au menu
- [Autoriser le Proiler](#enable)
- [Logging vers le Proiler](#logging)
- [Timers et Benchmarking](#timers)

<a name="enable"></a>
## Autoriser le Proiler

Pour autoriser le profiler, vous devez éditer **application/config/application.php** et mettre l'option profiler **true**.

    'profiler' => true,

Cela attachera le profiler à **toutes** les réponses de votre installation Laravel.

**Note:** Notez qu'un problème peut se présenter si vous génerez des fichiers JSON. En effet, le profiler viendra s'introduire dans votre flux de sortie et votre fichier JSON sera alors invalide.

<a name="logging"></a>
## Logging vers le Proiler

Quand le profiler est activé, alors les messages que vous logguez seront affichier dans la partie dédié au log de ce dernier. 

#### Logging vers le profiler:

    Profiler::log('info', 'Log some information to the profiler');

<a name="timers"></a>
## Timers et Benchmarking

Chronometrer et tester les performances de votre application est simple avec la fonction ```tick()``` du profiler. Cela vous permet de définir un jeu de différents timers et vous montrera les leurs résultats à la fin de l'execution de votre application.

Chaque timer peut avoir son propre nom. Chaque timer avec un nom identique à un timer précédent fait alors parti de son cycle de vie.

#### Utilisation du ticker par défaut

    Profiler::tick();
    Profiler::tick();

#### Utilisation de plusieurs tickers nommés

    Profiler::tick('myTimer');
    Profiler::tick('nextTimer');
    Profiler::tick('myTimer');
    Profiler::tick('nextTimer');

#### Utilisation d'un ticker nommé avec une méthode de callback
    Profiler::tick('myTimer', function($timers) {
        echo "I'm inside the timer callback!"; 
    });