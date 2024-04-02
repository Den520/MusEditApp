@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/abcjs.css') }}">
    <script type="text/javascript" src="{{ asset('js/abcjs.js') }}"></script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-auto">
            <h1>{{ __('app.Viewing a composition') }}</h1>
        </div>
    </div>
    <div class="container abcjs-container">
        <div id="sheet"></div>
        <div id="audio"></div>
    
        <script type="text/javascript">
            abcInitialize("{{ $sheet->content }}", "sheet", false);
        </script>
    </div>
</div>
@endsection