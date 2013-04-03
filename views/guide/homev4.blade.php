@layout('main')

@section('title')
    Documentation Laravel 4 beta - Laravel France
@endsection

@section('content')
    <div id="docs">
      <div class="row">
        <div class="span3">

            @include('docs::guide.selector')

          <div class="well">
            <h3>Laravel 4 beta</h3>
            <ul>
                <li><a href="{{ URL::to_route('doc_home', array($version)) }}#preface">Préface</a></li>
                <li><a href="{{ URL::to_route('doc_home', array($version)) }}#premiers-pas">Les premiers pas</a></li>
                <li><a href="{{ URL::to_route('doc_home', array($version)) }}#aller-plus-loin">Aller plus loin</a></li>
                <li><a href="{{ URL::to_route('doc_home', array($version)) }}#base-de-donnees">Base de données</a></li>
                <li><a href="{{ URL::to_route('doc_home', array($version)) }}#artisan-cli">Artisan CLI</a></li>
            </ul>



          </div>
        </div>
        <div class="span9">
            <ul class="breadcrumb">
                <li><a title="Retour à la page d'accueil" href="{{ URL::home() }}"><i class="icon-home"></i></a> <span class="divider">/</a></li>
                <li>Documentation & guides</li>
            </ul>

           <div class="alert alert-info">
                <strong>Important : </strong>Laravel v4 est actuellement en version beta, et sa documentation évolue énormement. Nous nous efforçons de garder la traduction la plus à jour possible.
            </div>

            <div class="well">
                <h2>La documentation</h2>

                <p>
                    Une véritable mine d'information, elle contient tout ce qu'il vous faut pour démarrer avec le Framework Laravel. Vous y trouverez toutes les explications nécéssaires, ainsi que des exemples pratiques.
                </p>
            
                <p class="clearfix">
                    <a href="{{ URL::to_route('doc_home', array($version)) }}" class="btn btn-primary btn-large pull-right">
                        Accéder à la doc
                    </a>
                </p>
            </div>
        </div>
    </div>
@endsection