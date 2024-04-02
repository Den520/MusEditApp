@extends('layouts.app')

@section('head')
    <link rel="stylesheet" href="{{ asset('css/abcjs.css') }}">
    <script type="text/javascript" src="{{ asset('js/abcjs.js') }}"></script>
@endsection

@section('header')
    <nav class="abcjs-navbar container">
        <div class="toolbar toolbar--scroll overflow-x-visible overflow-y-hidden hstack gap-2 col-auto">
            <div class="col-auto" data-group="modes">
                <div class="d-flex justify-content-center">
                    <label>{{ __('app.Editor mode') }}</label>
                </div>
                <div class="d-flex justify-content-center">
                    <button class="active" onclick="changeEditorMode(this)" data-type="sheet-edit" title="{{ __('app.Editor mode') }}">&#9998;&#x270D;</button>
                    <button onclick="changeEditorMode(this)" data-type="clef-edit" title="{{ __('app.Key and character editing mode') }}">&#127932;</button>
                </div>
            </div>

            <div class="vr"></div>

            <div class="hstack gap-2" data-mode="sheet-edit">
                <div class="col-auto" data-group="main" data-type="note">
                    <div class="d-flex justify-content-center">
                        <label>{{ __('app.Notes') }}</label>
                    </div>
                    <div class="d-flex justify-content-center music-symbols">
                        <button onclick="toolbarButtonClick(this)" data-duration="8" title="{{ __('app.Whole') }}">&#119133;</button>
                        <button onclick="toolbarButtonClick(this)" data-duration="4" title="{{ __('app.Half') }}">&#119134;</button>
                        <button onclick="toolbarButtonClick(this)" data-duration="2" title="{{ __('app.Quarter') }}">&#119135;</button>
                        <button onclick="toolbarButtonClick(this)" data-duration="" title="{{ __('app.Eighth') }}">&#119136;</button>
                        <button onclick="toolbarButtonClick(this)" data-duration="1/2" title="{{ __('app.Sixteenth') }}">&#119137;</button>
                    </div>
                </div>

                <div class="vr"></div>

                <div class="col-auto" data-group="main" data-type="rest">
                    <div class="d-flex justify-content-center">
                        <label>{{ __('app.Rests') }}</label>
                    </div>
                    <div class="d-flex justify-content-center music-symbols">
                        <button onclick="toolbarButtonClick(this)" data-duration="8" title="4 {{ __('app.fractions') }} ({{ __('app.whole') }})">&#119099;</button>
                        <button onclick="toolbarButtonClick(this)" data-duration="4" title="2 {{ __('app.fractions') }} ({{ __('app.half') }})">&#119100;</button>
                        <button onclick="toolbarButtonClick(this)" data-duration="2" title="1 {{ __('app.fraction') }} ({{ __('app.quarter') }})">&#119101;</button>
                        <button onclick="toolbarButtonClick(this)" data-duration="" title="1/2 {{ __('app.fractions') }} ({{ __('app.eighth') }})">&#119102;</button>
                        <button onclick="toolbarButtonClick(this)" data-duration="1/2" title="1/4 {{ __('app.fractions') }} ({{ __('app.sixteenth') }})">&#119103;</button>
                    </div>
                </div>

                <div class="vr"></div>

                <div class="col-auto" data-group="main" data-type="additional">
                    <div class="d-flex justify-content-center">
                        <label>{{ __('app.Other elements') }}</label>
                    </div>
                    <div class="d-flex justify-content-center music-symbols">
                        <button onclick="toolbarButtonClick(this)" data-value="|" title="{{ __('app.Bar line') }}">&#119040;</button>
                        <button onclick="toolbarButtonClick(this)" data-value="||" title="{{ __('app.Double bar line') }}">&#119040;&#119040;</button>
                        <button onclick="toolbarButtonClick(this)" data-value="[|:" title="{{ __('app.Starting repeat sign') }}">&#119043;&#119048;</button>
                        <button onclick="toolbarButtonClick(this)" data-value=":|]" title="{{ __('app.Final repeat sign') }}">&#119048;&#119043;</button>
                        <button onclick="toolbarButtonClick(this)" data-value="|]" title="{{ __('app.Final bar line') }}">&#119042;</button>
                    </div>
                </div>

                <div class="vr"></div>

                <div class="col-auto" data-group="accidentals" data-type="accidental">
                    <div class="d-flex justify-content-center">
                        <label>{{ __('app.Accdental') }}</label>
                    </div>
                    <div class="d-flex justify-content-center music-symbols">
                        <button onclick="toolbarButtonClick(this)" data-value="" title="{{ __('app.Without sign') }}" disabled>&#8211;</button>
                        <button onclick="toolbarButtonClick(this)" data-value="^" title="{{ __('app.Sharp') }}" disabled>&#9839;</button>
                        <button onclick="toolbarButtonClick(this)" data-value="_" title="{{ __('app.Flat') }}" disabled>&#9837;</button>
                        <button onclick="toolbarButtonClick(this)" data-value="=" title="{{ __('app.Natural') }}" disabled>&#9838;</button>
                    </div>
                </div>

                <div class="vr"></div>

                <div class="col-auto" data-group="bundles" data-type="bundle">
                    <div class="d-flex justify-content-center">
                        <label>{{ __('app.Bundles') }}</label>
                    </div>
                    <div class="d-flex justify-content-center music-symbols">
                        <button onclick="switchBundleMode(this)" data-mode="join" data-type="beam" title="{{ __('app.Combine notes using an beam') }}" data-help-text="{{ __('app.Select two notes (or a range of notes) that you want to combine with an beam') }}">&#9835;</button>
                        <button onclick="switchBundleMode(this)" data-mode="join" data-type="slur" title="{{ __('app.Combine notes using an slur') }}" data-help-text="{{ __('app.Select two notes (or a range of notes) that you want to combine with an slur') }}">&#8255;</button>
                        <button class="half-opacity" onclick="switchBundleMode(this)" data-mode="separate" data-type="beam" title="{{ __('app.Separate notes combined by an beam') }}" data-help-text="{{ __('app.Select two notes (or a range of notes) which you want to cancel the beam connection') }}">&#9835;</button>
                        <button class="half-opacity" onclick="switchBundleMode(this)" data-mode="separate" data-type="slur" title="{{ __('app.Separate notes combined by an slur') }}" data-help-text="{{ __('app.Select two notes (or a range of notes) which you want to cancel the slur connection') }}">&#8255;</button>
                    </div>
                </div>

                <div class="vr"></div>

                <div class="col-auto" data-group="add-remove">
                    <div class="d-flex justify-content-center">
                        <label>{{ __('app.Adding') }}/{{ __('app.Removing') }}</label>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button onclick="addElement()" title="{{ __('app.Add') }} {{ __('app.element') }}">&#10010;</button>
                        <button onclick="removeElement()" title="{{ __('app.Remove') }} {{ __('app.element') }}">&#10006;</button>
                    </div>
                </div>

                <div class="vr"></div>

                <div class="col-auto" data-group="pitch" data-type="pitch">
                    <div class="d-flex justify-content-center">
                        <label>{{ __('app.Increase/decrease note') }}</label>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button onclick="moveNote(-1)" title="{{ __('app.Increase note') }}">&#9650;</button>
                        <button onclick="moveNote(1)" title="{{ __('app.Decrease note') }}">&#9660;</button>
                    </div>
                </div>
            </div>

            <div class="hstack gap-2" data-mode="clef-edit" hidden>
                <div class="col-auto" data-group="clef" data-type="clef">
                    <div class="d-flex justify-content-center">
                        <label>{{ __('app.Clef') }}</label>
                    </div>
                    <div class="d-flex justify-content-center music-symbols">
                        <button onclick="changeClef(this)" data-value="treble" title="{{ __('app.Treble') }}">&#119070;</button>
                        <button onclick="changeClef(this)" data-value="bass" title="{{ __('app.Bass') }}">&#119074;</button>
                    </div>
                </div>

                <div class="vr"></div>

                <div class="col-auto" data-group="keySignature" data-type="keySignature">
                    <div class="d-flex justify-content-center">
                        <label>{{ __('app.Tonality') }}</label>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button onclick="changeClef(this)" title="{{ __('app.Without signs') }}" data-value="C">До</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.sharp_count', 1) }}" data-value="G">{{ __('app.Sol') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.sharp_count', 2) }}" data-value="D">{{ __('app.Re') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.sharp_count', 3) }}" data-value="A">{{ __('app.La') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.sharp_count', 4) }}" data-value="E">{{ __('app.Mi') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.sharp_count', 5) }}" data-value="B">{{ __('app.Si') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.sharp_count', 6) }}" data-value="F#">{{ __('app.Fa') }} {{ __('app.sharp') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.sharp_count', 7) }}" data-value="C#">{{ __('app.Do') }} {{ __('app.sharp') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.flat_count', 1) }}" data-value="F">{{ __('app.Fa') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.flat_count', 2) }}" data-value="Bb">{{ __('app.Si') }} {{ __('app.flat') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.flat_count', 3) }}" data-value="Eb">{{ __('app.Mi') }} {{ __('app.flat') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.flat_count', 4) }}" data-value="Ab">{{ __('app.La') }} {{ __('app.flat') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.flat_count', 5) }}" data-value="Db">{{ __('app.Re') }} {{ __('app.flat') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.flat_count', 6) }}" data-value="Gb">{{ __('app.Sol') }} {{ __('app.flat') }}</button>
                        <button onclick="changeClef(this)" title="{{ trans_choice('app.flat_count', 7) }}" data-value="Cb">{{ __('app.Do') }} {{ __('app.flat') }}</button>
                    </div>
                </div>

                <div class="vr"></div>

                <div class="col-auto" data-group="meter" data-type="meter">
                    <div class="d-flex justify-content-center">
                        <label>{{ __('app.Meter') }}</label>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button onclick="changeClef(this)" title="2/4" data-value="2/4">2/4</button>
                        <button onclick="changeClef(this)" title="3/4" data-value="3/4">3/4</button>
                        <button onclick="changeClef(this)" title="4/4" data-value="4/4">4/4</button>
                    </div>
                </div>
            </div>
        </div>
        <hr>
        <div class="toolbar-help-text"></div>
    </nav>
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

                <form id="saveForm" action="{{ route('sheet.update', $sheet->id) }}" method="POST" class="col">
                    @csrf
                    @method('patch')
                    <input type="hidden" name="content">
                    <div class="btn-group d-flex col">
                        <button type="submit" class="btn btn-primary">{{ __('app.Save') }}</button>
                    </div>
                </form>
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
        abcInitialize("{{ $sheet->content }}", "sheet");

        let toolbarButtons = document.querySelectorAll(".abcjs-navbar .toolbar button");
        toolbarButtons.forEach(button => {
            button.addEventListener("mouseover", setHelpText);
            button.addEventListener("mouseout", setHelpText);
        });

        document.getElementById("saveForm").addEventListener('submit', function(event) {
            if (document.querySelector("#saveForm [name='content']")) {
                document.querySelector("#saveForm [name='content']").value = abcString.replaceAll("\n", "\\n");
            }
        }, true);

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