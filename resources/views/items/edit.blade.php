@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-10 col-md-offset-1">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1>{{ $item->title }}</h1></div>


                    <div class="panel-body">
                        <form action="{{ route('item.update',$item) }}" method="POST">
                            {{ method_field('PUT') }}
                            {{ csrf_field() }}
                            <div class="form-group {{ $errors->has('title') ? 'has-error':'' }}">
                                <label class="control-label" for="title">Title</label>
                                <input id="title" name="title" class="form-control" type="text" placeholder="Title" value="{{ old('title') }}">
                                @if($errors->has('title'))
                                    <p class="help-block">{{$errors->get('title')[0] }}</p>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('description') ? 'has-error' : '' }}">
                                <label class="control-label" for="description">Description</label>
                                <textarea id="description" name="description" class="form-control" type="text" placeholder="Description">{{ old('description') }}</textarea>
                                @if($errors->has('description'))
                                    <p class="help-block">{{$errors->get('description')[0] }}</p>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('price') ? 'has-error' : '' }}">
                                <label for="price">Price</label>
                                <input id="price" name="price" class="form-control" type="text" placeholder="Price" value="{{ old('price') }}">
                                @if($errors->has('price'))
                                    <p class="help-block">{{$errors->get('price')[0] }}</p>
                                @endif
                            </div>
                            <div class="form-group {{ $errors->has('duration') ? 'has-error' : '' }}">
                                <label class="control-label" for="duration">Duration (days)</label>
                                <input id="duration" name="duration" class="form-control" type="number" placeholder="Duration (days)" value="{{ old('duration') }}" min="0" max="28">
                                @if($errors->has('duration'))
                                    <p class="help-block">{{$errors->get('duration')[0] }}</p>
                                @endif
                            </div>
                            <div class="form-group">
                                <input name="listItem" class="form-control btn btn-primary" type="submit" value="List Item">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection