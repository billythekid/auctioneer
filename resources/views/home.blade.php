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
                <div class="panel panel-default">
                    <div class="panel-heading">Welcome</div>

                    <div class="panel-body" id="main-content">
                        {{ $items->links() }}
                        <div class="row">
                            @foreach($items as $item)
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <a class='btn btn-primary form-control' href="{{route('item.show', $item)}}">{{ $item->title }}
                                            <span class="badge indicator-item-{{$item->id}}" >£{{ item<?=$item->id?> }}</span>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        {{ $items->links() }}
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
            @foreach($items as $item)
            item{{$item->id}}: {{$item->currentBid()->amount ?? 0}},
            @endforeach
        },

        ready: function () {
            @foreach($items as $item)

            socket.on("bids-channel{{ $item->id }}:App\\Events\\BidReceived", function (data) {
                this.item{{$item->id}} = parseFloat(data.currentTotal);
                $('.indicator-item-{{$item->id}}').fadeIn(100).fadeOut(100).fadeIn(100).fadeOut(100).fadeIn(100);
            }.bind(this));

            @endforeach

            socket.on('visitorsConnected', function(data){
                console.log(data);
            });
        }
    });
</script>

@endpush