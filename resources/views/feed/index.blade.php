@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-auto">
            <h1>{{ __('app.Sheet Feed') }}</h1>
        </div>
    </div>
    <div>
        @if ($sheets->isEmpty())
                <div class="row my-4 p-4 bg-white border rounded">
                    <h3 class="text-center">{{ __('app.There\'s nothing here yet') }}</h3>
                </div>
        @else
            @foreach ($sheets as $sheet)
                <div class="row gap-2 my-4 p-4 bg-white border rounded justify-content-between">
                    <div class="col-auto">
                        <div class="row">
                            <h3>
                                <a class="text-decoration-none" href="{{ route('feed.show', $sheet->id) }}">{{ $sheet->title }}</a>
                            </h3>
                        </div>

                        <div class="row">
                            <p>{{ __('app.Author') }}: {{ $sheet->user->name }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</div>
@endsection