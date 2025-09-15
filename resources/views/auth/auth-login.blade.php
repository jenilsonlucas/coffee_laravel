@extends('layouts._theme')
<h1></h1>
@section('content')
<article class="auth">
    <div class="auth_content container content">
        <header class="auth_header">
            <h1>Fazer Login</h1>
            <p>Ainda não tem conta? <a title="Cadastre-se!" href="{{url('/cadastrar')}}">Cadastre-se!</a></p>
        </header>

        <form class="auth_form" action="{{url('/entrar')}}" method="post" enctype="multipart/form-data">
            @csrf
            @if(session('credentials'))
            <div class="ajax_response">
                <div class="message {{session('message-type')}} icon-warning">{{session('credentials')}}</div>
            </div>
            @endif
            @error('email')
            <div class="ajax_response">
                <div class="message error icon-warning">{{$message}}</div>
            </div>
            @enderror
            @error('password')
            <div class="ajax_response">
                <div class="message error icon-warning">{{$message}}</div>
            </div>
            @enderror
            <label>
                <div><span class="icon-envelope">Email:</span></div>
                <input type="email" name="email" value="{{old('email')}}" placeholder="Informe seu e-mail:"
                    required />
            </label>

            <label>
                <div>
                    <span class="icon-unlock-alt">Senha:</span>
                    <span><a title="Esqueceu a senha?" style="pointer-events: none; cursor:default;" href="{{url('/recuperar')}}">Esqueceu a senha?</a></span>
                </div>
                <input type="password" name="password" placeholder="Informe sua senha:" required />
            </label>

            <label class="check">
                <input type="checkbox" @checked(old('save')) name="remember" />
                <span>Lembrar dados?</span>
            </label>

            <button class="auth_form_btn transition gradient gradient-green gradient-hover">Entrar</button>
        </form>
    </div>
</article>
@endsection