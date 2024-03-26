@extends('layouts.app')
@section('content')
<div class="container">
    {{ __('app.Sheet Feed') }}
    <div class="row justify-content-center">
        <div class="col-md-8">
            @foreach ($sheets as $sheet)
                <div>
                    <a href="{{ route('feed.show', $sheet->id) }}">{{ $sheet->title }}</a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
