<?php

namespace App\Http\Controllers;

use App\Models\AppInvoice;
use App\Models\Post;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use stdClass;

class AppController extends Controller
{
    public function __construct(
        protected User $user
    ) {
        $this->user = Auth::user();
    }

    public function home()
    {
        //CHART
        $dateChart = [];

        for ($month = -4; $month <= 0; $month++) {
            $dateChart[] = date("m/Y", strtotime("{$month}month"));
        }


        $chartData = new stdClass;
        $chartData->categories = "'" . implode(",", $dateChart) . "'";
        $chartData->expense = "0,0,0,0,0";
        $chartData->income = "0,0,0,0,0";
    
        $chart = AppInvoice::from(DB::raw('app_invoices AS main'))
            ->selectRaw("
                EXTRACT(YEAR FROM main.due_at) AS due_year,
                EXTRACT(MONTH FROM main.due_at) AS due_month,
                TO_CHAR(main.due_at, 'MM/YYYY') AS due_date,
                (
                    SELECT SUM(value)
                    FROM app_invoices AS sub
                    WHERE sub.user_id = ?
                    AND sub.status = ?
                    AND sub.type = 'income'
                    AND EXTRACT(YEAR FROM sub.due_at) = EXTRACT(YEAR FROM main.due_at)
                    AND EXTRACT(MONTH FROM sub.due_at) = EXTRACT(MONTH FROM main.due_at)
                ) AS income,
                (
                    SELECT SUM(value)
                    FROM app_invoices AS sub
                    WHERE sub.user_id = ?
                    AND sub.status = ?
                    AND sub.type = 'expense'
                    AND EXTRACT(YEAR FROM sub.due_at) = EXTRACT(YEAR FROM main.due_at)
                    AND EXTRACT(MONTH FROM sub.due_at) = EXTRACT(MONTH FROM main.due_at)
                ) AS expense
            ", [$this->user->id, 'paid', $this->user->id, 'paid'])
            ->where("main.user_id", $this->user->id)
            ->where("main.status", "paid")
            ->whereRaw("main.due_at >= NOW() - INTERVAL '4 months'")
            ->groupBy(DB::raw("main.due_at"))
            ->limit(5)
            ->get();      
            
        if ($chart->isNotEmpty()) {
            $chartCategories = [];
            $chartIncome = [];
            $chartExpense = [];

            foreach ($chart as $chartItem) {
                $chartCategories[] = $chartItem->due_date;
                $chartIncome[] = $chartItem->income;
                $chartExpense[] = $chartItem->expense;
            }
            
            $chartData->categories = "'" . implode("','", $chartCategories) . "'";
            $chartData->income = implode(",", array_map("abs", $chartIncome));
            $chartData->expense = implode(",", array_map("abs", $chartExpense));
        }
        //END CHART

        //INCOME && EXPENSE
        $income = AppInvoice::where("user_id", $this->user->id)
        ->where("type", "income")
        ->where("status", "unpaid")
        ->whereRaw("due_at <= NOW() + INTERVAL '1 months'")
        ->orderBy("due_at", "ASC")
        ->get();

        $expense = AppInvoice::where("user_id", $this->user->id)
        ->where("type", "expense")
        ->where("status", "unpaid")
        ->whereRaw("due_at <= NOW() + INTERVAL '1 months'")
        ->orderBy("due_at", "ASC")
        ->get();

        //END INCOME && EXPENSE

        //WALLET
        $wallet = AppInvoice::selectRaw(
            "
               (SELECT SUM(value) FROM app_invoices WHERE user_id = ? AND status = ? AND type = 'income') AS income,
               (SELECT SUM(value) FROM app_invoices WHERE user_id = ? AND status = ? AND type = 'expense') AS expense  
            "
        , [$this->user->id, "paid", $this->user->id, "paid"])
        ->where("user_id", $this->user->id)
        ->where("status", "paid")
        ->first();

        if ($wallet) {
            $wallet->wallet = $wallet->income - $wallet->expense;
        }

        //END WALLET

        //POSTS
        $posts = Post::orderBy("post_at", "DESC")
        ->limit(4)
        ->get();   

        return view("cafeapp.home", compact("chartData", "income", "expense", "wallet", "posts"));
    }

    public function filter(Request $request)
    {
        $status = (!empty($request->input("status")) ? $request->input("status") : "all");
        $category = (!empty($request->input("category")) ? $request->input("category") : "all");
        $date = (!empty($request->input("date")) ? $request->input("date") : date("m/Y"));
        
        list($m, $y) = explode("/", $date);
        $m = ($m >= 1 && $m <= 12 ? $m : date("m"));
        $y = ($y <= date("Y", strtotime("+10year")) ? $y : date("Y", strtotime("+10year")));
    
        $redirect = ($request->input("filter") == "income" ? "receber" : "pagar");
        return Response()->json([
            "redirect" => url("/app/{$redirect}/{$status}/{$category}/{$m}-{$y}")
        ]);
    }
}
