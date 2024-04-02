<?php

namespace App\Http\Controllers;

use App\Helpers\Export\Export;

class ExportController extends Controller
{
    public function export()
    {
        $data = request()->validate(['type' => 'string', 'midi_file' => 'mimetypes:audio/midi']);
        switch ($data['type']) {
            case 'gp5':
                $output_file = Export::exportGp5($data['midi_file']);
                break;
            case 'MusicXML':
                $output_file = Export::exportMusicXML($data['midi_file']);
                break;
        }
        return true;
        // return response()->download($output_file->path(), $output_file->getClientOriginalName(), ['Content-Type: audio/midi']);
    }
}
