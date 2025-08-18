<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;

class AppInvoice extends Model
{

    use HasFactory;

    protected $fillable = [
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
        "enrollemnts",
        "enrollemnts_of",
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


    protected function casts(): array
    {
        return [
            "due_at" => 'datetime' 
        ];
    }
}
