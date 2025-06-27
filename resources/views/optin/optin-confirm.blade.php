@extends('layouts._theme')

<h1></h1>
@section('content')
<article class="optin_page">
    <div class="container content">
        <div class="optin_page_content">
            <img alt="Confirmar Cadastro" title="Confirmar Cadastro"
                 src="{{asset('/assets/images/optin-confirm.jpg')}}"/>

            <h1>Falta pouco! Confirme seu cadastro.</h1>
            <p>Enviamos um link de confirmação para seu e-mail. Acesse e siga as instruções para concluir seu cadastro
                e comece a controlar com o CaféControl</p>

        </div>
    </div>
</article>

@endsection