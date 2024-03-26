@extends('layouts.app')
@section('content')
<div>
    <form action="{{ route('sheet.store') }}" method="POST">
        @csrf
        <div>
            <label for="title">{{ __('app.Composition title') }}</label>
            <input type="text" name="title" class="form-control" placeholder="{{ __('app.Enter title') }}" required>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('app.Create') }}</button>
    </form>
</div>
@endsection
