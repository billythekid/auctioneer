<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'description', 'price', 'active', 'end_time', 'slug',
    ];

    protected $dates = [
        'end_time', 'deleted_at',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function createFromTitle(string $title)
    {
        $item        = new static;
        $slug        = str_slug($title);
        $item->title = $title;
        $item->slug  = $slug;
        $i           = 1;
        while (Item::where('slug', $slug)->count() > 0)
        {
            $i++;
            $slug = $item->slug . "-{$i}";
        }
        $item->slug = $slug;

        return $item;
    }

    public function getPriceAttribute($value)
    {
        return number_format($value / 100, 2);
    }


    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    public function currentBid()
    {
        return $this->bids()->orderBy('amount', 'desc')->first();
    }

    public function highBidder()
    {
        return $this->currentBid()->user->name ?? 'No High Bidder';
    }


}
