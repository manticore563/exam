<?php

namespace App\Http\Controllers;

use App\Models\Result;

class ResultController extends Controller
{
    public function index()
    {
        $results = Result::with(['exam', 'user'])->get();
        return view('results.index', compact('results'));
    }

    public function studentIndex()
    {
        $results = Result::where('user_id', auth()->id())->with('exam')->get();
        return view('results.index', compact('results'));
    }
}
?>
