@extends('layouts.app')

@section('content')
    <div class="container" id="main-content">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h1>{{ $item->title }}</h1>
                        @foreach($item->categories as $category)
                            <span class="label {{ $category->slug }}">{{ $category->title }}</span>
                        @endforeach
                    </div>

                    <div class="panel-body">
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="alert current-bid">
                                    <p class="lead">£@{{ currentBid }}</p>
                                    <p>High Bidder: @{{ highBidder }}</p>
                                </div>
                                <p>{{ $item->description }}</p>
                                <hr>
                                <p>
                                    <small>Ends: {{ $item->end_time->toDayDateTimeString() }}
                                        ({{ $item->end_time->diffinDays() . ' ' . str_plural('day',$item->end_time->diffinDays()) }}
                                        from now)
                                    </small>
                                </p>
                            </div>
                            @if(Auth::check())
                                <div class="col-sm-4">
                                    <div class="quick-bids">
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

        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Related Items</div>
                    <div class="panel-body">
                        <div class="row">
                            @foreach($item->relatedItems() as $relatedItem)
                                <div class="col-md-4">
                                    <a class='btn btn-primary' href="{{route('item.show', $relatedItem)}}">{{ $relatedItem->title }}
                                        <span class="badge indicator-item-{{$relatedItem->id}}">£{{ item<?=$relatedItem->id?> }}</span>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    var channel = "bids-channel{{ $item->id }}";
    var socket = io(':3000');
    new Vue({
        el: '#main-content',

        data: {
            bidAmount: {{ ($item->currentBid()->amount ?? 0) + 1 }},
            currentBid: {{ $item->currentBid()->amount ?? 0 }},
            currentTimestamp: 0,
            highBidder: '{{ $item->highBidder() }}',
            @foreach($item->relatedItems() as $relatedItem)
            item{{$relatedItem->id}}: {{$relatedItem->currentBid()->amount ?? 0}},
            @endforeach
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
            socket.on(channel + ":App\\Events\\BidReceived", function (data) {
                if (data.item_id == {{ $item->id }} ) {
                    var newBid = parseFloat(data.currentTotal);
                    if (newBid > this.currentBid) {
                        this.currentBid = newBid;
                        this.currentTimestamp = data.timestamp;
                        this.highBidder = data.highBidder;

                        $('.current-bid').addClass('alert-success');
                        setTimeout(function () {
                            $('.current-bid').removeClass('alert-success');
                        }, 350);
                    }
                }
            }.bind(this));
            @foreach($item->relatedItems() as $relatedItem)
            socket.on("bids-channel{{ $relatedItem->id }}:App\\Events\\BidReceived", function (data) {
                this.item{{$relatedItem->id}} = parseFloat(data.currentTotal);
                $('.indicator-item-{{$relatedItem->id}}').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
            }.bind(this));
            @endforeach
        }
    });
</script>
@endpush