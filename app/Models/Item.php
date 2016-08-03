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

    /**
     * Define what our unique thing is when we implicitly bind a route param to the model
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * The user who created this listing
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Named constructor which takes the title and creates the title and slug parameters
     * of the item. This avoids slug duplication.
     * @param string $title
     * @return static
     */
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


    /**
     * The categories this item belongs to.
     * @return BelongsToMany
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    /**
     * All bids that have been placed on this item.
     * @return HasMany
     */
    public function bids()
    {
        return $this->hasMany(Bid::class);
    }

    /**
     * The highest bid by bid amount.
     * @return Bid
     */
    public function currentBid()
    {
        return $this->bids()->orderBy('amount', 'desc')->first() ;
    }

    /**
     * The user name of the user who placed the highest bid.
     * @return string
     */
    public function highBidder()
    {
        return $this->currentBid()->user->name ?? 'No High Bidder';
    }

    /**
     * Other items that have not yet ended that share a category with this item.
     * @return Collection
     */
    public function relatedItems()
    {
        $categories   = $this->categories->modelKeys();
        $relatedItems = Item::whereHas('categories', function ($query) use ($categories)
        {
            $query->whereIn('categories.id', $categories);
        })->where('id', '<>', $this->id)->where('end_time', '>', Carbon::now())->get();

        return $relatedItems;
    }

    /**
     * Has this item's listing ended meaning is it now later than the item's end time.
     * @return bool
     */
    public function hasEnded()
    {
        return ($this->end_time < Carbon::now());
    }
}
