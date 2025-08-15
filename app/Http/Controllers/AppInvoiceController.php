<?php

namespace App\Http\Controllers;

use App\Models\AppInvoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AppInvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(AppInvoice $appInvoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AppInvoice $appInvoice)
    {
        //
    }

    public function launch(Request $request)
    {
        if(!empty($request->input('enrollments')) && ($request->input('enrollments') < 2 || $request->input('enrollments') > 420)) {
            return Response()->json([
                "message" => "Oops {Auth::user()->first_name}! Para lançar o níero de parcela deve ser entre 2 e 420"
            ]);
        }

        $status = (strtotime($request->input("due_at")) <= strtotime(date("Y-m-d")) ? "paid" : "unpaid");
        
        $invoice = AppInvoice::create([
            "user_id" => Auth::id(),
            "wallet_id" => $request->input("wallet"),
            "category_id" => $request->input("category"),
            "description" => $request->input("description"),
            "type" => ($request->input("repeat_when") == "fixed" ? "fixed_{$request->input("type")}" : $request->input("type")),
            "value" => str_replace([".", ","], ["", "."], $request->input("value")),
            "currency" => $request->input("currency"),
            "due_at" => $request->input("due_at"),
            "repeat_when" => $request->input("repeat_when"),
            "period" => ($request->input("period") ?? "month"),
            "enrollments" => ($request->input("enrollments") ?? 1),
            "enrollments_of" => 1,
            "status" => ($request->input("repeat_when") == "fixed" ? "paid" : $status)
        ]);

        return Response()->json([
            "reload" => true
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AppInvoice $appInvoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AppInvoice $appInvoice)
    {
        //
    }
}
