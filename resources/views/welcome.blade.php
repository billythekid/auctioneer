@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading">Welcome</div>

                    <div class="panel-body" id="main-content">
                        {{ $items->links() }}
                        <div class="row">
                            @foreach($items as $item)
                                <div class="col-md-4"><a class='btn btn-primary' href="{{route('item.show', $item)}}">{{ $item->title }}</a></div>
                            @endforeach
                        </div>
                        {{ $items->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection