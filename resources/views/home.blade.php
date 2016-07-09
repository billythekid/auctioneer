@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        <p>
                            <a href="{{route('item.create')}}">List a new item</a>
                        </p>
                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading"><h2>Current Items</h2></div>
                    <div class="panel-body">
                        {{ $items->links() }}
                        @foreach($items as $item)
                            <p><a class='btn btn-primary' href="{{route('item.show', $item)}}">{{ $item->title }}</a></p>
                        @endforeach
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
