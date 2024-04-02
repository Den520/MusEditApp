@extends('layouts.app')
@section('content')
<div class="container">
    <div class="mb-5">
        <div class="row">
            <div class="col-auto">
                <h1>{{ __('app.Editing the composition settings') }}</h1>
            </div>
        </div>
        <div>
            <form action="{{ route('sheet.settings-update', $sheet->id) }}" method="POST">
                @csrf
                @method('patch')
                <div class="mb-3">
                    <label for="title" class="form-label">{{ __('app.Composition title') }}</label>
                    <input type="text" class="form-control" id="title" name="title" value="{{ $sheet->title }}" placeholder="{{ __('app.Enter title') }}" required />
                </div>
                <div class="mb-3">
                    <label for="is_published" class="form-label">{{ __('app.Status') }}</label>
                    <select class="form-select" id="is_published" name="is_published">
                        <option value="1" @if ($sheet->is_published) selected @endif>{{ __('app.Published') }}</option>
                        <option value="0" @if (!$sheet->is_published) selected @endif>{{ __('app.Unpublished') }}</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">{{ __('app.Save') }}</button>
            </form>
        </div>
    </div>

    <div class="mb-5">
        <div class="row">
            <div class="col-auto">
                <h1>{{ __('app.Users who have access to the composition') }}</h1>
            </div>
        </div>
        <div class="mb-5">
            <div class="row">
                <h4>{{ __('app.Grant access to user') }}</h4>
            </div>
            <div>
                <form action="{{ route('sheet.grant-access', $sheet->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('app.User email') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="{{ __('app.Enter email') }}" required />

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">{{ __('app.Grant access to the user') }}</button>
                </form>
            </div>
        </div>

        <div class="mb-5">
            <div class="row">
                <h4>{{ __('app.List of users with access') }}</h4>
            </div>
            <div>
                @if ($accessed_users->isEmpty())
                    <div class="row my-4 p-4 bg-white border rounded">
                        <h3 class="text-center">{{ __('app.There\'s nothing here yet') }}</h3>
                    </div>
                @else
                    @foreach ($accessed_users as $user)
                        <div class="row gap-2 my-4 p-4 bg-white border rounded justify-content-between align-items-center">
                            <div class="col-auto">
                                {{ $user->name }} ({{ $user->email }})
                            </div>
                            <div class="col-auto">
                                <form action="{{ route('sheet.revoke-access', $sheet->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="user_id" value="{{ $user->id }}">
                                    <button type="submit" class="btn btn-primary">{{ __('app.Revoke access') }}</button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
@endsection