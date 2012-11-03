@layout('main')

@section('title')
    {{ $title }} - Documentation de Laravel France
@endsection

@section('content')
    <div id="guides">
        <article>
    	   {{ $content }}
        </article>
        <aside id="menu">
            {{ $sidebar }}
        </aside>
    </div>
@endsection