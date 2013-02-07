@layout('main')

@section('title')
    {{ $title }} - Documentation de Laravel France
@endsection

@section('content')
    <div id="docs">
      <div class="row">
        <div class="span3">
          <div class="well">
            {{ $sidebar }}
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
            <ul class="breadcrumb">
                <li><a title="Retour à la page d'accueil" href="/"><i class="icon-home"></i></a> <span class="divider">/</a></li>
                <li><a href="/docs">Documentation & guides</a> <span class="divider">/</span></li>
                <li><a href="/docs/v3/doc">{{ $bc_title }}</a>
                @if( (isset($isHome) and $isHome === false) || (isset($section) and $section != "home"))
                    <span class="divider">/</span></li>  <li class="active">{{ $title }}</li>
                @else
                </li>
                @endif
            </ul>

            <article>
               {{ $content }}
            </article>
        </div>
    </div>
@endsection