@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/abcjs.css') }}">
    <script type="text/javascript" src="{{ asset('js/abcjs.js') }}"></script>
@endsection

@section('content')
    <div id="abcjs-container" class="container">
        <div id="sheet"></div>
    </div>

    <div id="printable-abcjs-container" hidden></div>
@endsection

@section('footer')
    <footer>
        <div class="container fixed-bottom d-grid gap-2 py-2">
            <div id="audio"></div>
            <div id="save-buttons" class="row">
                <div class="btn-group d-flex col">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exportModal">{{ __('app.Export') }}</button>
                </div>
            </div>
        </div>
        <div class="modal fade" id="exportModal" tabindex="-1" aria-labelledby="exportModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exportModalLabel">{{ __('app.Export composition') }}</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>{{ __('app.Select the type of export') }}</p>
                        <form id="exportForm" action="{{ route('export') }}" method="POST" enctype="multipart/form-data" class="col">
                            @csrf
                            <input type="hidden" name="type" />
                            <input type="file" name="midi_file" hidden />
                            <div class="list-group">
                                <div id="midi-link" hidden></div>
                                <button type="submit" class="list-group-item list-group-item-action" data-type="midi">MIDI</button>
                                <button type="submit" class="list-group-item list-group-item-action" data-type="wav">WAV</button>
                                <button type="submit" class="list-group-item list-group-item-action" data-type="print">{{ __('app.Print') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <script type="text/javascript">
        abcInitialize("{{ $sheet->content }}", "sheet", false);

        document.querySelector("#exportForm").addEventListener('submit', function(event) {
            let type = event.submitter.dataset.type;
            if (["midi", "wav", "print"].includes(type)) {
                exportFile(type);
                event.preventDefault();
            }
            // TODO: Export for gp5, MusicXML
            // else {
            //     let midi_blob = new Blob(ABCJS.synth.getMidiFile(abcString, { midiOutputType: 'binary', bpm: abcVisualObj[0].metaText.tempo.bpm }));
            //     let midi_file = new File([midi_blob], abcVisualObj[0].metaText.title + "." + type, {type: "audio/midi"});
            //     let container = new DataTransfer();
            //     container.items.add(midi_file);
            //     document.querySelector("#exportForm [name='midi_file']").files = container.files;
            //     document.querySelector("#exportForm [name='type']").value = type;
            // }
        }, true);
    </script>
@endsection