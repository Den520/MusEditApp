@extends('layouts.app')

@section('header')
    <link rel="stylesheet" href="{{ asset('css/abcjs.css') }}">
    <script type="text/javascript" src="{{ asset('js/abcjs.js') }}"></script>
@endsection

@section('content')
    <div id="sheet"></div>
    <div id="audio"></div>

    <div class="toolbar">
        <div data-mode="sheet-edit">
            <div data-group="main" data-type="note">
                <label>Ноты</label>
                <button onclick="toolbarButtonClick(this)" data-duration="8" title="Целая">&#119133;</button>
                <button onclick="toolbarButtonClick(this)" data-duration="4" title="Половинная">&#119134;</button>
                <button onclick="toolbarButtonClick(this)" data-duration="2" title="Четвертная">&#119135;</button>
                <button onclick="toolbarButtonClick(this)" data-duration="" title="Восьмая">&#119136;</button>
                <button onclick="toolbarButtonClick(this)" data-duration="1/2" title="Шестнадцатая">&#119137;</button>
            </div>

            <div data-group="main" data-type="rest">
                <label>Паузы</label>
                <button onclick="toolbarButtonClick(this)" data-duration="8" title="4 доли (целая)">&#119099;</button>
                <button onclick="toolbarButtonClick(this)" data-duration="4" title="2 доли (половинная)">&#119100;</button>
                <button onclick="toolbarButtonClick(this)" data-duration="2" title="1 доля (четвертная)">&#119101;</button>
                <button onclick="toolbarButtonClick(this)" data-duration="" title="1/2 доли (восьмая)">&#119102;</button>
                <button onclick="toolbarButtonClick(this)" data-duration="1/2" title="1/4 доли (шестнадцатая)">&#119103;</button>
            </div>

            <div data-group="main" data-type="additional">
                <label>Другие элементы</label>
                <button onclick="toolbarButtonClick(this)" data-value="|" title="Тактовая черта">&#119040;</button>
                <button onclick="toolbarButtonClick(this)" data-value="||" title="Двойная тактовая черта">&#119040;&#119040;</button>
                <button onclick="toolbarButtonClick(this)" data-value="[|:" title="Начинающая реприза">&#119043;&#119048;</button>
                <button onclick="toolbarButtonClick(this)" data-value=":|]" title="Завершающая реприза">&#119048;&#119043;</button>
                <button onclick="toolbarButtonClick(this)" data-value="|]" title="Завершающая тактовая черта">&#119042;</button>
            </div>

            <div data-group="accidentals" data-type="accidental">
                <label>Альтерация</label>
                <button onclick="toolbarButtonClick(this)" data-value="" title="Без знака" disabled>&#8212;</button>
                <button onclick="toolbarButtonClick(this)" data-value="^" title="Диез" disabled>&#9839;</button>
                <button onclick="toolbarButtonClick(this)" data-value="_" title="Бемоль" disabled>&#9837;</button>
                <button onclick="toolbarButtonClick(this)" data-value="=" title="Бекар" disabled>&#9838;</button>
            </div>

            <div data-group="bundles" data-type="bundle">
                <label>Связки</label>
                <button onclick="switchBundleMode(this)" data-mode="join" data-type="beam" title="Объединить ноты с помощью ребра">&#9835;</button>
                <button onclick="switchBundleMode(this)" data-mode="join" data-type="tie" title="Объединить ноты с помощью легато">&#8255;</button>
                <button class="half-opacity" onclick="switchBundleMode(this)" data-mode="separate" data-type="beam" title="Разъединить ноты, соединённые с помощью ребра">&#9835;</button>
                <button class="half-opacity" onclick="switchBundleMode(this)" data-mode="separate" data-type="tie" title="Разъединить ноты, соединённые с помощью легато">&#8255;</button>
            </div>

            <div data-group="add-remove">
                <button onclick="addElement()" title="Добавить элемент">&#10010;</button>
                <button onclick="removeElement()" title="Удалить элемент">&#10006;</button>
            </div>

            <div data-group="pitch" data-type="pitch">
                <button onclick="moveNote(-1)" title="Повысить ноту">&#9650;</button>
                <button onclick="moveNote(1)" title="Понизить ноту">&#9660;</button>
            </div>
        </div>

        <div data-mode="clef-edit">
            <div data-group="clef" data-type="clef">
                <label>Ключ</label>
                <button onclick="changeClef(this)" data-value="treble" title="Скрипичный">&#119070;</button>
                <button onclick="changeClef(this)" data-value="bass" title="Басовый">&#119074;</button>
            </div>
            <div data-group="keySignature" data-type="keySignature">
                <label>Тональность</label>
                <button onclick="changeClef(this)" title="Без знаков" data-value="C">До</button>
                <button onclick="changeClef(this)" title="1 диез" data-value="G">Соль</button>
                <button onclick="changeClef(this)" title="2 диеза" data-value="D">Ре</button>
                <button onclick="changeClef(this)" title="3 диеза" data-value="A">Ля</button>
                <button onclick="changeClef(this)" title="4 диеза" data-value="E">Ми</button>
                <button onclick="changeClef(this)" title="5 диезов" data-value="B">Си</button>
                <button onclick="changeClef(this)" title="6 диезов" data-value="F#">Фа диез</button>
                <button onclick="changeClef(this)" title="7 диезов" data-value="C#">До диез</button>
                <button onclick="changeClef(this)" title="1 бемоль" data-value="F">Фа</button>
                <button onclick="changeClef(this)" title="2 бемоля" data-value="Bb">Си бемоль</button>
                <button onclick="changeClef(this)" title="3 бемоля" data-value="Eb">Ми бемоль</button>
                <button onclick="changeClef(this)" title="4 бемоля" data-value="Ab">Ля бемоль</button>
                <button onclick="changeClef(this)" title="5 бемолей" data-value="Db">Ре бемоль</button>
                <button onclick="changeClef(this)" title="6 бемолей" data-value="Gb">Соль бемоль</button>
                <button onclick="changeClef(this)" title="7 бемолей" data-value="Cb">До бемоль</button>
            </div>
            <div data-group="meter" data-type="meter">
                <label>Размерность</label>
                <button onclick="changeClef(this)" data-value="2/4">2/4</button>
                <button onclick="changeClef(this)" data-value="3/4">3/4</button>
                <button onclick="changeClef(this)" data-value="4/4">4/4</button>
            </div>
        </div>

        <div class="modes" data-group="modes" data-type="mode">
            <label>Режим редактора</label>
            <button class="active" onclick="changeEditorMode(this)" data-type="sheet-edit" title="Режим редактирования">&#9998;&#x270D;</button>
            <button onclick="changeEditorMode(this)" data-type="clef-edit" title="Режим редактирования ключа и знаков при ключе">&#127932;</button>
        </div>
    </div>
    <div class="toolbar-help-text">
        Help text
    </div>

    <script type="text/javascript">
        abcInitialize("X:1\nK:D clef=treble\nM:4/4\nL:1/8\nDD ABz|BBD2|DD ABz|BBD2|]", "sheet");
    </script>
@endsection