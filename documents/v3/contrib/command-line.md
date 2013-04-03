# Contribuez à Laravel via la ligne de commande

## Au menu

- [Mise en route](#getting-started)
- [Forkez Laravel](#forking-laravel)
- [Clonez Laravel](#cloning-laravel)
- [Ajoutez votre Fork](#adding-your-fork)
- [Créez des Branches](#creating-branches)
- [Committez](#committing)
- [Soumettez une Pull Request](#submitting-a-pull-request)
- [Et ensuite ?](#whats-next)

<a name="getting-started"></a>
## Mise en route

Ce tutoriel explique les bases pour contribuer au projet sur [GitHub](https://github.com/) via la ligne de commande. Le workflow peut s'appliquer à beaucoup de projets sur GitHub, mais dans ce cas, nous nous focaliserons sur le projet [Laravel](https://github.com/laravel/laravel). Ce tutoriel est applicable sur OSX, Linux et Windows.

Pour ce tutoriel, vous devez avoir installé [Git](http://git-scm.com/) et que vous avez créé un [compte GitHub](https://github.com/signup/free). Si ce n'est pas le cas, lisez la documentation [Laravel sur GitHub](/docs/v3/doc/contrib/github) pour vous familiariser avec les dépôts et branches de Laravel.

<a name="forking-laravel"></a>
## Forkez Laravel

Connectez vous sur GitHub et visitez le [dépôt Laravel](https://github.com/laravel/laravel). Cliquez sur le bouton **Fork**. Cela créera votre propre fork de Laravel dans votre compte GitHub. Votre fork de Laravel sera disponible sur **https://github.com/username/laravel** (votre pseudo GitHub sera utilisé à la place de *username*).

<a name="cloning-laravel"></a>
## Clonez Laravel

Ouvrez votre ligne de commande ou terminal et créez un nouveau répertoire où vous pourrez modifier Laravel :

    # mkdir laravel-develop
    # cd laravel-develop

Ensuite, clonez le dépôt Laravel (ne forkez pas ce que vous avez fait) :

    # git clone https://github.com/laravel/laravel.git .

> **Note**: La raison de cloner le dépôt d'origine de Laravel (et non le fork que vous avez fait) est de toujours récupérer les derniers développements du dépôt Laravel dans votre dépôt local.

<a name="adding-your-fork"></a>
## Ajoutez votre Fork

Ensuite, il est temps d'ajouter le fork que vous avez fait comme un **dépôt distant** :

    # git remote add fork git@github.com:username/laravel.git

N'oubliez pas de remplacer **username** avec votre pseudo GitHub. *C'est sensible à la case*. Vous pouvez vérifier que votre fork a été ajouté en tapant :

    # git remote

Maintenant que vous avez un clone vierge du dépôt Laravel avec votre fork comme un dépôt distant. Vous êtes prêt pour commencer à créer des branches pour de nouvelles fonctionnalités ou corriger des bugs.

<a name="creating-branches"></a>
## Créez des Branches

Tout d'abord, soyez sûr que vous travaillez dans la branche **develop**. Si vous soumettez des changements dans la branche **master**, il est peu probable qu'ils soient repris dans un futur proche. Pour plus d'informations sur ce sujet, lisez la documentation sur [Laravel sur GitHub](/docs/v3/doc/contrib/github). Pour switcher sur la branche develop :

    # git checkout develop

Ensuite, vous voulez être sûr d'être à jour avec le dernier dépôt Laravel. Si de nouvelles fonctionnalités ou corrections de bugs sont ajoutées au projet Laravel depuis que vous l'avez cloné, 

Next, you want to make sure you are up-to-date with the latest Laravel repository. If any new features or bug fixes have been added to the Laravel project since you cloned it, this will ensure that your local repository has all of those changes. This important step is the reason we originally cloned the Laravel repository instead of your own fork.

    # git pull origin develop

Now you are ready to create a new branch for your new feature or bug-fix. When you create a new branch, use a self-descriptive naming convention. For example, if you are going to fix a bug in Eloquent, name your branch *bug/eloquent*:

    # git branch bug/eloquent
    # git checkout bug/eloquent
    Switched to branch 'bug/eloquent'

Or if there is a new feature to add or change to the documentation that you want to make, for example, the localization documentation:

    # git branch feature/localization-docs
    # git checkout feature/localization-docs
    Switched to branch 'feature/localization-docs'

> **Note:** Create one new branch for every new feature or bug-fix. This will encourage organization, limit interdependency between new features/fixes and will make it easy for the Laravel team to merge your changes into the Laravel core.

Now that you have created your own branch and have switched to it, it's time to make your changes to the code. Add your new feature or fix that bug.

<a name="committing"></a>
## Committez

Maintenant que vous avez fini de coder et de tester vos modifications, il est temps de les committer à votre dépôt local. Tout d'abord, ajoutez les fichiers que vous avez modifié/ajouté :

    # git add laravel/documentation/localization.md

Ensuite, committez les changements au dépôt :

    # git commit -s -m "I added some more stuff to the Localization documentation."

"- **-s** signifie que vous means that you are signing-off on your commit with your name. This tells the Laravel team know that you personally agree to your code being added to the Laravel core.
"- **-m** is the message that goes with your commit. Provide a brief explanation of what you added or changed.

<a name="pushing-to-your-fork"></a>
## Envoyez à votre Fork

Maintenant que votre dépôt local a les changements soumis, il est temps d'envoyer (ou synchroniser) votre nouvelle branche à votre fork qui est hébergé sur GitHub :

    # git push fork feature/localization-docs

Votre branche a été envoyé avec succès à votre fork sur GitHub.

<a name="submitting-a-pull-request"></a>
## Soumettez une Pull Request

L'étape finale est de soumettre une pull request au dépôt Laravel. 

The final step is to submit a pull request to the Laravel repository. Cela signifie que vous demandez à l'équipe de Laravel de prendre en compte vos développements. Dans votre navigateur, visitez votre fork de Laravel sur [https://github.com/username/laravel](https://github.com/username/laravel). Cliquez sur **Pull Request**. Ensuite, soyez sûr que vous choisissez la base et le dépôt adéquates, et les branches :

- **base repo:** laravel/laravel
- **base branch:** develop
- **head repo:** username/laravel
- **head branch:** feature/localization-docs

Utilisez le formulaire pour écrire une description plus détaillée des modifications que vous avez faites et pourquoi vous les avez faites. Pour finir, cliquez sur **Send pull request**. C'est tout ! Les changements que vous avez faits ont été soumis à l'équipe de Laravel.

<a name="whats-next"></a>
## Et ensuite ?

Avez vous une autre fonctionnalité que vous voulez ajouter ou un autre bug que vous avez besoin de corriger ? Tout d'abord, soyez sûr que vous avez toujours basé votre nouvelle branche sur la branche develop :

    # git checkout develop

Ensuite, récuperez les derniers changements du dépôt de Laravel :

    # git pull origin develop

Maintenant vous êtes prêt à créer une nouvelle branche et à commencer à coder !

> Le post du blog de [Jason Lewis](http://jasonlewis.me/), [Contributing to a GitHub Project](http://jasonlewis.me/blog/2012/06/how-to-contributing-to-a-github-project), a été la principale inspiration pour ce tutoriel.