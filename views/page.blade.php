@layout('main')

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