# Traduction & Localisation

## Au menu

- [Les bases](#the-basics)
- [Retrouver une ligne de langue](#get)
- [Utilisation de jokers](#replace)

<a name="the-basics"></a>
## Les bases

La localisation est le processus de traduction de votre application en différent langages. La classe **Lang** fournie un mécanisme simple pour vous aider à organiser et à retrouver le texte de votre application multilingue.

Tous les fichiers de langues de votre application se trouvent dans le dossier **application/language**. Dans ce dossier **application/language**, vous devrez créer un dossier pour chaque langage que vous application supportera. Par exemple, si vous application supporte l'anglais et le français, vous devrez créer un dossier **en** et un dossier **sp**.

Laravel est fourni avec une liste relativement bien fournie de dossiers de langues, pour le supporte des messages d'erreurs.

Chaque dossier de langue peut contenir plusieurs fichiers de langues. Et dans chaque fichier se trouve un tableau de chaînes dans ce langage. En fait, les fichier de langues ont la même structure que les fichier de configurations. Par exemple dans le dossier **application/language/fr**, vous pourriez créer un fichier **marketing.php** qui ressemblerai à cela :

#### Crée un fichier de langue :

	return array(

	     'welcome' => 'Bienvenu sur notre site !',

	);

Ensuite, vous créeriez un fichier **marketing.php** dans le dossier **application/language/en**. Il ressemblerai à cela :

	return array(

	     'welcome' => 'Welcome to our website!',

	);

Bien ! Maintenant vous êtes prêt pour commencer à mettre en place vos dossiers & fichiers de langue, continuons à "localiser" !

<a name="get"></a>
## Retrouver une ligne de langue

#### Retrouve une ligne de langue:

	echo Lang::line('marketing.welcome')->get();

#### Retrouver une ligne de langue en utilisant l'helper "__" ( deux underscore ):

	echo __('marketing.welcome');

Remarquez comment le point est utiliser pour séparer "marketing" et "welcome". Le texte avant le point correspond au fichier de langue, tandis que le texte après le point correspond à une clé inscrite de ce fichier.

Pour obtenir une ligne dans un langage précis, passez l'abréviation de ce langage à la méthode `get` :

#### Obitent une ligne de langue dans un langage donné :

	echo Lang::line('marketing.welcome')->get('en');

<a name="replace"></a>
## Utilisation de jokers

Maintenant, travaillons sur notre message de bienvenu. Ce dernier est très générique. Trop générique. Pourquoi ne pas placer le nom de l'utilisateur ci ce dernier est connécté ? Pour ce faire, nous pouvons placer un joker dans notre ligne de langue. Les jokers sont précédés par le caractère ':' :

#### Crée une ligne de langage avec un joker :

	'welcome_connected' => 'Bienvenu sur notre site, :nom!'

#### Retrouve une ligne de langue avec une valeur :

	echo Lang::line('marketing.welcome_connected', array('nom' => 'Julien'))->get();

#### Retrouve une ligne de langue avec une valeur en utilisant "__":

	echo __('marketing.welcome', array('nom' => 'Julien'));