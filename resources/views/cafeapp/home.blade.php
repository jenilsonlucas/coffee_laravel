@extends("cafeapp.layouts._theme")
 <h1></h1>
@section("content")

<div class="app_main_box">
        <section class="app_main_left">
            <article class="app_widget">
                <header class="app_widget_title">
                    <h2 class="icon-bar-chart">Controle</h2>
                </header>
                <div id="control"></div>
            </article>

            <div class="app_main_left_fature">
                <article class="app_widget app_widget_balance">
                    <header class="app_widget_title">
                        <h2 class="icon-calendar-minus-o">À receber:</h2>
                    </header>
                    <div class="app_widget_content">
                        @if(!empty($income))
                            @foreach($income as $incomeItem)
                                @include("views.balance", ["invoice" => $incomeItem->data()])
                            @endforeach
                        @else
                            <div class="message success al-center icon-check-square-o">
                                No momento, não existem contas a receber.
                            </div>
                        @endif
                        <a href="{{ url('app/receber')}}" title="Receitas"
                           class="app_widget_more transition">+ Receitas</a>
                    </div>
                </article>

                <article class="app_widget app_widget_balance">
                    <header class="app_widget_title">
                        <h2 class="icon-calendar-check-o">À pagar:</h2>
                    </header>
                    <div class="app_widget_content">
                        @if (!empty($expense))
                            @foreach($expense as $expenseItem)
                                @include("views/balance", ["invoice" => $expenseItem->data()])
                            @endforeach
                        @else
                            <div class="message error al-center icon-check-square-o">
                                No momento, não existem contas a pagar.
                            </div>
                        @endif
                        <a href="{{ url('app/pagar')}}" title="Despesas"
                           class="app_widget_more transition">+ Despesas</a>
                    </div>
                </article>
            </div>
        </section>

        <section class="app_main_right">
            <ul class="app_widget_shortcuts">
                <li class="income radius transition" data-modalopen=".app_modal_income">
                    <p class="icon-plus-circle">Receita</p>
                </li>
                <li class="expense radius transition" data-modalopen=".app_modal_expense">
                    <p class="icon-plus-circle">Despesa</p>
                </li>
            </ul>

             <article
                    class="app_flex <?= (!empty($wallet->wallet) && $wallet->wallet >= 0 ? "gradient-green" : "gradient-red"); ?>">
                <header class="app_flex_title">
                    <h2 class="icon-money">Saldo</h2>
                </header>

                <p class="app_flex_amount">R$ {{ ($wallet->wallet ?? 0) }} </p>
                <p class="app_flex_balance">
                    <span class="income">Receitas: R$ {{ ($wallet->income ?? 0) }}</span>
                    <span class="expense">Despesas: R$ {{ ($wallet->expense ?? 0) }}</span>
                </p>
            </article>

            <section class="app_widget app_widget_blog">
                <header class="app_widget_title">
                    <h2 class="icon-graduation-cap">Aprenda:</h2>
                </header>
                <div class="app_widget_content">
                    @if(!empty($posts))
                        @foreach($posts as $post)
                            <article class="app_widget_blog_article">
                                <div class="thumb">
                                    <img alt="{{ $post->title }}" title="{{ $post->title }}"
                                         src="{{ asset($post->cover) }}"/>
                                </div>
                                <h3 class="title">
                                    <a target="_blank" href="{{ url('/blog/{$post->uri}') }}"
                                       title="{{ $post->title }}">{{  Str::limit($post->title, 50) }}</a>
                                </h3>
                            </article>
                        @endforeach
                    @endif
                    <a target="_blank" href="{{ url('/blog') }}" title="Blog"
                       class="app_widget_more transition">Ver Mais...</a>
                </div>
            </section>
        </section>
    </div> 
@endsection
@section("scripts")
    <script type="text/javascript">
        $(function () {
            Highcharts.setOptions({
                lang: {
                    decimalPoint: ',',
                    thousandsSep: '.'
                }
            });

            var chart = Highcharts.chart('control', {
                chart: {
                    type: 'areaspline',
                    spacingBottom: 0,
                    spacingTop: 5,
                    spacingLeft: 0,
                    spacingRight: 0,
                    height: (9 / 16 * 100) + '%'
                },
                title: null,
                xAxis: {
                    categories: [<?= $chartData->categories;?>],
                    minTickInterval: 1
                },
                yAxis: {
                    allowDecimals: true,
                    title: null,
                },
                tooltip: {
                    shared: true,
                    valueDecimals: 2,
                    valuePrefix: 'R$ '
                },
                credits: {
                    enabled: false
                },
                plotOptions: {
                    areaspline: {
                        fillOpacity: 0.5
                    }
                },
                series: [{
                    name: 'Receitas',
                    data: [<?= $chartData->income;?>],
                    color: '#61DDBC',
                    lineColor: '#36BA9B'
                }, {
                    name: 'Despesas',
                    data: [<?= $chartData->expense;?>],
                    color: '#F76C82',
                    lineColor: '#D94352'
                }]
            });

            $("[data-onpaid]").click(function (e) {
                setTimeout(function () {
                    $.post('<?= url("/app/dash");?>', function (callback) {
                        if (callback.chart) {
                            chart.update({
                                xAxis: {
                                    categories: callback.chart.categories
                                },
                                series: [{
                                    data: callback.chart.income
                                }, {
                                    data: callback.chart.expense
                                }]
                            });
                        }

                        if (callback.wallet) {
                            $(".app_wallet").removeClass("gradient-red gradient-green").addClass(callback.wallet.status);
                            $(".app_flex_amount").text("R$ " + callback.wallet.wallet);
                            $(".app_flex_balance .income").text("R$ " + callback.wallet.income);
                            $(".app_flex_balance .expense").text("R$ " + callback.wallet.expense);
                        }
                    }, "json");
                }, 200);
            });
        });
    </script>
@endsection