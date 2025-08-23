<div class="app_modal" data-modalclose="true">
    <!--INCOME-->
   @php
        $wallets = (new \App\Models\App_Wallet())
            ->select("id", "wallet")
            ->where("user_id", Auth::id())
            ->orderBy("wallet")
            ->get();
    @endphp

    @include('cafeapp.includes.invoice', [
        'type' => 'income',
        'wallets' => $wallets,
        'categories' => (new \App\Models\App_Category())
            ->select('id', 'name')
            ->where('type', 'income')
            ->orderByRaw('order_by, name')
            ->get()
    ])

    @include('cafeapp.includes.invoice', [
        'type' => 'expense',
        'wallets' => $wallets,
        'categories' => (new \App\Models\App_Category())
            ->select("id", "name")
            ->where("type", "expense")
            ->orderByRaw('order_by, name')
            ->get()
    ])
        
</div>