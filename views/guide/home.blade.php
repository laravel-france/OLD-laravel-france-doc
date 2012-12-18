@layout('main')

@section('title')
    Guides - Laravel France
@endsection

@section('content')
    <div id="guides">
      <div class="row">
        <div class="span3">
          <div class="well">
            <h3>Laravel 3</h3>
            <h4>La documentation</h4>
            <ul>
                <li><a href="/guides/doc/v3#general">Général</a></li>
                <li><a href="/guides/doc/v3#base_de_donnees">Base de données</a></li>
                <li><a href="/guides/doc/v3#caching">Caching</a></li>
                <li><a href="/guides/doc/v3#sessions">Sessions</a></li>
                <li><a href="/guides/doc/v3#authentication">Authentication</a></li>
                <li><a href="/guides/doc/v3#artisan">Artisan CLI</a></li>
            </ul>

            <h4>Les livres</h4>
            <ul>
                <li><a href="#code_happy">Code Happy [en]</a></li>
                <li><a href="#Laravel_Starter">Laravel Starter [en]</a></li>
            </ul>


          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
            <ul class="breadcrumb">
                <li><a title="Retour à la page d'accueil" href="/"><i class="icon-home"></i></a> <span class="divider">/</a></li>
                <li>Guides</li>
            </ul>

            <div class="well">
                <h2>La documentation</h2>

                <p>
                    Une véritable mine d'information, elle contient tout ce qu'il vous faut pour démarrer avec le Framework Laravel. Vous y trouverez toutes les explications nécéssaires, ainsi que des exemples pratiques.
                </p>
            
                <p class="clearfix">
                    <a href="/guides/doc/v3" class="btn btn-primary btn-large pull-right">
                        Accéder à la doc
                    </a>
                </p>
            </div>

            <h2 class="clearfix">Livres</h2>
            <p>
                Vous trouverez ci dessous une collection de livres que nous vous recommandons            
            </p>
            
        <div class="row-fluid">
            <ul class="thumbnails" id="books">


              <li class="span4">
                <a name="code_happy"></a>
                <div class="thumbnail">
                  <img src="{{ URL::to_asset('img/books/code_happy.jpg') }}">
                  <div class="caption">
                    <h3>Code Happy [en]<br /><small>Dayle Rees</small></h3>
                    <p>Ce livre est écrit par un membre de l'équipe de Laravel que l'on ne présente plus, Dayle Rees.</p>

                    <p>Vous y apprendrez à utiliser les fonctionnalités de bases du framework, tout au long d'un tutoriel qui vous montrera comment créer un simple blog à partir de zéro.</p>

                    <p><a href="http://www.lulu.com/shop/dayle-rees/code-happy/paperback/product-20249496.html" class="btn btn-primary">voir</a></p>
                  </div>
                </div>
              </li>

              <li class="span4">
                <a name="Laravel_Starter"></a>
                <div class="thumbnail">
                  <img src="{{ URL::to_asset('img/books/laravel_starter.jpg') }}">
                  <div class="caption">
                    <h3>Laravel Starter [en]<br /><small>Shawn McCool</small></h3>
                    <p>"Laravel Starter" est l'introduction idéale à Laravel. C'est un livre axé sur la pratique, une sorte de tutoriel basé sur différentes tâches, ou vous découvrirez pas à pas des sujets tels que la séparation du code en suivant le pattern MVC, la création de code modulaire, l'utilisation d'ActiveRecord en tant que couche d'asbtraction aux données, tout en exploitant les possibilités offertes par le Framework</p>
                    <p>Basé sur le comment plutôt que sur le pourquoi, ce livre vous donne de quoi commencer rapidement la création d'application web professionelles avec Laravel</p>
                    <p><a href="http://www.packtpub.com/laravel-php-starter/book" class="btn btn-primary">Voir</a></p>
                  </div>
                </div>
              </li>
            </ul>
          </div>



        </div>
    </div>
@endsection