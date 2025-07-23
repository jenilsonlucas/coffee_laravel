<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class App_Wallet extends Model
{

    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'wallet',
        'free'
    ];

    protected $table = "appwallets";
    /**
     * get the walllet that owns the user
     */
    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
