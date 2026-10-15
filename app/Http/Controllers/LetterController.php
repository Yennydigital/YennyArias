<?php

namespace App\Http\Controllers;

use App\Models\Letter;
use Illuminate\Http\Request;

class LetterController extends Controller
{
    public function index(Request $request)
    {
        return view('letters.index', [
            'letters' => $request->user()->letters()->latest()->get(),
        ]);
    }

    public function destroy(Request $request, Letter $letter)
    {
        abort_unless($letter->user_id === $request->user()->id, 403);
        $letter->delete();

        return redirect()->route('letters.index');
    }
}
