<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use App\Models\SheetUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SheetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $sheets = Auth::user()->sheets;
        $accessed_sheets = Auth::user()->accessedSheets;
        return view('sheet.index', compact('sheets', 'accessed_sheets'));
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

    public function edit(Sheet $sheet)
    {
        if (empty($sheet->content)) {
            $sheet->content = 'X:1\nT:' . $sheet->title . '\nQ:120\nK:D clef=treble\nM:4/4\nL:1/8\nz';
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

    public function settings(Sheet $sheet)
    {
        $accessed_users = $sheet->accessedUsers;
        return view('sheet.settings', compact('sheet', 'accessed_users'));
    }

    public function settingsUpdate(Sheet $sheet)
    {
        $data = request()->validate(['title' => 'string', 'is_published' => 'boolean']);
        $content_lines = explode('\n', $sheet->content);
        foreach ($content_lines as &$line) {
            if (str_contains($line, 'T:')) {
                $line = 'T:' . $data['title'];
                break;
            }
        }
        $data['content'] = implode('\n', $content_lines);
        $sheet->update($data);
        return redirect()->route('sheet.settings', $sheet->id);
    }

    public function grantAccess(Sheet $sheet)
    {
        $validator = Validator::make(request()->all(), ['email' => 'string']);
        $data = $validator->validated();
        $user_id = User::where('email', $data['email'])->value('id');
        if ($user_id) {
            if ($sheet->user->id !== $user_id) {
                SheetUser::firstOrCreate(['sheet_id' => $sheet->id, 'user_id' => $user_id]);
            }
            else {
                $validator->errors()->add('email', __('app.You cannot specify the owner\'s email address'));
            }
        }
        else {
            $validator->errors()->add('email', __('app.The user with the specified email does not exist'));
        }

        if (!$validator->errors()->isEmpty()) {
            return redirect()->route('sheet.settings', $sheet->id)->withErrors($validator)->withInput();
        }

        return redirect()->route('sheet.settings', $sheet->id);
    }

    public function revokeAccess(Sheet $sheet)
    {
        $data = request()->validate(['user_id' => 'integer']);
        SheetUser::where('sheet_id', $sheet->id)->where('user_id', $data['user_id'])->delete();
        return redirect()->route('sheet.settings', $sheet->id);
    }
}
