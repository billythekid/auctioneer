<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    public $fillable = ['title', 'slug'];

    public function items()
    {
        return $this->belongsToMany(Item::class);
    }
}
