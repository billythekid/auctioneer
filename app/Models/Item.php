<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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


    public function categories() : BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function bids() : HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function currentBid() : Bid
    {
        return $this->bids()->orderBy('amount', 'desc')->first();
    }

    public function highBidder() : string
    {
        return $this->currentBid()->user->name ?? 'No High Bidder';
    }

    public function relatedItems() : Collection
    {
        $categories   = $this->categories->modelKeys();
        $relatedItems = Item::whereHas('categories', function ($query) use ($categories)
        {
            $query->whereIn('categories.id', $categories);
        })->where('id', '<>', $this->id)->where('end_time', '>', Carbon::now())->get();

        return $relatedItems;
    }

    public function hasEnded() : bool
    {
        return ($this->end_time < Carbon::now());
    }
}
