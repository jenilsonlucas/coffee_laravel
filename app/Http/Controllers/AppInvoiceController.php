<?php

namespace App\Http\Controllers;

use App\Models\App_Category;
use App\Models\AppInvoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AppInvoiceController extends Controller
{

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

    public function income(?string $status = null, ?string $category = null, ?string $date = null)
    {
        $data = [
            "status" => $status,
            "category" => $category,
            "date" => $date
        ];

        $categories = App_Category::select("id", "name")
                    ->where("type", "income")
                    ->orderByRaw("order_by, name")
                    ->get();
        
        return view("cafeapp.invoices", [
            "categories" => $categories,
            "user" => Auth::user(),
            "type" => "income",
            "invoices" => (new AppInvoice())
                ->filter(Auth::user(), "income", $data),
            "filter" => (object) [
                "status" => ($data["status"] ?? null),
                "category" => ($data["category"] ?? null),
                "date" => (!empty($data["date"]) ? str_replace("-", "/", $data["date"]) : null)
            ]
        ]);
    }

    public function expense(?string $status = null, ?string $category = null, ?string $date = null)
    {
        $data = [
            "status" => $status,
            "category" => $category,
            "date" => $date
        ];

        $categories = App_Category::select("id", "name")
                    ->where("type", "expense")
                    ->orderByRaw("order_by, name")
                    ->get();
        
        return view("cafeapp.invoices", [
            "categories" => $categories,
            "user" => Auth::user(),
            "type" => "expense",
            "invoices" => (new AppInvoice())
                ->filter(Auth::user(), "expense", $data),
            "filter" => (object) [
                "status" => ($data["status"] ?? null),
                "category" => ($data["category"] ?? null),
                "date" => (!empty($data["date"]) ? str_replace("-", "/", $data["date"]) : null)
            ]
        ]);
    }
}
