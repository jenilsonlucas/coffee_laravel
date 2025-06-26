@extends('layouts._theme')
<h1></h1>
@section('content')
<article class="post_page">
    <header class="post_page_header">
        <div class="post_page_hero">
            <h1>{{$post->title }}</h1>
            <img class="post_page_cover" alt="{{$post->title}}" title="{{$post->title}}"
                src="{{$post->cover}}" />
            <div class="post_page_meta">
                <div class="author">
                    <div><img alt="{{$post->user->first_name.' '.$post->user->last_name}}"
                            title="{{$post->user->first_name.' '.$post->user->last_name}}"
                            src="{{$post->user->photo}}" />
                    </div>
                    <div class="name">
                        Por: {{$post->user->first_name .' '.  $post->user->last_name}}
                    </div>
                </div>
                <div class="date">Dia {{$post->post_at->format('d/m/y')}}</div>
            </div>
        </div>
    </header>

    <div class="post_page_content">
        <div class="htmlchars">
            <h2>{{$post->subtitle}}</h2>
            {!! $post->content !!}
        </div>

        <aside class="social_share">
            <h3 class="social_share_title icon-heartbeat">Ajude seus amigos a controlar:</h3>
            <div class="social_share_medias">
                <!--facebook-->
                <div class="fb-share-button" data-href="{{url($post->uri)}}" data-layout="button_count"
                    data-size="large"
                    data-mobile-iframe="true">
                    <a target="_blank"
                        href="https://www.facebook.com/sharer/sharer.php?u={{urlencode(url($post->uri))}}"
                        class="fb-xfbml-parse-ignore">Compartilhar</a>
                </div>

                <!--twitter-->
                <a href="https://twitter.com/share?ref_src=site" class="twitter-share-button" data-size="large"
                    data-text="{{$post->title}}" data-url="{{url($post->uri)}}"
                    data-via="jenilsonlucas@"
                    data-show-count="true">Tweet</a>
            </div>
        </aside>
    </div>

    @isset($related)
    <div class="post_page_related content">
        <section>
            <header class="post_page_related_header">
                <h4>Veja também:</h4>
                <p>Confira mais artigos relacionados e obtenha ainda mais dicas de controle para suas
                    contas.</p>
            </header>

            <div class="blog_articles">
                @each('blog.blog-list', $related, 'post')
            </div>
        </section>
    </div>
    @endisset
</article>

@endsection