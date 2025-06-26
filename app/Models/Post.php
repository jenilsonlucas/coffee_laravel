<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes tha are mass assignable
     * @var list<string>
     */
    protected $fillable = [
        "user_id", 
        "category_id",
        "title",
        "uri",
        "subtitle",
        "content",
        "cover",
        "views",
        "status",
        "post_at"
    ];


    /**
     * get the user that owns the post
     */

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * get the category that owns the post
     */
    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    protected function casts():array
    {
        return [
            'post_at' => 'datetime'
        ];
    }
}
