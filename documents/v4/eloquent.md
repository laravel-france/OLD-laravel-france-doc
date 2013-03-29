# Eloquent ORM

- [Introduction](#introduction)
- [Utilisation Basique](#basic-usage)
- [Assignement de masse](#mass-assignment)
- [Insertion, mise à jour, suppression](#insert-update-delete)
- [Timestamps](#timestamps)
- [Cadres de requête](#query-scopes)
- [Relations](#relationships)
- [Chargements liés](#eager-loading)
- [Insertion de modèles liés](#inserting-related-models)
- [Travail sur les tables pivots](#working-with-pivot-tables)
- [Collections](#collections)
- [Les accesseurs et mutateurs](#accessors-and-mutators)
- [Evenements de modèle](#model-events)
- [Conversion en tableau / JSON](#converting-to-arrays-or-json)

<a name="introduction"></a>
## Introduction

L'ORM Eloquent inclu avec Laravel fournit une implémentation magnifique et simple d'ActiveRecord pour travailler avec votre base de donnée. Chaque table de votre base de donnée à un Modèle associé pour intéragir avec cette table.

Avant de commencer, n'oubliez pas de configurer votre connection à la base de donnée dans le fichier `app/config/database.php`.

<a name="basic-usage"></a>
## Utilisation Basique

Pour commencer, créez un modèle Eloquent. Ils sont généralement stockés dans le dossier `app/models`, mais vous êtes libre de les mettre dans n'importe quel endroit qui peut être chargé automatiquement en accord avec votre fichier `composer.json`.

**Définition d'une modèle Eloquent**

    class User extends Eloquent {}

Notez que nous n'avons pas indiqué à Eloquent quelle table doit être utilisée pour notre modèle `User`. Le nom de la classe en minuscule et au pluriel sera utilisé en tant que table à moins que vous ne définissiez une autre table explicitement. Donc dans ce cas, Eloquent utilisera le table `users` pour le modèle `User`. Pour définir explicitement un nom de table, définissez une propriété `$table` dans votre modèle :

    class User extends Eloquent {

        protected $table = 'my_users';

    }

Eloquent va également présumer que votre table à une clé primaire nommée `id`. Vous pouvez définir une propriété `primaryKey` pour surcharger cette convention. De la même manière, vous pouvez définir une propriété `connection` pour surcharger le nom de la connexion qui sera utilisé pour accéder à la table de ce modèle.

> **Note:** Eloquent va également assumé que chaque table à une clé primaire qui s'appelle `id`. Vous pouvez définir une clé primaire à la main en ajoutant une propriété `$primaryKey`.

Une fois qu'un modèle est défini, vous êtes prêt à récupérer et à créer des enregistrement dans votre table. Notez que vous aurez besoin de créer des colonnes `updated_at` et `created_at`  sur votre table par défaut. Si vous ne voulez pas de ces colonnes, qui sont auto maintenu par Laravel, définissez une propriété `$timestamps` à `false`.

**Retourne tous les modèles**

    $users = User::all();

**Retourne un modèle par sa clé primaire**

    $user = User::find(1);

    var_dump($user->name);

> **Note:** Toutes les méthodes disponibles dans le [Query Builder](/docs/v4/doc/queries) sont également disponibles avec Eloquent.

**Récupérer un modèle par sa clé primaire ou lancer une exception**

Parfois vous pourriez vouloir lancer une exception si un modèle n'est pas trouvé, vous permettant d'attraper les exceptions en utilisant un gestionnaire d'événement `error` et afficher une page 404.

    $model = User::findOrFail(1);

Pour enregistrer le gestionnaire d'erreur, écoutez auprès d'un `ModelNotFoundException`

    use Illuminate\Database\Eloquent\ModelNotFoundException;

    App::error(function(ModelNotFoundException $e)
    {
        return Response::make('Not Found', 404);
    });

**Requêtage utilisant le modèle Eloquent**

    $users = User::where('votes', '>', 100)->take(10)->get();

    foreach ($users as $user)
    {
        var_dump($user->name);
    }

Bien sur, vous pouvez également utilisé les fonctions d'aggrégats du Query Builder.

**Aggrégat avec Eloquent**

    $count = User::where('votes', '>', 100)->count();

<a name="mass-assignment"></a>
## Assignement de masse

Lorsque vous créez un nouvel modèle, vous passez un tableau d'attribut au constructeur du modèle. Ces attributs  sont ensuite assigné au modèle via l'assignement de masse. C'est très pratique, cependant cela peut être une risque **sérieux** de sécurité lorsque des données provenants d'utilisateurs sont aveugléments passées dans un modèle. Si les données de l'utilisateurs sont aveugléments passés au modèle, l'utilisateur est libre de modifier n'importe quel attribut du modèle.

Pour commencer, définissez les propriétés `fillable` ou `guarded` sur votre modèle.

La propriété `fillable` spécifie quels attributs peuvent être assignés en masse. Cela peut être défini dans la classe ou au niveau de l'instance du modèle.

**Definition de l'attribut fillable dans un modèle**

    class User extends Eloquent {

        protected $fillable = array('first_name', 'last_name', 'email');

    }

Dans cet exemple, seul les trois attributs listés peuvent être assignés lors d'un assignement de masse.

L'inverse de `fillable` est `guarded`, et il contient une "blacklist" plutôt qu'un laisser passer :

**Definition de l'attribut guarded dans un modèle**

    class User extends Eloquent {

        protected $guarded = array('id', 'password');

    }

Dans l'exemple ci dessus, les attributs `id` et `password` **ne peuvent pas** être assignés en masse. Tous les autres attributs peuvent être assignés lors d'un assignement de masse.  Vous pouvez aussi bloqué **tous** les attributs lors de l'assignement de masse en utilisant guard :

**Bloque tous les attributs lors de l'assignement de masse**

    protected $guarded = array('*');

<a name="insert-update-delete"></a>
## Insertion, mise à jour, suppression

Pour créer un nouvel enregistrement dans la base de donnée pour un modèle, créez simplement une nouvelle instance d'un modèle et appellez la méthode `save`.

**Sauvegarde un nouveau modèle**

    $user = new User;

    $user->name = 'John';

    $user->save();

**Note:** Typiquement, votre modèle Eloquent aura une clé de type auto-increment. Cependant, si vous souhaitez spécifier votre propre clé, définissez la propriété `incrementing` de votre modèle à `false`.

Vous pouvez également utiliser la méthode `create` Pour sauvegarder un modèle en une seule ligne. L'instance du modèle inséré sera retourné par la méthode. Cependant avant de faire cela, vous devrez spécifier soit l'attribut `fillable` ou `guarded` sur le modèle, car tous les modèles Eloquent sont protégés contre l'assignement de masse.

**Mise en place de l'attribut guarded sur le modèle**

    class User extends Eloquent {

        protected $guarded = array('id', 'account_id');

      }

**Création d'un utilisateur en utilant la méthode create**

    $user = User::create(array('name' => 'John'));

Pour mettre à modèle, récupérez le, changer un attribut, et utilisez la méthode `save` :

**Mise à jour d'un Modèle**

    $user = User::find(1);

    $user->email = 'john@foo.com';

    $user->save();

Vous pouvez aussi lancer une mise à jour sur un ensemble de modèle :

    $affectedRows = User::where('votes', '>', 100)->update(array('status' => 2));

Pour supprimer un modèle, appellez simplement la méthode `delete` sur une instance :

**Suppression d'un modèle existant**

    $user = User::find(1);

    $user->delete();

Bien sur, vous pouvez également supprimé un ensemble de modèle :

    $affectedRows = User::where('votes', '>', 100)->delete();

Si vous souhaitez simplement mettre à jour les timestamps d'un modèle, utilisez la méthode `touch` :

**Mise à jour uniquement des timestamps d'un modèle**

    $user->touch();

<a name="timestamps"></a>
## Timestamps

Par défaut, Eloquent maintiendra les colones `created_at` et `updated_at` de votre table automatiquement. Ajoutez simplement ces colonnes de type `datetime` à votre table et Eloquent va automatiquement se changer du reste. Si vous ne souhaitez pas qu'Eloquent s'en occupe, ajoutez la propriété suivante au modèle :

**Disabling Auto Timestamps**

    class User extends Eloquent {

        protected $table = 'users';

        public $timestamps = false;

    }

Si vous souhaitez personnaliser le format de vos timestamps, surchargez la méthode `freshTimestamp` de votre modèle :

**Création d'un formatde timestamp personnalisé pour ce modèle**

    class User extends Eloquent {

        public function freshTimestamp()
        {
            return time();
        }

    }

<a name="query-scopes"></a>
## Cadres de requête

Les cadres vous permettent de réutiliser facilement des logiques de requêtes dans vos modèles. Pour définir un cadre, prefixez simpelement une méthode du modèle avec `scope`:

**Définition d'un cadre de requête**

    class User extends Eloquent {

        public function scopePopular($query)
        {
           return $query->where('votes', '>', 100);
        }
    }

**Utilisation d'un cadre de requête**

  $users = User::popular()->orderBy('created_at')->get();

<a name="relationships"></a>
## Relations

Bien sur, vos tables sont propablement liées les unes aux autres. Par exemple, un post de blog à plusieurs commentaire, ou une commande est liée à l'utilisateur qui l'a passé. Eloquent Rend la gestion et le travail avec ces relations simple. Laravel supporte quatre type de relations:

- [Un vers un (1:1)](#one-to-one)
- [Un vers plusieurs (1:n)](#one-to-many)
- [Plusieurs vers plusieurs (n:n)](#many-to-many)
- [Relations polymorphiques](#polymorphic-relations)

<a name="one-to-one"></a>
### Un vers un (1:1)

Une relation un-vers-un est une relation très basique. Par exemple, un modèle `User` peut avoir un téléphone modèle `Phone`. Nous définissons la relation de la manière suivante avec Eloquent :

**Définition d'une relation un vers un**

    class User extends Eloquent {

        public function phone()
        {
            return $this->hasOne('Phone');
        }

    }

Le premier argument passé à la méthode `hasOne` est le nom du modèle lié. Une fois que la relation est définie, nous pouvons la récuperer en utilisant les propriétés dynamiques d'Eloquent :

    $phone = User::find(1)->phone;

Le SQL executé pour cette requête sera le suivant :

    select * from users where id = 1

    select * from phones where user_id = 1

Notez qu'Eloquent devine la clé étrangère en se basant sur le nom du modèle. Dans ce cas, le modèle `Phone` doit avoir une colonne `user_id` en tant que clé étrangère. Vous pouvez surchager cette convention en passant un second argument à la méthode `hasOne` :

    return $this->hasOne('Phone', 'custom_key');

Pour définir la relation inverse sur le modèle `Phone`, nous utilisons la méthode `belongsTo` :

**Définition de la relation inverse**

    class Phone extends Eloquent {

        public function user()
        {
            return $this->belongsTo('User');
        }

    }

<a name="one-to-many"></a>
### Un vers plusieurs (1:n)

Un exemple de relation une-vers-plusieurs est un post de blog qui à plusieurs commentaires. Nous réalisons cette relation comme cela :

    class Post extends Eloquent {

        public function comments()
        {
            return $this->hasMany('Comment');
        }

    }

Nous pouvons accéder aux commentaires du post via la propriété dynamique :

    $comments = Post::find(1)->comments;

Si vous avez besoin d'ajouter des contraintes suplémentaires à la récupération de 'comments', appellez la méthode `comments` et continuez à chainer les conditions :

    $comments = Post::find(1)->comments()->where('title', '=', 'foo')->first();

Une fois encore, vous pouvez surcharger le nom de la clé étrangère en passant en tant que second argument son nom à la méthode `hasMany` :

    return $this->hasMany('Comment', 'custom_key');

Pour définir la relation inverse, sur le modèle `Comment`, nous utilisons la méthode `belongsTo` :

**Définition de la relation inverse**

    class Comment extends Eloquent {

        public function post()
        {
            return $this->belongsTo('Post');
        }

    }

<a name="many-to-many"></a>
### Plusieurs vers plusieurs  (n:n)

Les relations plusieurs-vers-plusieurs sont un type un peu plus compliqué. Par exemple un utilisateur peut avoir plusieurs rôles, et un rôle peut être assigné à plusieurs utilisateurs. Plusieurs utilisateurs peuvent avoir un rôle "Admin" par exemple. Pour établir cette relation, nous avons besoin de trois tables : `users`, `roles`, et `role_user`. La table `role_user` est dérivée de l'ordre alphabetique des modèles liées, and avoir contenir les colonnes `user_id` et `role_id`.

Nous pouvons défnir une relation de type plusieurs-vers-plusieurs en utilisant la méthode `belongsToMany` :

    class User extends Eloquent {

        public function roles()
        {
            return $this->belongsToMany('Role');
        }

    }

Maintenant nous pouvons récupérer nos rôles via le modèle `User` :

    $roles = User::find(1)->roles;

Si vous souhaitez utiliser un nom non conventionnel pour votre table pivot, passez le second argument de la méthode `belongsToMany` :

    return $this->belongsToMany('Role', 'user_roles');

Vous pouvez également surchager les clés associées :

    return $this->belongsToMany('Role', 'user_roles', 'user_id', 'foo_id');

Bien sur, vous pouvez aussi avoir besoin de définir de la relation dans le modèle `Role` :

    class Role extends Eloquent {

        public function users()
        {
            return $this->belongsToMany('User');
        }

    }

<a name="polymorphic-relations"></a>
### Relations polymorphiques

Les relations polymorphiques permettent à un modèle d'appartenir à plus d'un autre modèle, en une simple associtation. Par exemple, vous pourriez avoir un modèle Photo qui appartient au modèle Staff ainsi qu'au modèle c. Nous définirons cette relation de la manière suivante :

    class Photo extends Eloquent {

        public function imageable()
        {
            return $this->morphTo();
        }

    }

    class Staff extends Eloquent {

        public function photos()
        {
            return $this->morphMany('Photo', 'imageable');
        }

    }

    class Commande extends Eloquent {

        public function photos()
        {
            return $this->morphMany('Photo', 'imageable');
        }

    }

Maintenant, nous pouvons récuperer les photos de soit notre staff, soit d'une commande :

**Récupération d'une relation polymorphique**

    $staff = Staff::find(1);

    foreach ($staff->photos as $photo)
    {
        //
    }

Cependant, la vrai magie de la polymorphie appartait lorsque vous accédez au staff ou à la commande depuis le modèle `Photo` :

**Récupération du propriétaire de la Photo**

    Photo::find(1);

    $imageable = $photo->imageable;

La relation `imageable` du modèle `Photo` retournera soit une instance de `Staff` ou de `Commande`, selon quel modèle est propriétaire de la photo.

Pour vous aider à comprendre comment cela marche, jetons un oeil à la structure de la base de donnée pour une relation polymorphique :

**Structure de la base de donnée pour une relation polymorphique**

    staff
        id - integer
        name - string

    orders
        id - integer
        price - integer

    photos
        id - integer
        path - string
        imageable_id - integer
        imageable_type - string

Le champ clés à remarquer ici sont `imageable_id` et `imageable_type` de la table `photos`. L'ID contiendra la valeur de l'ID d'une ligne de staff ou de commande ici par exemple, tandis que le type contiendra le nom de la classe du modèle propriétaire. C'est ce qui permet à l'ORM de determiner quel type de propriétaire doit être retourné lors de l'accès à la relation `imageable`.

<a name="eager-loading"></a>
## Chargements liés

Les chargements liés (eager loading) existent pour éviter le problème des requêtes N + 1. Par exemple, disons qu'un modèle `Book` est relié à un modèle `Author`. La relation est définie de la manière suivante :

    class Book extends Eloquent {

        public function author()
        {
            return $this->belongsTo('Author');
        }

    }

Maintenant considerez le code suivant :

    foreach (Book::all() as $book)
    {
        echo $book->author->name;
    }

La boucle executera une requête pour récuperer tous les livres de la table, ensuite une autre requête sur chaque livre pour récuperer l'auteur. Donc, si nous avons 25 livres, nous aurons 26 requêtes.

Heuresement, nous pouvons utiliser les chargements liés pour réduire drastiquement le nombre de requête. Les relations qui doivent être chargés doivent être préciser avec la méthode `with` :

    foreach (Book::with('author')->get() as $book)
    {
        echo $book->author->name;
    }

Pour la boucle ci dessus, les requêtes suivantes sont executées :

    select * from books

    select * from authors where id in (1, 2, 3, 4, 5, ...)

Une utilisation sage des chargements liés peut augmenter drastiquement les performances de votre application.

Bien sur, vous pouvez faire des chargements liés sur plusieurs relations en une fois :

    $books = Book::with('author', 'publisher')->get();

Vous pouvez même faire du chargement lié de manière imbriquée :

    $books = Book::with('author.contacts')->get();

Dans l'exemple ci dessus, la relation `author` sera chargé de manière liée, et les contacts de l'auteur seront chargés également.

### Contraintes sur les chargements liéss

Si vous avez besoin d'ajouter des contraintes sur un chargement lié,  vous pouvez le faire de la manière suivante :

    $users = User::with(array('posts' => function($query)
    {
        $query->where('title', 'like', '%first%');
    }))->get();

Dans cet exemple; nous chargeons les posts de l'utilisateur, mais seulement si le post contient le mot "first".

### Chargements liés différé

Il est également possible de faire du chargement lié directement sur une collection de modèles existantes. Cela peut s'averer utile si vous devez décider dynamiquement de charger les modèles liés ou non, ou en combinaison avec du cache.

    $books = Book::all();

    $books->load('author', 'publisher');

<a name="inserting-related-models"></a>
## Insertion de modèles liés

Vous aurez souvent besoin d'insérer des nouveaux modèles liés. Par exemple, pour insérer un commentaire lié à un post de blog. Plutôt que de définir manuellement la clé étrangère `post_id` sur le modèle, vous pouvez insérer un nouveau commentaire directement depuis son modèle parent `Post` directement :

**Attachement un modèle lié**

    $comment = new Comment(array('message' => 'A new comment.'));

    $post = Post::find(1);

    $comment = $post->comments()->save($comment);

Dans ces exemple, le champ `post_id` sera automatiquement rempli dans le commentaire inséré.

### Insertion de modèles liés, plusieurs vers plusieurs

Vous devrez également insérer des modèles liés par une relation plusieurs vers plusieurs. Continuons d'utiliser nos modèles d'exemples `User` et `Role`. Nous pavons facilement attacher des nouveaux rôles à un utilisateur avec la méthode :

**Attache des modèles liés par une relation plusieurs vers plusieurs**

    $user = User::find(1);

    $user->roles()->attach(1);

Vous pouvez également passer un tableau d'attributs qui doivent être stockés dans la table pivot pour la relation :

    $user->roles()->attach(1, array('expires' => $expires));

Naturellement, l'opposé de `attach` est `detach` :

    $user->roles()->detach(1);

Vous pouvez également utiliser la méthode `sync` pour attacher des modèles liés. La méthode `sync` accepte un tableau d'IDs à placer dans la table pivot. Une fois cette opération terminée, seul les IDs dans le tableau seront dans la table pivot pour le modèle :

**Utilisation de la méthode Sync pour attacher des modèles liés**

    $user->roles()->sync(array(1, 2, 3));

Vous pouvez également créer une nouveau modèle lié et l'attacher en une simple ligne. Pour cette opératio, utilisez la méthode `save` :

    $role = new Role(array('name' => 'Editor'));

    User::find(1)->roles()->save($role);

Dans cet exemple, le nouveau modèle `Role` sera sauvegardé et attaché au modèle `User`. Vous pourriez également avoir besoin de passer un tableau d'attributs pour le sauvegarder dans la table de jointure :

    User::find(1)->roles()->save($role, array('expires' => $expires));

<a name="working-with-pivot-tables"></a>
## Travail sur les tables pivots

Comme vous l'avez déjà appris, le travail avec les relations plusieurs-vers-plusieurs requis le présence d'une table intermédiaire. Eloquent fournit des moyens très utiles d'intéragir avec cette table. Par exemple, disons que nous avons un objet `User` qui a plusieurs objets `Role`. Après avoir accédé à la relation, vous pouvez accéder à la table `pivot` du modèle :

    $user = User::find(1);

    foreach ($user->roles as $role)
    {
        echo $role->pivot->created_at;
    }

Notez que chaque modèle `Role` que nous récupérons aura automatiquement l'attribut `pivot` assigné. Cet attribut  contient un modèle qui représente la table intermediaire, et peut être utilisé comme n'importe quel autre modèle Eloquent.

Par défaut, seul les clés seront présentes dans l'objet `pivot`. Si vous table pivot contient des attributs en plus, vous devez les spécifier lors de la définition de la relation :

    return $this->belongsToMany('Role')->withPivot('foo', 'bar');

Maintenant, les attributs `foo` et `bar` seront accessible par l'objet `pivot` pour le modèle `Role`.

Si vous souhaitez que que votre table pivot ai les timestamps `created_at` et `updated_at` automatiquement maintenus, utilisez la méthode `withTimestamps` sur la définition de la relation :

    return $this->belongsToMany('Role')->withTimestamps();

Pour supprimer toutes les lignes de la table pivot pour un modèle, vous pouvez utiliser la méthode `delete` :

**Suppression des lignes de la table pivot**

    User::find(1)->roles()->delete();

Notez que cette opération ne supprimera pas les enregistrements de la table `roles`, mais seulement de la table pivot.

<a name="collections"></a>
## Collections

Tous les requêtes qui renvoient plusieurs résultats par Eloquent via la méthode `get` ou par une relation retournent une objet Eloquent `Collection`. Cet objet implémente l'interface PHP `IteratorAggregate`, donc nous pouvons itérer dessus comme pour un tableau. Cependant, cet objet a également une variété de méthode utiles pour travailler avec une liste de résultat.

Par exemple, nous pouvons determiné si une liste de résultat contient une clé primaire donnée en utilisant la méthode `contains` :

**Vérifie si une collection contient une clé**

    $roles = User::find(1)->roles;

    if ($roles->contains(2))
    {
        //
    }

Les Collections peuvent être converties en tableau ou en JSON :

    $roles = User::find(1)->roles->toArray();

    $roles = User::find(1)->roles->toJson();

Si une collection est casté en une chaine, alors sa représentation JSON sera retournée :

    $roles = (string) User::find(1)->roles;

Les collections Eloquent contiennent également quelques méthodes utiles pour boucler et filtrer sur les objets qu'elle contient :

**Bouclage et filtrage de collections**

    $roles = $user->roles->each(function($role)
    {

    });

    $roles = $user->roles->filter(function($role)
    {

    });

Parfois, vous pourriez vouloir retourner une collection personnalisée avec vos propres méthodes ajoutées. Vous devez spécifier cela dans votre modèlé Eloquent en surchargeant la méthode `newCollection` :

**Retourne un type de collection personnalisé**

    class User extends Eloquent {

        public function newCollection(array $models = array())
        {
            return new CustomCollection($models);
        }

    }

**Applique une fonction de retour sur les objets d'une collection**

    $roles = User::find(1)->roles;
    
    $roles->each(function($role)
    {
        //  
    });
    

<a name="accessors-and-mutators"></a>
## Les accesseurs et mutateurs

Eloquent fournit une manière efficace de transformer vos attributs de modèle lorsque vous les récuperez ou les définissez. Définissez simplement une méthode `getFooAttribute` sur votre modèle pour créer un accesseur. Gardez à l'esprit que les méthodes doivent être en camelCase, même si les colonnes de votre base sont en snake_case :

**Définition d'un accesseur**

    class User extends Eloquent {

        public function getFirstNameAttribute($value)
        {
            return ucfirst($value);
        }

    }

Dans l'exemple ci dessus, la colonne `first_name` a un accesseur. Notez que la valeur est passée à l'accesseur.

Les mutateurs sont déclarés dans le même ésprit :

**Définition d'un mutateur**

    class User extends Eloquent {

        public function setFirstNameAttribute($value)
        {
            $this->attributes['first_name'] = strtolower($value);
        }

    }

<a name="model-events"></a>
## Evenements de modèle

Les modèles Eloquent lancent plusieurs événements, vous permettant de d'intéragir avec le modèle durant son cycle de vie en utilisant les méthodes : `creating` (avant la création), `created` (une fois créé), `updating` (avant la mise à jour), `updated` (une fois mis à jour), `saving` (avant l'enregistrement), `saved` (une fois enregistré), `deleting` (avant la suppression), `deleted` (une fois supprimé). Si `false` est retourné par la méthode `creating`, `updating`, ou `saving`, alors l'action est annulée :

**Annulation de la création d'un modèle**

    User::creating(function($user)
    {
        if ( ! $user->isValid()) return false;
    });

Les modèles Eloquent contiennent également une méthode static `boot`, qui peut être l'endroit idéal pour s'abonner aux événements 

**Mise en place de la méthode boot d'un modèle**

  class User extends Eloquent {

    public static function boot()
    {
      parent::boot();

      // Setup event bindings...
    }

  }


<a name="converting-to-arrays-or-json"></a>
## Conversion en tableau / JSON

Quand une construisez des APIs en JSON, vous devez souvent convertir vos modèles et vos relations en tableau ou en JSON. Eloquent inclus des méthodes pour le faire. Pour convertir un modèle et ses relations en tableau, vous pouvez utiliser la méthode `toArray` :

**Convertion d'un modèlé en tableau**

    $user = User::with('roles')->first();

    return $user->toArray();

Notez que l'intégralité des collections de modèles peuvent être converties en tableau :

    return User::all()->toArray();

Pour convertir un modèle en JSON, vous pouvez utiliser la méthode `toJson` :

**Conversion d'un modèle en JSON**

    return User::find(1)->toJson();

Notez que quand un modèle ou une collection est casté en string, ils seront convertis en JSON, ce qui signifie que vous pouvez retourner des objets Eloquent directement depuis vos routes/actions !

**Retourne un modèle depuis une route**

    Route::get('users', function()
    {
        return User::all();
    });

Parfois vous pourriez souhaiter que certains attributs ne soient pas inclus dans la forme tableau ou JSON de vos modèles, tels que les mot de passes. Pour ce faire, ajoutez la propriété `hidden`  à la définition de votre modèle :

**Cache un attribut des formats tableaux Ou JSON**

    class User extends Eloquent {

        protected $hidden = array('password');

    }
