<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateExamRequest;
use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        $exams = Exam::with('questions')->where('user_id', auth()->id())->get();
        return view('exams.index', compact('exams'));
    }

    public function create()
    {
        return view('exams.create');
    }

    public function store(CreateExamRequest $request)
    {
        $exam = Exam::create($request->validated() + ['user_id' => auth()->id()]);
        return redirect()->route('exams.index')->with('success', 'Exam created successfully.');
    }

    public function show(Exam $exam)
    {
        $exam->load('questions.options');
        return view('exams.show', compact('exam'));
    }

    public function studentIndex()
    {
        $exams = Exam::where('start_time', '<=', now())
                     ->where('end_time', '>=', now())
                     ->get();
        return view('exams.index', compact('exams'));
    }

    public function takeExam(Exam $exam)
    {
        $exam->load('questions.options');
        return view('exams.show', compact('exam'));
    }

    public function submitExam(Request $request, Exam $exam)
    {
        $score = 0;
        foreach ($request->input('answers', []) as $questionId => $optionId) {
            $option = \App\Models\Option::find($optionId);
            if ($option && $option->is_correct) {
                $score++;
            }
        }

        $exam->results()->create([
            'user_id' => auth()->id(),
            'score' => $score,
            'submitted_at' => now(),
        ]);

        return redirect()->route('student.results')->with('success', 'Exam submitted successfully.');
    }
}
?>
