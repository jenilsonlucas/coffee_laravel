@extends('layouts._theme', ["title" => $title])

<h1></h1>
@section('content')
<article class="auth">
    <div class="auth_content container content">
        <header class="auth_header">
            <h1>Cadastre-se</h1>
            <p>Já tem uma conta? <a title="Fazer login!" href="{{url('/entrar')}}">Fazer login!</a></p>
        </header>

        <form class="auth_form" action="{{url('/cadastrar')}}" method="post" enctype="multipart/form-data">
            @csrf
            @if(session('credentials'))
            <div class="ajax_response">
                <div class="message {{ session('message-type') }} icon-warning">
                    {{ session('credentials') }}
                </div>
            </div>
            @elseif($errors->has('first_name'))
                <div class="ajax_response">
                    <div class="message error icon-warning">
                        {{ $errors->first('first_name') }}
                    </div>
                </div>
            @elseif($errors->has('last_name'))
                <div class="ajax_response">
                    <div class="message error icon-warning">
                        {{ $errors->first('last_name') }}
                    </div>
                </div>
            @elseif($errors->has('email'))
                <div class="ajax_response">
                    <div class="message error icon-warning">
                        {{ $errors->first('email') }}
                    </div>
                </div>
            @elseif($errors->has('password'))
                <div class="ajax_response">
                    <div class="message error icon-warning">
                        {{ $errors->first('password') }}
                    </div>
                </div>
            @endif

            <label>
                <div><span class="icon-user">Nome:</span></div>
                <input type="text" name="first_name" value="{{old('first_name')}}" placeholder="Primeiro nome:" required/>
            </label>

            <label>
                <div><span class="icon-user-plus">Sobrenome:</span></div>
                <input type="text" name="last_name" value="{{old('last_name')}}" placeholder="Último nome:" required/>
            </label>

            <label>
                <div><span class="icon-envelope">Email:</span></div>
                <input type="email" name="email" value="{{old('email')}}" placeholder="Informe seu e-mail:" required/>
            </label>

            <label>
                <div class="unlock-alt"><span class="icon-unlock-alt">Senha:</span></div>
                <input type="password" name="password" placeholder="Informe sua senha:" required/>
            </label>

            <button class="auth_form_btn transition gradient gradient-green gradient-hover">Criar conta</button>
        </form>
    </div>
</article>
@endsection