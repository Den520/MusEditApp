<?php

namespace App\Http\Controllers;

use App\Models\Sheet;

class FeedController extends Controller
{
    public function index()
    {
        $sheets = Sheet::where('is_published', true)->get();
        return view('feed.index', compact('sheets'));
    }

    public function show(Sheet $sheet)
    {
        if (empty($sheet->content)) {
            $sheet->content = 'X:1\nT:' . $sheet->title . '\nK:D clef=treble\nM:4/4\nL:1/8\nz';
        }
        return view('feed.show', compact('sheet'));
    }
}