<?php

namespace App\Models;

use Database\Factories\AppCategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class App_Category extends Model
{
    use HasFactory;


    protected $table = "appCategories";

    protected $fillable = [
        "sub_of",
        "name", 
        "type",
        "order_by"
    ];
    /**
     * get the invoices for the category
     */
    public function invoices():HasMany
    {
        return $this->hasMany(AppInvoice::class)->chaperone();
    }

    public static function newFactory()
    {
        return AppCategoryFactory::new();
    }
    
}
