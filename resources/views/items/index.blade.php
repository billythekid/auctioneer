@extends('layouts.app')
@section('title', '$')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">All items</div>
                    <div class="panel-body">

                        <a href="{{route('item.create')}}" class="btn btn-primary">List an item.</a>

                        {{ $items->links() }}

                        <ol>
                            @foreach($items as $item)
                                <li><a class='btn btn-primary' href="{{route('item.show', $item)}}">{{ $item->title }}</a></li>
                            @endforeach
                        </ol>
                        {{ $items->links() }}

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
