<div class="balance {{ ($invoice->type == 'income' ? 'positive' : 'negative'); }}">
    <p class="desc">
        <b class="app_invoice_link transition">
            <a title="{{ $invoice->description }}"
               href="{{ url('app/fatura/'. $invoice->id) }}">
                {{ Str::limit($invoice->description, 10) }} &nbsp;<span class='icon-info icon-notext'></span> 
            </a></b>

        @php
        $now = new DateTime();
        $due = new DateTime($invoice->due_at);
        $expire = $now->diff($due);
        $s = ($expire->days == 1 ? "" : "s");
        @endphp

        @if(!$expire->days && $expire->invert)
            <span class="date" style="color: var(--color-yellow);">Hoje</span>
        @elseif(!$expire->invert)
            <span class="date">Em {{ ($expire->days <= 1 ? "1 dia" : "{$expire->days} dias") }}</span>
        @else
            <span class="date"
                  style="color: var(--color-red);">Há {{ ($expire->days <= 1 ? "1 dia" : "{$expire->days} dias") }}</span>
        @endif
    </p>
    <p class="price">
        R$&nbsp;{{ number_format($invoice->value, 2, ',', '.') }}

        @if($invoice->status == 'unpaid')
            <span class="check {{ $invoice->type }} icon-thumbs-o-down transition"
                  data-toggleclass="active icon-thumbs-o-down icon-thumbs-o-up"
                  data-onpaid="{{ url('/app/onpaid') }}"
                  data-invoice="{{ $invoice->id }}"></span>
        @else
            <span class="check {{ $invoice->type }} icon-thumbs-o-up transition"
                  data-toggleclass="active icon-thumbs-o-down icon-thumbs-o-up"
                  data-onpaid="{{ url('/app/onpaid') }}"
                  data-invoice="{{ $invoice->id }}"></span>
        @endif
    </p>
</div>