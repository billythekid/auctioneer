@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <p>
                                    <a href="{{route('item.create')}}">List a new item</a>
                                </p>
                            </div>
                        </div>
                        @include('partials.logins')
                    </div>
                </div>
                <div class="panel panel-default" id="main-content">
                    <div class="panel-heading">Current Listings</div>

                    <div class="panel-body">
                        {{ $items->links() }}
                        <div class="row">
                            <div v-for="item in items" class="col-md-4" v-cloak>
                                <div class="form-group">
                                    <a class='btn btn-primary form-control' href="@{{item.link}}">@{{item.title}}
                                        <span class="badge @{{ item.itemClass }}">£@{{item.price}}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{ $items->links() }}
                    </div>

                    <div class="panel-heading">Recently Ended</div>
                    <div class="panel-body" id="ended-items">
                        {{ $endedItems->links() }}
                        <div class="row">
                            <div v-for="item in endedItems" class="col-md-4" v-cloak>
                                <div class="form-group">
                                    <a class='btn btn-warning form-control' href="@{{item.link}}">@{{item.title}}
                                        <span class="badge @{{ item.itemClass }}">£@{{item.price}}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                        {{ $endedItems->links() }}
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
            items: [
                    @foreach($items as $item)
                {
                    id: {{ $item->id }},
                    title: '{{ $item->title }}',
                    price: '{{ $item->currentBid()->amount ?? 0}}',
                    link: '{{ route('item.show', $item) }}',
                    itemClass: "indicator-item-{{$item->id}}",
                },
                @endforeach
            ],
            endedItems: [
                    @foreach($endedItems as $item)
                {
                    id: {{ $item->id }},
                    title: '{{ $item->title }}',
                    price: '{{ $item->currentBid()->amount ?? 0}}',
                    link: '{{ route('item.show', $item) }}',
                    itemClass: "indicator-item-{{$item->id}}",
                },
                @endforeach
            ]
        },

        ready: function () {
            @foreach($items as $item)
            socket.on("bids-channel{{ $item->id }}:App\\Events\\BidReceived", function (data) {
                var item = this.items.find(function (item) {
                    return item.id === {{ $item->id }};
                });
                if (parseInt(data.currentTotal) > item.price) {
                    item.price = data.currentTotal;
                    $('.indicator-item-{{$item->id}}').fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
                }
            }.bind(this));

            @endforeach

        }
    });
</script>

@endpush