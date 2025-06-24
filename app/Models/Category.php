<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    

    /**
     * the attributes that are mass assignable
     * @var list<string>
     */
    protected $fillable = [
        "title",
        "uri",
        "description",
        "cover",
        "type"
    ];


    /**
     * get the post for the category
     */

    public function posts():HasMany
    {
        return $this->hasMany(Post::class);
    }
}
