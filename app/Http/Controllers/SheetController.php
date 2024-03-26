<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use Illuminate\Support\Facades\Auth;

class SheetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sheets = Sheet::all();
        return view('sheet.index', compact('sheets'));
    }

    public function create()
    {
        return view('sheet.create');
    }

    public function store()
    {
        $data = request()->validate(['title' => 'string']);
        $data = array_merge($data, ['user_id' => Auth::id()]);
        $sheet = Sheet::create($data);
        return redirect()->route('sheet.edit', $sheet->id);
    }

    public function settings(Sheet $sheet)
    {
        return view('sheet.settings', compact('sheet'));
    }

    public function settingsUpdate(Sheet $sheet)
    {
        $data = request()->validate(['title' => 'string']);
        $sheet->update($data);
        return redirect()->route('sheet.settings', $sheet->id);
    }

    public function edit(Sheet $sheet)
    {
        if (empty($sheet->content)) {
            $sheet->content = 'X:1\nT:' . $sheet->title . '\nK:D clef=treble\nM:4/4\nL:1/8\nz';
        }
        return view('sheet.edit', compact('sheet'));
    }

    public function update(Sheet $sheet)
    {
        $data = request()->validate(['content' => 'string']);
        $sheet->update($data);
        return redirect()->route('sheet.index');
    }

    public function destroy(Sheet $sheet)
    {
        $sheet->delete();
        return redirect()->route('sheet.index');
    }
}
