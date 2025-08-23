<?php

namespace App\Models;

use DateInterval;
use DatePeriod;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class AppInvoice extends Model
{

    use HasFactory;

    protected $fillable = [
        "id",
        "user_id",
        "wallet_id",
        "category_id",
        "invoice_of",
        "description",
        "type",
        "value",
        "currency",
        "due_at",
        "repeat_when",
        "period",
        "enrollments",
        "enrollemnt_of",
        "status"
    ];


    /**
     * get the user that own the invoice
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * get the category that owns the invoice
     */
    public function category():BelongsTo
    {
        return $this->belongsTo(App_Category::class);
    }

    /**
     * get the wallet that owns the invoice
     */
    public function wallet():BelongsTo
    {
        return $this->belongsTo(AppInvoice::class);
    }

    /**
     * @param User $user
     * @param int $afterMonths
     */
    public function fixed(User $user, int $afterMonths = 1): void
    {
        $fixed = $this->where("user_id", $user->id)
                    ->where("status", "paid")
                    ->whereRaw("type IN('fixed_income', 'fixed_expense')")
                    ->get();
        if($fixed->isEmpty())
            return;
        
        foreach($fixed as $fixedItem){
            $invoice = $fixedItem->id;
            $start = new DateTime($fixedItem->due_at);
            $end = new DateTime("+{$afterMonths}month");

            if($fixedItem->period == "month"){
                $interval = new DateInterval("P1M");
            }

            if($fixedItem->period == "year"){
                $interval = new DateInterval("P1Y");
            }

            $period = new DatePeriod($start, $interval, $end);
            
            foreach($period as $item)
            {
                $getFixed = $this->where("user_id", $user->id)
                                ->where("invoice_of", $fixedItem->id)
                                ->whereRaw("EXTRACT(YEAR FROM due_at) = {$item->format('Y')}")
                                ->whereRaw("EXTRACT(MONTH FROM due_at) = {$item->format('m')}")
                                ->get();            
                if($getFixed->isEmpty()){
                    $newItem = $fixedItem->replicate();
                    $newItem->invoice_of = $invoice;
                    $newItem->type = str_replace("fixed_", "", $newItem->type);
                    $newItem->due_at = $item->format("Y-m-d");
                    $newItem->status = ($item->format("Y-m-d") <= date("Y-m-d") ? "paid" : "unpaid");
                    $newItem->save();
                }
          }
        }
    }

    /**
     * @param User $user
     * @param string $type
     * @param array|null $filter
     * @param int|null  $limit
     * @return colletion
     */
    public function filter(User $user, string $type, ?array $filter, ?int $limit = null)
    {
        $status = (!empty($filter["status"]) && $filter["status"] == "paid" ? "paid" : (!empty($filter["status"]) 
        && $filter["status"] == "unpaid" ? "unpaid" : null));
        
        $category = (!empty($filter["category"]) && $filter["category"] != "all" ? $filter["category"] : null);
       
        $due_year = (!empty($filter["date"]) ? explode("-", $filter["date"])[1] : date("Y"));
        $due_month = (!empty($filter["date"]) ? explode("-", $filter["date"])[0] : date("m"));

        $due = $this->where("user_id", $user->id)
                ->where("type", $type) 
                ->whereRaw("EXTRACT(YEAR FROM due_at) = {$due_year}")
                ->whereRaw("EXTRACT(MONTH FROM due_at) = {$due_month}");
        if($status) 
            $due->where("status", $status);
        if($category)
            $due->where("category_id", $category);
        if($limit)
            $due->limit($limit);

        return $due->get();
    }


    public function balance(User $user, int $year, int $month, string $type): ?object
    {
        $onpaid = $this->selectRaw("
             (SELECT SUM(value) FROM app_invoices where user_id = ? AND type = ? 
                AND EXTRACT(YEAR FROM due_at) = ? AND EXTRACT(MONTH FROM due_at) = ? AND status = 'paid' ) AS paid,
             (SELECT SUM(value) FROM app_invoices where user_id = ? AND type = ? 
                AND EXTRACT(YEAR FROM due_at) = ? AND EXTRACT(MONTH FROM due_at) = ? AND status = 'unpaid' ) AS unpaid
            ",[$user->id, $type, $year, $month, $user->id, $type, $year, $month])
            ->where("user_id", $user->id)
            ->first();

  
        if(empty($onpaid)){
            return null;
        }

        return (object)[
            "paid" => number_format(($onpaid->paid ?? 0), 2, ',', '.'),
            "unpaid" => number_format(($onpaid->unpaid ?? 0), 2, ',', '.')
        ];
    }

    protected function casts(): array
    {
        return [
            "due_at" => 'datetime' 
        ];
    }
}
