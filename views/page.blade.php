@layout('main')

@section('title')
    {{ $title }} - Documentation de Laravel France
@endsection

@section('content')
    <div id="guides">
      <div class="row">
        <div class="span3">
          <div class="well">
            {{ $sidebar }}
          </div><!--/.well -->
        </div><!--/span-->
        <div class="span9">
            <article>
               {{ $content }}
            </article>
        </div>
    </div>
@endsection