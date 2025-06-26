<article class="blog_article">
    <a title="{{ $post->title }}" href="{{ url('/blog/'.$post->uri) }}">
        <img title="{{ $post->title }}" alt="{{ $post->title }}" src="{{asset($post->cover)}}"/>
    </a>
    <header>
        <p class="meta">
            <a title="Artigos em {{ $post->category->title }}"
               href="{{ url('/blog/em/'.$post->category->uri) }}">{{$post->category->title}}</a>
            &bull; Por  {{$post->user->first_name}} {{$post->user->last_name}}
            &bull; {{$post->post_at->format('d/m/Y H:i')}}
        </p>
        <h2><a title="{{$post->title}}" href="{{url('/blog/'.$post->uri)}}">{{$post->title}}</a></h2>
        <p><a title="{{$post->title}}" href="{{url('/blog/'.$post->uri)}}"></a>
                {{Str::limit($post->subtitle, 120)}}</p>
    </header>
</article>