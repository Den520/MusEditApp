@extends('layouts.app')
@section('content')
<div class="container">
    <div class="mb-5">
        <div class="row justify-content-between">
            <div class="col-auto">
                <h1>{{ __('app.My sheets') }}</h1>
            </div>
            <div class="col-auto">
                <a href="{{ route('sheet.create') }}" class="btn btn-primary">{{ __('app.Create new composition') }}</a>
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
                            <h3>
                                <a class="text-decoration-none" href="{{ route('sheet.edit', $sheet->id) }}">{{ $sheet->title }}</a>
                            </h3>
                        </div>

                        <div class="col-auto">
                            <a href="{{ route('sheet.settings', $sheet->id) }}" class="btn btn-secondary">{{ __('app.Configure') }}</a>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $sheet->id }}">{{ __('app.Delete') }}</button>
                            <div class="modal fade" id="deleteModal{{ $sheet->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $sheet->id }}" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="deleteModalLabel{{ $sheet->id }}">{{ __('app.Delete composition') }}</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            {{ __('app.Are you sure you want to delete composition?', ['title' => $sheet->title]) }}
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('app.Cancel') }}</button>
                                            <form action="{{ route('sheet.delete', $sheet->id) }}" method="POST">
                                                @csrf
                                                @method('delete')
                                                <button type="submit" class="btn btn-danger">{{ __('app.Permanently delete composition') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </div>

    <div class="mb-5">
        <div class="row">
            <div class="col-auto">
                <h1>{{ __('app.Sheets from other users that have been accessed') }}</h1>
            </div>
        </div>
        
        <div>
            @if ($accessed_sheets->isEmpty())
                <div class="row my-4 p-4 bg-white border rounded">
                    <h3 class="text-center">{{ __('app.There\'s nothing here yet') }}</h3>
                </div>
            @else
                @foreach ($accessed_sheets as $sheet)
                    <div class="row gap-2 my-4 p-4 bg-white border rounded justify-content-between">
                        <div class="col-auto">
                            <div class="row gap-2">
                                <h3>
                                    <a class="text-decoration-none" href="{{ route('sheet.edit', $sheet->id) }}">{{ $sheet->title }}</a>
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
</div>
@endsection