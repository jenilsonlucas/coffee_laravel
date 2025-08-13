<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppInvoice extends Model
{

    use HasFactory;

    protected $fillable = [
        "user_id",
        "wallet_id",
        "category_id",
        "description",
        "type",
        "value",
        "due_at",
        "repeat_when"
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
}
