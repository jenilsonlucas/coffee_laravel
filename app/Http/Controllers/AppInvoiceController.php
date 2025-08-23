<?php

namespace App\Http\Controllers;

use App\Models\App_Category;
use App\Models\App_Wallet;
use App\Models\AppInvoice;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

use function PHPSTORM_META\type;

class AppInvoiceController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        (new AppInvoice())->fixed(Auth::user(), 3);
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
            "enrollemnt_of" => 1,
            "status" => ($request->input("repeat_when") == "fixed" ? "paid" : $status)
        ]);

        if($invoice->repeat_when == "enrollment"){
       
            $invoiceOf = $invoice->id;

            for($enrollment = 1; $enrollment < $invoice->enrollments; $enrollment++){
                $newInvoice = $invoice->replicate();
                $newInvoice->invoice_of = $invoiceOf;
                $newInvoice->due_at = date("Y-m-d", strtotime($request->input("due_at") . "+{$enrollment}month"));
                $newInvoice->status = (date($newInvoice->due_at) <= date("Y-m-d") ? "paid" : "unpaid");
                $newInvoice->enrollemnt_of = $enrollment + 1;
                $newInvoice->save();
            }
        }

        if ($invoice->type == "income") {
            $this->message->success("Receita lançada com sucesso. Use o filtro para controlar.")->render();
        } else {
            $this->message->success("Despesa lançada com sucesso. Use o filtro para controlar.")->render();
        }

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

    public function fixas()
    {
        return view("cafeapp.recurrences", [
            "invoices" => AppInvoice::where("user_id", Auth::id())
                        ->whereRaw("type IN('fixed_income', 'fixed_expense')")
                        ->get()
        ]);
    }

    public function onpaid(Request $request)
    {
        $invoice = AppInvoice::where("user_id", Auth::id())
                ->where("id", $request->input("invoice"))->first();
        
   
        if(empty($invoice)){
            $this->message->warning("Ooops! Ocorreu um erro ao actualizar o lançamento :/")->flash();
            return Response()->json([
                "reload" => true
            ]);
        }

        $invoice->status = ($invoice->status == "paid" ? "unpaid" : "paid");
        $invoice->save();
        $m = date("m");
        $y = date("Y");

        if($request->input("date")){
            list($m, $y) = explode("/", $request->input("date"));
        }

        return Response()->json([
            "onpaid" => (new AppInvoice())->balance(Auth::user(), $y, $m, $invoice->type)
        ]); 
    }


    public function invoice(AppInvoice $invoice)
    {
        return View("cafeapp.invoice", [
            "invoice" => $invoice,
            "wallets" => App_Wallet::select("id", "wallet")
                ->where("user_id", Auth::id())
                ->orderBy("wallet")
                ->get(),
            "categories" => App_Category::where("type", $invoice->category->type)
                    ->orderBy("order_by")
                    ->get()
        ]);
    }


    public function update(Request $request, AppInvoice $invoice)
    {
        $userName = Auth::user()->first_name;
        if(empty($invoice))
        {
            return Response()->json([
                "message" => $this->message->warning("Ooops! Não foi possivel carregar a fatura {$userName}.
                 Você pode tentar novamente")->render()
            ]);
        }

        if($request->input("due_day") < 1 || $request->input("due_day") > $dayOfMonth = date("t", strtotime($invoice->due_at))){
            return Response()->json([
                "message" => $this->message->warning("O vencimento deve ser entre dia 1 e dia {$dayOfMonth} para este mês.")->render()
            ]);
        }

        $data = filter_var_array($request->all(), FILTER_SANITIZE_SPECIAL_CHARS);
        $due_day = date("Y-m", strtotime($invoice->due_at)) . '-' . $data["due_day"];
        $invoice->category_id = $data["category"];
        $invoice->description = $data["description"];
        $invoice->due_at = date("Y-m-d", strtotime($due_day));
        $invoice->value = str_replace(['.', ','], ["", '.'], $data["value"]);
        $invoice->wallet_id = $data["wallet"];
        $invoice->status = $data["status"];
        
        try{
            $invoice->save();
        }catch(Exception $e){
            return Response()->json([
                "message" => $this->message->warning($e->getMessage())->render() 
            ]);
        }

        $invoiceOf = AppInvoice::where("user_id", Auth::id())
                ->where("invoice_of", $invoice->id)->get();
        
       if($invoiceOf->isNotEmpty() && in_array($invoice->type, ["fixed_income", "fixed_expense"]))
        {
            foreach($invoiceOf as $invoiceItem)
            {
                if($request->input("status") == "unpaid"  && $invoiceItem->status == "unpaid")
                {
                    $invoiceItem->destroy();
                }else {
                    $due_day = date("Y-m", strtotime($invoiceItem->due_at)) . "-" . $request->input("due_day");
                    $invoiceItem->category = $request->input("category");
                    $invoiceItem->description = $request->input("description");
                    $invoiceItem->wallet_id = $request->input("wallet");

                    if($invoiceItem->status = "unpaid"){
                        $invoiceItem->value = str_replace([".", ','], ["", "."], $request->input("value"));
                        $invoiceItem->due_at = date("Y-m-d",strtotime($due_day));
                    }

                    $invoiceItem->save();
                }
            }
        } 
        
        return Response()->json([
            "message" => $this->message->success("Pronto {$userName}, a actualização foi efetuada com sucesso")->render()
        ]);
    }


    public function destroy(AppInvoice $invoice)
    {
        $invoice->delete();

        return Response()->json([
            "redirect" => url("/app")
        ]);
    }
}
