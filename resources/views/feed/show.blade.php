@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/abcjs.css') }}">
    <script type="text/javascript" src="{{ asset('js/abcjs.js') }}"></script>
@endsection

@section('content')
<div class="container abcjs-container">
    {{ __('app.My sheets') }}
    <div>
        <div id="sheet"></div>
        <div id="audio"></div>
    </div>

    <script type="text/javascript">
        abcInitialize("{{ $sheet->content }}", "sheet", false);
    </script>
</div>
@endsection