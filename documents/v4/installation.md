# Installation

- [Installation de Composer](#install-composer)
- [Installation de Laravel](#install-laravel)
- [Prérequis](#server-requirements)
- [Configuration](#configuration)
- [Permissions](#permissions)
- [Des URLs propres](#pretty-urls)

<a name="install-composer"></a>
## Installation de Composer

Laravel utilise [Composer](http://getcomposer.org) pour gérer ses dépendances. Premièrement, téléchargez une copie de `composer.phar`. Une fois que vous avez l'archive PHAR, vous pouvez soit le laisser dans le dossier local de votre projet, soit le déplacer vers `usr/local/bin` pour l'utiliser de manière globale sur votre système. Sur Windows, vous pouvez utiliser l'installeur de Composer [pour Windows](https://getcomposer.org/Composer-Setup.exe).

<a name="install-laravel"></a>
## Installation de Laravel

Une fois que Composer est installé, téléchargez la [dernière version](https://github.com/laravel/laravel/archive/develop.zip) du framework, et extrayez son contenu dans un dossier sur votre serveur. Ensuite, à la racine de votre application Laravel, lancez la commande `php composer.phar install` pour installer toutes les dépendances du framework. Ce process requis que git soit installé sur le serveur pour terminer l'installation.

<a name="server-requirements"></a>
## Pré-requis

Le framework Laravel a quelques pré-requis système :

- PHP >= 5.3.7
- L'extension PHP MCrypt

<a name="configuration"></a>
## Configuration

Laravel n'a presque pas besoin de configuration pour fonctionner. En fait, vous êtes libre de commencer à développer ! Cependant, vous devriez au minimum jeter un oeil au fichier `app/config/app.php` et à sa documentation. Il contient plusieurs options comme `timezone` et `locale` que vous pourriez vouloir changer pour votre application.

> **Note:** Une option de configuration doit à tout prix avoir une valeur, il s'agit de l'option `key` du fichier `app/config/app.php`. Cette valeur doit être une chaine de 32 caractères aléatoires. Cette clé est utilisée pour chiffrer des valeurs, et les valeurs chiffrées ne seront pas sûres tant que cette clé n'est pas définie. Vous pouvez définir une clé aléatoire rapidement en lançant la commande Artisan suivante : `php artisan key:generate`.

<a name="permissions"></a>
### Permissions
Laravel a besoin que le serveur web ait un accès en écriture sur les dossiers à l'intérieur de `app/storage`.

<a name="paths"></a>
### Chemins

Plusieurs chemins des dossiers du Framework sont configurables. Pour changer leurs positions, regardez le fichier `bootstrap/paths.php`.

<a name="pretty-urls"></a>
## Des URLs propres

Le framework est fourni avec un fichier `public/.htaccess` qui est utilisé pour autoriser les URLs sans `index.php`. Si vous utilisez Apache pour servir votre application Laravel, veuillez vous assurer que le module `mod_rewrite` est actif.

Si le fichier `.htaccess` fourni avec Laravel ne fonctionne pas, essayez celui ci :

	Options +FollowSymLinks
	RewriteEngine on

	RewriteCond %{REQUEST_FILENAME} !-f
	RewriteCond %{REQUEST_FILENAME} !-d

	RewriteRule . index.php [L]
