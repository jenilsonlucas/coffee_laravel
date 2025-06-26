@extends('layouts._theme')

<h1></h1>

@section('content')
<section class="blog_page">
    <header class="blog_page_header">
        <h1>@php ($title ?? "BLOG");@endphp</h1>
        <p>@php ($search ?? $desc ?? "Confira nossas dicas para controlar melhor suas contas"); @endphp</p>
        <form name="search" action="{{url('/blog/buscar');}}" method="post" enctype="multipart/form-data">
            <label>
                <input type="text" name="s" placeholder="Encontre um artigo:" required/>
                <button class="icon-search icon-notext"></button>
            </label>
        </form>
    </header>

    
    @if(empty($blog) && !empty($search))
        <div class="content content">
            <div class="empty_content">
                <h3 class="empty_content_title">Sua pesquisa não retornou resultados :/</h3>
                <p class="empty_content_desc">Você pesquisou por <b> $search </b>. Tente outros termos.</p>
                <a class="empty_content_btn gradient gradient-green gradient-hover radius"
                   href="<?= url("/blog"); ?>" title="Blog">...ou volte ao blog</a>
            </div>
        </div>
    @elseif (empty($blog))
        <div class="content content">
            <div class="empty_content">
                <h3 class="empty_content_title">Ainda estamos trabalhando aqui!</h3>
                <p class="empty_content_desc">Nossos editores estão preparando um conteúdo de primeira para você.</p>
            </div>
        </div>
    @else
        <div class="blog_content container content">
            <div class="blog_articles">
                @each('blog.blog-list', $blog, 'post')
            </div>

             {{$blog->links('page')}}
            </div>
    
    @endif
</section>

@endsection