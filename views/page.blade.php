@layout('main')

@section('title')
    {{ $title }} - Documentation de Laravel France
@endsection

@section('content')
    <div id="docs">
      <div class="row">
        <div class="span3">

            @include('docs::guide.selector')


          <div class="well">
            {{ $sidebar }}
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
            <ul class="breadcrumb">
                <li><a title="Retour à la page d'accueil" href="{{ URL::home() }}"><i class="icon-home"></i></a> <span class="divider">/</a></li>
                <li><a href="{{ URL::to_route('doc_home', array($version)) }}">{{ $bc_title }}</a>
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
