<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('shared/styles/styles.css') }}"/>
    <link rel="stylesheet" href="{{ asset('shared/styles/boot.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/cafeapp/assets/css/style.css') }}"/>
    <link rel="stylesheet" href="{{ asset('/cafeapp/assets/css/message.css') }}"/>
    <link rel="shortcut icon" href="{{ asset('/cafeapp/assets/images/favicon.png') }}" type="image/x-icon">
</head>
<body>
    <div class="ajax_load">
    <div class="ajax_load_box">
    <div class="ajax_load_box_circle"></div>
    <p class="ajax_load_box_title">Aguarde, carregando...</p>
    </div>
</div>

<div class="app">
    <header class="app_header">
        <h1><a class="icon-coffee transition" href="{{url('/app')}}" title="CaféApp">CaféApp</a></h1>
        <ul class="app_header_widget">
            <li class="radius icon-filter wallet"> {{(session()->has("walletfilter") ? (new \Source\Models\CafeApp\AppWallet())->where("name", session()->walletfilter)->wallet : "Saldo Geral")}}
                <ul>
                    @session("walletfilter")
                        <li class="radius icon-briefcase" data-walletfilter="{{ url('/app/dash') }}"
                            data-wallet="all">Saldo Geral
                        </li>
                    @endsession

                    <?php

                    $userId = Auth::id();
                    $wallets = (new \App\Models\App_Wallet())
                        ->where("user_id", $userId)
                        ->orderBy("wallet")
                        ->get();

                    foreach ($wallets as $walletIt):
                        if (!session()->has("walletfilter") || $walletIt->id != session()->walletfilter):
                            ?>
                            <li class="radius icon-suitcase" data-walletfilter="{{ url('/app/dash')}}"
                                data-wallet="{{ $walletIt->id }}"><{{ $walletIt->wallet}}</li>
                        <?php
                        endif;
                    endforeach;
                    ?>
                </ul>
            </li>
            <li data-mobilemenu="open" class="app_header_widget_mobile radius transition icon-menu icon-notext"></li>
        </ul>
    </header>

    <div class="app_box">
        <nav class="app_sidebar radius box-shadow">
            <div data-mobilemenu="close"
                 class="app_sidebar_widget_mobile radius transition icon-error icon-notext"></div>

            <div class="app_sidebar_user app_widget_title">
                <span class="user">
                        @if(Auth::user()->photo)
                            <img class="rounded" alt="{{ Auth::user()->first_name }}" title="{{ Auth::user()->first_name }}"
                                src="{{ asset(Auth::user()->photo) }}"/>
                        @else
                            <img class="rounded" alt="{{ Auth::user()->first_name }}" title="{{ Auth::user()->first_name}}"
                                src="{{ asset('/assets/images/avatar.jpg')}}"/>
                        @endif
                        <span>{{ Auth::user()->first_name }}</span>
                </span>
                <span class="plan radius">FREE</span>
            </div>
            
            @include("cafeapp.includes.sidebar") 
        </nav>

        <main class="app_main">
         @yield("content")
        </main>
    </div>

    <footer class="app_footer">
        <span class="icon-coffee">
            CaféApp - Desenvolvido na formação FSPHP<br>
            &copy; UpInside - Todos os direitos reservados
        </span>
    </footer>

    @include("cafeapp.includes.modals")
</div>
<script src="{{ asset('/shared/scripts/jquery.min.js')}}"></script>
<script src="{{ asset('/shared/scripts/jquery.form.js')}}"></script>
<script src="{{ asset('/shared/scripts/jquery-ui.js')}}"></script>
<script src="{{ asset('/shared/scripts/jquery.mask.js')}}"></script>
<script src="{{ asset('/shared/scripts/highcharts.js')}}"></script>
<script src="{{ asset('/cafeapp/assets/js/scripts.js') }}"></script>
@yield("scripts")

</body>
</html>