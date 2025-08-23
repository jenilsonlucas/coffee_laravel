@extends("cafeapp.layouts._theme")

<h1></h1>

@section("content")
<div class="app_launch_header">
    <form class="app_launch_form_filter app_form" action="{{ url('/app/filter') }}" method="post">
        @csrf    
        <input type="hidden" name="filter" value="{{ $type }}"/>
        
        <select name="status">
            <option value="all" {{ (empty($filter->status) ? "selected" : "") }} >Todas</option>
            <option value="paid" {{ (!empty($filter->status) && $filter->status == "paid" ? "selected" : "") }}>{{ ($type == 'income' ? "Receitas recebidas" : "Despesas pagas") }}</option>
            <option value="unpaid" {{ (!empty($filter->status) && $filter->status == "unpaid" ? "selected" : "") }}>{{ ($type == 'income' ? "Receitas não recebidas" : "Despesas não pagas") }}</option>
        </select>
            

        <select name="category">
            <option value="all">Todas</option>
            @foreach($categories as $category)
                <option 
                {{(!empty($filter->category) && $filter->category == $category->id ? 'selected' : '')}}                
                value="{{$category->id}}">{{$category->name}}</option>      
            @endforeach
        </select>

        <input list="datelist" type="text" class="radius mask-month" name="date"
               placeholder="{{ (!empty($filter->date) ? $filter->date : date('m/Y')) }}">

        <datalist id="datelist">
            @for($range = -2; $range <= 2; $range++) 
                @php
                    $dateRange = date("m/Y", strtotime(date("Y-m-01") . "+{$range}month"));
                @endphp
                <option value="<?= $dateRange; ?>"/>
            
            @endfor
        </datalist>

        <button class="filter radius transition icon-filter icon-notext"></button>
    </form>

    <div class="app_launch_btn {{ $type }} radius transition icon-plus-circle"
         data-modalopen=".app_modal_{{ $type }}">Lançar
        {{ ($type == "income" ? "Receita" : "Despesa") }}
    </div>
</div>

<section class="app_launch_box">
    @if(!$invoices)
        @if(empty($filter->status))
            <div class="message info icon-info">Ainda não existem contas
                a {{ ($type == "income" ? "receber" : "pagar") }}
                . Comece lançando suas {{ ($type == "income" ? "receitas" : "despesas") }}.
            </div>
        @else
            <div class="message info icon-info">Não existem contas
                a {{ ($type == "income" ? "receber" : "pagar") }}
                para o filtro aplicado.
            </div>
        @endif
    @else
        <div class="app_launch_item header">
            <p class="desc">Descrição</p>
            <p class="date">Vencimento</p>
            <p class="category">Categoria</p>
            <p class="enrollment">Parcela</p>
            <p class="price">Valor</p>
        </div>
        @php
        $unpaid = 0;
        $paid = 0;
        @endphp
        @foreach($invoices as $invoice)
            <article class="app_launch_item">
                <p class="desc app_invoice_link transition">
                    <a title="{{ $invoice->description }}"
                       href="{{ url('/app/fatura/'.$invoice->id) }}">{{ Str::limit($invoice->description,
                            30) }} &nbsp;<span class='icon-info icon-notext'></span> </a>
                </p>
                <p class="date">Dia {{ $invoice->due_at->format('d') }}</p>
                <p class="category">{{ $invoice->category->name }}</p>
                <p class="enrollment">
                    @if($invoice->repeat_when == "fixed")
                        <span class="app_invoice_link">
                            <a href="{{ url('/app/fatura/'.$invoice->invoice_of) }}" class="icon-exchange"
                               title="Controlar Conta Fixa">Fixa</a>
                        </span>
                    @elseif($invoice->repeat_when == 'enrollment')
                        <span class="app_invoice_link">
                            <a href="{{ url('/app/fatura/'.$invoice->invoice_of) }}"
                               title="Controlar Parcelamento">{{ str_pad($invoice->enrollemnt_of, 2, 0,
                                    0) }} de {{ str_pad($invoice->enrollments, 2, 0, 0) }}</a>
                        </span>
                    @else
                        <span class="icon-calendar-check-o">Única</span>
                    @endif
                </p>
                <p class="price">
                    <span>R$</span>
                    <span>{{ number_format($invoice->value, 2, ',', '.') }}</span>
                    @if($invoice->status == 'unpaid')
                        @php
                            $unpaid += $invoice->value
                        @endphp
                        <span class="check {{ $type }} icon-thumbs-o-down transition"
                              data-toggleclass="active icon-thumbs-o-down icon-thumbs-o-up"
                              data-onpaid="{{ url('/app/onpaid') }}"
                              data-date="{{ ($filter->date ?? date('m/Y')) }}"
                              data-invoice="{{ $invoice->id }}"></span>
                    @else
                        @php 
                            $paid += $invoice->value
                        @endphp
                            <span class="check {{ $type }} icon-thumbs-o-up transition"
                              data-toggleclass="active icon-thumbs-o-down icon-thumbs-o-up"
                              data-onpaid="{{ url('/app/onpaid') }}"
                              data-date="{{ ($filter->date ?? date('m/Y')) }}"
                              data-invoice="{{ $invoice->id }}"></span>
                    @endif
                </p>
            </article>
        @endforeach

        <div class="app_launch_item footer">
            <p class="icon-thumbs-o-down j_total_unpaid">R$ {{ number_format($unpaid, 2, ',', '.') }}</p>
            <p class="icon-thumbs-o-up j_total_paid">R$ {{ number_format($paid, 2, ',', '.') }}</p>
        </div>
    @endif
</section>
@endSection