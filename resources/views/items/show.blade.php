@extends('layouts.app')

@section('content')
    <div class="container" id="main-content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1>
                            {{ $item->title }}
                            @if($item->hasEnded())
                                <small>Auction ended</small>
                            @endif
                        </h1>
                        @foreach($item->categories as $category)
                            <span class="label {{ $category->slug }}">{{ $category->title }}</span>
                        @endforeach
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="alert @{{ updated ? 'alert-success':'' }}" v-cloak>
                                    <p class="lead">£@{{ currentBid }}</p>
                                    <p>{{ $item->hasEnded() ? 'Winning' : 'High'}} Bidder: @{{ highBidder }}</p>
                                </div>
                                <p>{{ $item->description }}</p>
                                <hr>
                                <p>
                                    <small>Ends: {{ $item->end_time->toDayDateTimeString() }}
                                        ({{ !$item->hasEnded() ? $item->end_time->diffinDays() . ' ' . str_plural('day',$item->end_time->diffinDays()) . 'from now' : 'Ended ' . $item->end_time->diffForHumans() }}
                                        )
                                    </small>
                                </p>
                            </div>
                            @if(Auth::check() && !$item->hasEnded())
                                <div class="col-sm-4">
                                    <div class="quick-bids" v-cloak>
                                        <h3>Quick Bid</h3>
                                        <p>
                                            <button class="btn btn-primary form-control" @click="bid(1)">Bid
                                            £@{{ currentBid + 1 }} (
                                            Add £1 )</button>
                                        </p>
                                        <p>
                                            <button class="btn btn-primary form-control" @click="bid(5)">Bid
                                            £@{{ currentBid + 5 }} (
                                            Add £5 )</button>
                                        </p>
                                        <p>
                                            <button class="btn btn-primary form-control" @click="bid(10)">Bid
                                            £@{{ currentBid + 10 }}
                                            ( Add £10 )</button>
                                        </p>
                                        <hr>
                                        <h3>Bid</h3>
                                        <div class="row">
                                            <div class="col-sm-8">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <span class="input-group-addon">£</span>
                                                        <input title="" class="form-control" type="number" v-model="bidAmount">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <button class="form-control btn btn-primary" @click="manualBid">Bid</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="row" v-if="relatedItems.length > 0" v-cloak>
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Related Items</div>
                    <div class="panel-body">
                        <div class="row">
                            <div v-for="relatedItem in relatedItems" class="col-md-4">
                                <div class="form-group">
                                    <a class='btn btn-primary form-control' href="@{{ relatedItem.link }}">@{{ relatedItem.title }}
                                        <span class="badge indicator-item-@{{relatedItem.id}}">£@{{ relatedItem.currentBid }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    new Vue({
        el: '#main-content',

        data: {
            bidAmount: {{ ($item->currentBid()->amount ?? 0) + 1 }},
            currentBid: {{ $item->currentBid()->amount ?? 0 }},
            currentTimestamp: 0,
            highBidder: '{{ $item->highBidder() }}',
            updated: false,
            relatedItems: [
                    @foreach($item->relatedItems() as $relatedItem)
                {
                    id: {{$relatedItem->id}},
                    title: '{{$relatedItem->title}}',
                    link: '{{route('item.show', $relatedItem)}}',
                    currentBid: {{$relatedItem->currentBid()->amount ?? 0}},
                },
                @endforeach
            ]
        },

        methods: {
            manualBid: function () {
                this.makeBid(this.bidAmount);
            },
            bid: function (amount) {
                this.makeBid(amount + this.currentBid);
            },
            makeBid: function (amount) {
                var options = {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    }
                };
                this.$http.post('{{route('bid', $item) }}', {amount: amount}, options);
            }
        },

        ready: function () {
            socket.on("bids-channel{{ $item->id }}:App\\Events\\BidReceived", function (data) {
                var newBid = parseFloat(data.currentTotal);
                if (newBid > this.currentBid) {
                    this.currentBid = newBid;
                    this.currentTimestamp = data.timestamp;
                    this.highBidder = data.highBidder;
                    this.updated = true;
                    setTimeout(function () {
                        this.updated = false;
                    }.bind(this), 350);
                }
            }.bind(this));
            @foreach($item->relatedItems() as $relatedItem)
            socket.on("bids-channel{{ $relatedItem->id }}:App\\Events\\BidReceived", function (data) {
                var relatedItem = this.relatedItems.find(function (item) {
                    return item.id === {{ $relatedItem->id }};
                });
                if (parseInt(data.currentTotal) > relatedItem.currentBid) {
                    relatedItem.currentBid = data.currentTotal;
                    $('.indicator-item-{{$relatedItem->id}}').fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                }
            }.bind(this));
            @endforeach
        }
    });
</script>
@endpush