@extends('layouts.app')
@section('content')
<div class="container">
    <form action="{{ route('sheet.settings-update', $sheet->id) }}" method="POST">
        @csrf
        @method('patch')
        <div>
            <label for="title">{{ __('app.Composition title') }}</label>
            <input type="text" name="title" class="form-control" placeholder="{{ __('app.Enter title') }}" value="{{ $sheet->title }}" required>
        </div>
        <button type="submit" class="btn btn-primary">{{ __('app.Save') }}</button>
    </form>
</div>
@endsection