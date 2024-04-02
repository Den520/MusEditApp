@extends('layouts.app')
@section('content')
<div class="container">
    <div class="row justify-content-between">
        <div class="col-auto">
            <h1>{{ __('app.Creating new composition') }}</h1>
        </div>
    </div>
    <div class="col-auto">
        <form action="{{ route('sheet.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="title" class="form-label">{{ __('app.Composition title') }}</label>
                <input type="text" class="form-control" id="title" name="title" placeholder="{{ __('app.Enter title') }}" required>
            </div>
            <button type="submit" class="btn btn-primary">{{ __('app.Create') }}</button>
        </form>
    </div>
</div>
@endsection
