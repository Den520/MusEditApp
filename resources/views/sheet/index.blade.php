@extends('layouts.app')
@section('content')
<div class="container">
    {{ __('app.My sheets') }}
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div>
                <a href="{{ route('sheet.create') }}" class="btn btn-primary">{{ __('app.Create new composition') }}</a>
            </div>
            @foreach ($sheets as $sheet)
                <div>
                    <a href="{{ route('sheet.edit', $sheet->id) }}">{{ $sheet->title }}</a>
                    <div>
                        <a href="{{ route('sheet.settings', $sheet->id) }}" class="btn btn-secondary">{{ __('app.Configure') }}</a>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">{{ __('app.Delete') }}</button>

                        <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="deleteModalLabel">{{ __('app.Delete composition') }}</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        {{ __('app.Are you sure you want to delete this composition?') }}
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
        </div>
    </div>
</div>
@endsection