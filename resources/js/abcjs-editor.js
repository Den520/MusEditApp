let abcString;
let sheetName;
let abcVisualObj;
let helpTextCurrentValue = "";
let isEditorMode;
let selectMode = "default";
let selectedAbcElem;
let abcElementsOfSegment = [];
let allPitches = [
    'C,,,,', 'D,,,,', 'E,,,,', 'F,,,,', 'G,,,,', 'A,,,,', 'B,,,,',
    'C,,,', 'D,,,', 'E,,,', 'F,,,', 'G,,,', 'A,,,', 'B,,,',
    'C,,', 'D,,', 'E,,', 'F,,', 'G,,', 'A,,', 'B,,',
    'C,', 'D,', 'E,', 'F,', 'G,', 'A,', 'B,',
    'C', 'D', 'E', 'F', 'G', 'A', 'B',
    'c', 'd', 'e', 'f', 'g', 'a', 'b',
    "c'", "d'", "e'", "f'", "g'", "a'", "b'",
    "c''", "d''", "e''", "f''", "g''", "a''", "b''",
    "c'''", "d'''", "e'''", "f'''", "g'''", "a'''", "b'''",
    "c''''", "d''''", "e''''", "f''''", "g''''", "a''''", "b''''"
];

function abcInitialize(abcStringInit = "", sheetNameInit = "sheet", isEditor = true) {
    abcString = abcStringInit;
    sheetName = sheetNameInit;
    isEditorMode = isEditor;
    draw(sheetName, {}, isEditorMode);
}

function draw(sheetElemName, specificOptions = {}, isEditor = true, withCreateAudio = true) {
    let options = {
        wrap: {
            minSpacing: 2,
            maxSpacing: 3,
            preferredMeasuresPerLine: 8
        },
        staffwidth: document.getElementById("abcjs-container").offsetWidth - 54
    };

    if (isEditor) {
        Object.assign(options, {
            add_classes: true,
            selectionColor: "red",
            dragColor: "red",
            clickListener: selectElement
        });
        let editorMode = document.querySelector("[data-group='modes'] button.active").dataset.type;
        if (editorMode == "sheet-edit") {
            options.dragging = true;
            options.selectTypes = ["note", "bar"]
        }
    
        abcString = abcString.replace(/ +(?= )/g,'');
        let abcStringLines = abcString.split(/\n/);
        if (abcStringLines[abcStringLines.length - 1] == "") {
            abcStringLines[abcStringLines.length - 1] = "z2";
            abcString = abcStringLines.join("\n");
        }
        else {
            abcString = abcString.trim();
        }
    }

    if (specificOptions) {
        Object.assign(options, specificOptions);
    }
        
    abcVisualObj = ABCJS.renderAbc(sheetElemName, abcString, options);
    
    if (selectedAbcElem && isEditor) {
        setSelectionToElementFromChar(selectedAbcElem.startChar);
    }

    if (withCreateAudio) {
        createAudio();
        return abcVisualObj;
    }
}

window.addEventListener("resize", function(event) {
    draw(sheetName, {}, isEditorMode, false);
}, true);

function setHelpText(event) {
    let helpTextElem = document.querySelector(".toolbar-help-text");
    if (event.type == 'mouseover') {
        helpTextElem.innerHTML = event.target.title;
    }
    if (event.type == 'mouseout') {
        helpTextElem.innerHTML = helpTextCurrentValue;
    }
}

function tokenize(str) {
    let arr = str.split(/(!.+?!|".+?")/);
    let output = [];
    for (let i = 0; i < arr.length; i++) {
        let token = arr[i];
        if (token.length > 0) {
            if (token[0] !== '"' && token[0] !== '!') {
                let arr2 = arr[i].split(/([A-Ga-g][,']*)/);
                output = output.concat(arr2);
            } else
                output.push(token);
        }
    }
    return output;
}

function getPitchedNote(note, step) {
    let x = allPitches.indexOf(note);
    if (x >= 0)
        return allPitches[x - step];
    return note;
}

function removeElementSelection() {
    selectedAbcElem = null;
    let elements = document.querySelectorAll("g.abcjs-note_selected");
    elements.forEach(element => {
        element.classList.remove("abcjs-note_selected");
    });
}

function setAbcElemDatasetInfo(abcElem) {
    abcElem.datasetInfo = [];
    abcElem.datasetInfo.index = abcElem.abselem.elemset[0].dataset.index;
    if (abcElem.pitches) {
        abcElem.datasetInfo.type = "note";
        abcElem.datasetInfo.duration = abcString.substring(abcElem.startChar, abcElem.endChar).replace(/[^1-9//]/g, "");
        abcElem.datasetInfo.accidental = abcElem.pitches[0].name.replace(/[^/^_=]/g, "");
    }
    else if (abcElem.rest) {
        abcElem.datasetInfo.type = "rest";
        abcElem.datasetInfo.duration = abcString.substring(abcElem.startChar, abcElem.endChar).replace(/[^1-9//]/g, "");
    }
    else if (abcElem.el_type == "bar") {
        abcElem.datasetInfo.type = "additional";
        abcElem.datasetInfo.value = abcString.substring(abcElem.startChar, abcElem.endChar).trim();
    }
    else {
        abcElem.datasetInfo.type = abcElem.el_type;
        abcElem.datasetInfo.value = abcString.substring(abcElem.startChar, abcElem.endChar).trim();
    }
}

function setToolbarState(abcElem) {
    changeToolbarGroupState("enable");
    if (abcElem.datasetInfo.type == "note") {
        setActiveToolbarButton(document.querySelector("[data-type='note'] button[data-duration='" + abcElem.datasetInfo.duration + "']"));
        setActiveToolbarButton(document.querySelector("[data-type='accidental'] button[data-value='" + abcElem.datasetInfo.accidental + "']"));
    }
    else {
        changeToolbarGroupState("disable", ["accidentals", "pitch"]);
        if (abcElem.datasetInfo.type == "rest") {
            setActiveToolbarButton(document.querySelector("[data-type='rest'] button[data-duration='" + abcElem.datasetInfo.duration + "']"));
        }
        else if (abcElem.datasetInfo.type == "additional") {
            setActiveToolbarButton(document.querySelector("[data-type='additional'] button[data-value='" + abcElem.datasetInfo.value + "']"));
        }
    }
}

function selectElement(abcElem, tuneNumber, classes, analysis, drag, mouseEvent) {
    removeElementSelection();
    setAbcElemDatasetInfo(abcElem);
    selectedAbcElem = abcElem;
    
    if (drag && drag.step) {
        moveNote(drag.step, abcElem);
    }

    if (selectMode == "default") {
        setToolbarState(abcElem);
    }
    else if (selectMode == "segment") {
        abcElementsOfSegment.push(abcElem);
        if (abcElementsOfSegment.length == 2) {
            editBundle();
        }
    }
}

function moveNote(step, abcElem = selectedAbcElem) {
    if (!abcElem) {
        return;
    }
    let originalText = abcString.substring(abcElem.startChar, abcElem.endChar);
    if (abcElem.pitches && abcElem.startChar >= 0 && abcElem.endChar >= 0) {
        let arr = tokenize(originalText);
        for (let i = 0; i < arr.length; i++) {
            arr[i] = getPitchedNote(arr[i], step);
        }
        let newAbcElemString = arr.join("");
        replaceElement(abcElem, newAbcElemString);
    }
}

function replaceElement(abcElem, newAbcElemString) {
    if (!abcElem) {
        return;
    }
    abcString = abcString.substring(0, abcElem.startChar) + newAbcElemString + abcString.substring(abcElem.endChar);
    draw(sheetName);
}

function setSelectionToElementFromChar(startChar) {
    removeElementSelection();
    selectedAbcElem = abcVisualObj[0].getElementFromChar(startChar);
    if (selectedAbcElem) {
        setAbcElemDatasetInfo(selectedAbcElem);
        document.querySelector("g[data-index='" + selectedAbcElem.datasetInfo.index + "']").classList.add("abcjs-note_selected");
        setToolbarState(selectedAbcElem);
    }
}

function toolbarButtonClick(btnElem) {
    setActiveToolbarButton(btnElem);
    let btnType = btnElem.parentElement.parentElement.dataset.type;
    let newAbcElemString;
    if (btnType == "note") {
        if (selectedAbcElem) {
            if (selectedAbcElem.pitches) {
                newAbcElemString = selectedAbcElem.pitches[0].name + btnElem.dataset.duration;
            }
            else {
                let previousAbcElem = abcVisualObj[0].getElementFromChar(selectedAbcElem.startChar - 1);
                if (previousAbcElem && previousAbcElem.pitches) {
                    newAbcElemString = previousAbcElem.pitches[0].name + btnElem.dataset.duration;
                }
                else {
                    newAbcElemString = "A" + btnElem.dataset.duration;
                }
            }
        }
        changeToolbarGroupState("enable", ["accidentals"]);
    }
    else if (btnType == "rest") {
        newAbcElemString = "z" + btnElem.dataset.duration;
        changeToolbarGroupState("disable", ["accidentals"]);
    }
    else if (btnType == "accidental") {
        if (selectedAbcElem && selectedAbcElem.pitches) {
            newAbcElemString = btnElem.dataset.value + abcString.substring(selectedAbcElem.startChar, selectedAbcElem.endChar).replace(/[\^_=]/g, "");
        }
    }
    else if (btnType == "additional") {
        newAbcElemString = btnElem.dataset.value;
        changeToolbarGroupState("disable", ["accidentals"]);
    }
    
    if (newAbcElemString) {
        replaceElement(selectedAbcElem, newAbcElemString);
    }
}

function setActiveToolbarButton(btnElem) {
    if (!btnElem) {
        return;
    }
    let btnGroup = btnElem.parentElement.parentElement.dataset.group;
    let elements = document.querySelectorAll("[data-group='" + btnGroup + "'] button");
    elements.forEach(element => {
        element.classList.remove("active");
    });
    btnElem.classList.add("active");
}

function removeActiveToolbarButton(btnElem) {
    let btnGroup = btnElem.parentElement.parentElement.dataset.group;
    let elements = document.querySelectorAll("[data-group='" + btnGroup + "'] button");
    elements.forEach(element => {
        element.classList.remove("active");
    });
}

function removeAllActiveToolbarButtons() {
    let elements = document.querySelectorAll("[data-group]:not([data-group='modes']) button");
    elements.forEach(element => {
        element.classList.remove("active");
    });
}

function changeToolbarGroupState(mode, groups = true) {
    let elements = [];
    if (groups == true) {
        elements = document.querySelectorAll("[data-group]:not([data-group='modes']) button");
    }
    else {
        groups.forEach(group => {
            elements.push(...document.querySelectorAll("[data-group='" + group + "'] button"));
        });
    }

    if (mode == "enable") {
        elements.forEach(element => {
            element.disabled = false;
            element.classList.remove("active");
        });
    }
    else if (mode == "disable") {
        elements.forEach(element => {
            element.disabled = true;
            element.classList.remove("active");
        });
    }
}

function changeEditorMode(btnElem) {
    removeAllActiveToolbarButtons();
    setActiveToolbarButton(btnElem);
    removeElementSelection();
    changeToolbarGroupState("enable");
    selectMode = "default";
    abcElementsOfSegment = [];
    let helpTextElem = document.querySelector(".toolbar-help-text");
    helpTextElem.innerHTML = helpTextCurrentValue = "";
    if (btnElem.dataset.type == "sheet-edit") {
        document.querySelector("div[data-mode='clef-edit']").setAttribute("hidden", true);
        document.querySelector("div[data-mode='sheet-edit']").removeAttribute("hidden");
    }
    else if (btnElem.dataset.type == "clef-edit") {
        document.querySelector("div[data-mode='sheet-edit']").setAttribute("hidden", true);
        document.querySelector("div[data-mode='clef-edit']").removeAttribute("hidden");
        let clefValue = getClefValue();
        let keySignature = abcVisualObj[0].getKeySignature();
        let keySignatureValue = keySignature.root + keySignature.acc;
        let meter = abcVisualObj[0].getMeterFraction();
        let meterValue = meter.num + "/" + meter.den;
        setActiveToolbarButton(document.querySelector("[data-type='clef'] button[data-value='" + clefValue + "']"));
        setActiveToolbarButton(document.querySelector("[data-type='keySignature'] button[data-value='" + keySignatureValue + "']"));
        setActiveToolbarButton(document.querySelector("[data-type='meter'] button[data-value='" + meterValue + "']"));
    }
    draw(sheetName);
}

function switchBundleMode(btnElem) {
    abcElementsOfSegment = [];
    removeElementSelection();
    let helpTextElem = document.querySelector(".toolbar-help-text");

    if (btnElem.classList.contains("active")) {
        selectMode = "default";
        changeToolbarGroupState("enable");
        removeActiveToolbarButton(btnElem);
        draw(sheetName);
        helpTextElem.innerHTML = helpTextCurrentValue = "";
    }
    else {
        selectMode = "segment";
        changeToolbarGroupState("disable");
        changeToolbarGroupState("enable", ["bundles"]);
        setActiveToolbarButton(btnElem);
        draw(sheetName, {dragging: false, selectTypes: ["note"]});
        helpTextElem.innerHTML = helpTextCurrentValue = btnElem.dataset.helpText;
    }
}

function editBundle() {
    let btnElem = document.querySelector("[data-group='bundles'] button.active");
    if (!btnElem) {
        return;
    }
    abcElementsOfSegment.sort((a, b) => parseFloat(a.startChar) - parseFloat(b.startChar));
    let bundleMode = btnElem.dataset.mode;
    let bundleType = btnElem.dataset.type;
    let abcSegmentString = abcString.substring(abcElementsOfSegment[0].startChar, abcElementsOfSegment[1].endChar);
    if (bundleMode == "join") {
        if (bundleType == "beam") {
            abcSegmentString = abcSegmentString.replace(/ /g, "");
        }
        else if (bundleType == "slur") {
            if (abcSegmentString.endsWith(" ")) {
                abcSegmentString = "(" + abcSegmentString.trim() + ") ";
            }
            else {
                abcSegmentString = "(" + abcSegmentString + ")";
            }
        }
    }
    else if (bundleMode == "separate") {
        if (bundleType == "beam") {
            let abcElements = getElementsFromSegment(abcElementsOfSegment[0].startChar, abcElementsOfSegment[1].endChar);
            abcSegmentString = "";
            abcElements.forEach(element => {
                abcSegmentString += abcString.substring(element.startChar, element.endChar) + " ";
            });
        }
        else if (bundleType == "slur") { // FIXME: Check the balancing for each bracket so that there is not one bracket left
            abcSegmentString = abcSegmentString.replace(/[()]/g, "");
        }
    }
    abcString = abcString.substring(0, abcElementsOfSegment[0].startChar) + abcSegmentString + abcString.substring(abcElementsOfSegment[1].endChar);
    removeElementSelection();
    draw(sheetName);
    abcElementsOfSegment = [];
    selectMode = "default";
    changeToolbarGroupState("enable");
    changeToolbarGroupState("disable", ["accidentals"]);
    removeActiveToolbarButton(btnElem);
    let helpTextElem = document.querySelector(".toolbar-help-text");
    helpTextElem.innerHTML = helpTextCurrentValue = "";
}

function getElementsFromSegment(startChar = 0, endChar = null) {
    let abcElements = [];
    let currentAbcElement;
    if (!endChar) {
        endChar = abcString.length;
    }
    for (let i = startChar; i < endChar; i++) {
        currentAbcElement = abcVisualObj[0].getElementFromChar(i);
        if (currentAbcElement && currentAbcElement !== abcElements[abcElements.length - 1]) {
            abcElements.push(currentAbcElement);
        }
    }
    return abcElements;
}

function addElement() {
    let newAbcElemStartChar;
    if (selectedAbcElem) {
        abcString = abcString.substring(0, selectedAbcElem.endChar) + abcString.substring(selectedAbcElem.startChar, selectedAbcElem.endChar) + abcString.substring(selectedAbcElem.endChar);
        newAbcElemStartChar = selectedAbcElem.endChar;
    }
    else {
        let lastAbcElement = getElementsFromSegment().pop();
        if (lastAbcElement) {
            newAbcElemStartChar = lastAbcElement.endChar;
        }
        let btnElem = document.querySelector("[data-group='main'] button.active");
        if (!btnElem) {
            return;
        }
        let btnType = btnElem.parentElement.parentElement.dataset.type;
        let newAbcElemString;
        if (btnType == "note") {
            newAbcElemString = "A" + btnElem.dataset.duration;
            let accidentalBtnElem = document.querySelector("[data-group='accidentals'] button.active");
            if (accidentalBtnElem) {
                newAbcElemString = accidentalBtnElem.dataset.value + newAbcElemString;
            }
        }
        else if (btnType == "rest") {
            newAbcElemString = "z" + btnElem.dataset.duration;
        }
        else if (btnType == "additional") {
            newAbcElemString = btnElem.dataset.value;
        }
        
        if (newAbcElemString) {
            abcString = abcString.substring(0, newAbcElemStartChar) + newAbcElemString + abcString.substring(newAbcElemStartChar);
        }
    }
    draw(sheetName);
    setSelectionToElementFromChar(newAbcElemStartChar);
}

function removeElement(abcElem = selectedAbcElem) {
    if (abcElem) {
        replaceElement(abcElem, "");
        if (abcString.length < abcElem.startChar) {
            setSelectionToElementFromChar(abcString.length - 1);
        }
        else {
            setSelectionToElementFromChar(abcElem.startChar - 1);
        }
    }
}

function changeClef(btnElem) {
    setActiveToolbarButton(btnElem);
    let btnType = btnElem.parentElement.parentElement.dataset.type;
    let abcStringLines = abcString.split(/\n/);
    if (btnType == "clef") {
        let clefValue = btnElem.dataset.value;
        let indexOfLine = abcStringLines.findIndex(line => line.indexOf("clef") !== -1);
        if (indexOfLine !== -1) {
            let keySignature = abcStringLines[indexOfLine].split(" ")[0];
            let clef = "clef=" + clefValue;
            abcStringLines[indexOfLine] = keySignature + " " + clef;
        }
    }
    else if (btnType == "keySignature") {
        let keySignatureValue = btnElem.dataset.value;
        let indexOfLine = abcStringLines.findIndex(line => line.indexOf("K:") !== -1);
        if (indexOfLine !== -1) {
            let keySignature = "K:" + keySignatureValue;
            let clef = abcStringLines[indexOfLine].split(" ")[1];
            abcStringLines[indexOfLine] = keySignature + " " + clef;
        }
    }
    else if (btnType == "meter") {
        let meterValue = btnElem.dataset.value;
        let indexOfLine = abcStringLines.findIndex(line => line.indexOf("M:") !== -1);
        if (indexOfLine !== -1) {
            abcStringLines[indexOfLine] = "M:" + meterValue;
        }
    }
    abcString = abcStringLines.join("\n");
    draw(sheetName);
}

function getClefValue() {
    let abcStringLines = abcString.split(/\n/);
    let indexOfLine = abcStringLines.findIndex(line => line.indexOf("clef") !== -1);
    let clefValue = "treble";
    if (indexOfLine !== -1) {
        clefValue = abcStringLines[indexOfLine].split(" ")[1].replace("clef=", "");
    }
    return clefValue;
}

// Audio widget scripts
function CursorControl() {
    let self = this;

    self.onReady = function() {
    };
    self.onStart = function() {
        let svg = document.querySelector("#" + sheetName + " svg");
        let cursor = document.createElementNS("http://www.w3.org/2000/svg", "line");
        cursor.setAttribute("class", "abcjs-cursor");
        cursor.setAttributeNS(null, 'x1', 0);
        cursor.setAttributeNS(null, 'y1', 0);
        cursor.setAttributeNS(null, 'x2', 0);
        cursor.setAttributeNS(null, 'y2', 0);
        svg.appendChild(cursor);

    };
    self.beatSubdivisions = 2;
    self.onBeat = function(beatNumber, totalBeats, totalTime) {
    };
    self.onEvent = function(ev) {
        if (ev.measureStart && ev.left === null)
            return; // this was the second part of a tie across a measure line. Just ignore it.

        let lastSelection = document.querySelectorAll("#" + sheetName + " svg .highlight");
        for (let k = 0; k < lastSelection.length; k++)
            lastSelection[k].classList.remove("highlight");

        for (let i = 0; i < ev.elements.length; i++ ) {
            let note = ev.elements[i];
            for (let j = 0; j < note.length; j++) {
                note[j].classList.add("highlight");
            }
        }

        let cursor = document.querySelector("#" + sheetName + " svg .abcjs-cursor");
        if (cursor) {
            cursor.setAttribute("x1", ev.left - 2);
            cursor.setAttribute("x2", ev.left - 2);
            cursor.setAttribute("y1", ev.top);
            cursor.setAttribute("y2", ev.top + ev.height);
        }
    };
    self.onFinished = function() {
        let els = document.querySelectorAll("svg .highlight");
        for (let i = 0; i < els.length; i++ ) {
            els[i].classList.remove("highlight");
        }
        let cursor = document.querySelector("#" + sheetName + " svg .abcjs-cursor");
        if (cursor) {
            cursor.setAttribute("x1", 0);
            cursor.setAttribute("x2", 0);
            cursor.setAttribute("y1", 0);
            cursor.setAttribute("y2", 0);
        }
    };
}

let cursorControl = new CursorControl();

let synthControl;

function createAudio() {
    if (ABCJS.synth.supportsAudio()) {
        synthControl = new ABCJS.synth.SynthController();
        synthControl.load("#audio", cursorControl, {displayRestart: true, displayPlay: true, displayProgress: true});
    } else {
        document.querySelector("#audio").innerHTML = "<div class='audio-error'>Audio is not supported in this browser.</div>";
    }
    setTune(false);
}

function setTune(userAction, withDownload = false) {
    let audioParams = { soundFontVolumeMultiplier: 0.5 };
    synthControl.disable(true);

    let midiBuffer = new ABCJS.synth.CreateSynth();
    midiBuffer.init({
        visualObj: abcVisualObj[0],
        options: {
            soundFontUrl: "/resources/soundfonts/abcjs"
        }
    }).then(function (response) {
        if (synthControl) {
            synthControl.setTune(abcVisualObj[0], userAction, audioParams).then(function (response) {
                if (withDownload) {
                    synthControl.download(abcVisualObj[0].metaText.title);
                }
            }).catch(function (error) {
                console.warn("Audio problem:", error);
            });
        }
    }).catch(function (error) {
        console.warn("Audio problem:", error);
    });
}

function exportFile(type) {
    if (type == "midi") {
        let midi_link_elem = document.getElementById("midi-link");
        midi_link_elem.innerHTML = ABCJS.synth.getMidiFile(abcVisualObj[0], { midiOutputType: 'link', bpm: abcVisualObj[0].metaText.tempo.bpm, fileName: abcVisualObj[0].metaText.title });
        document.querySelector('#midi-link .abcjs-download-midi a').click();
        midi_link_elem.innerHTML = "";
    }
    else if (type == "wav") {
        setTune(true, true);
    }
    else if (type == "print") {
        draw('printable-abcjs-container', { print: true, scale: 1.3 }, false, false);
        let mywindow = window.open();
        mywindow.resizeTo(1244, 1408);
        mywindow.document.write(document.getElementById('printable-abcjs-container').innerHTML);
        mywindow.document.close();
        mywindow.focus();
        mywindow.print();
        document.getElementById('printable-abcjs-container').innerHTML = "";
    }
}