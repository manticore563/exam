<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateQuestionRequest;
use App\Http\Requests\ImportQuestionsRequest;
use App\Models\Exam;
use App\Models\Question;
use App\Models\Option;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;

class QuestionController extends Controller
{
    public function create(Exam $exam)
    {
        return view('questions.create', compact('exam'));
    }

    public function store(CreateQuestionRequest $request)
    {
        $question = Question::create($request->validated());
        if ($request->type === 'mcq') {
            foreach ($request->options as $index => $text) {
                Option::create([
                    'question_id' => $question->id,
                    'text' => $text,
                    'is_correct' => $index == $request->correct_option,
                ]);
            }
        }
        return redirect()->route('exams.show', $question->exam_id)->with('success', 'Question added.');
    }

    public function showImportForm()
    {
        $exams = Exam::where('user_id', auth()->id())->get();
        return view('questions.import', compact('exams'));
    }

    public function import(ImportQuestionsRequest $request)
    {
        $file = $request->file('word_file');
        $exam = Exam::find($request->exam_id);

        $phpWord = IOFactory::load($file->path());
        $text = '';
        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {
                if (method_exists($element, 'getText')) {
                    $text .= $element->getText() . "\n";
                }
            }
        }

        // Simple parsing: assumes format "Q: [text] A: [opt1] B: [opt2] C: [opt3] D: [opt4] Correct: [letter]"
        $lines = explode("\n", $text);
        $question = null;
        $options = [];

        foreach ($lines as $line) {
            if (preg_match('/^Q:\s*(.+)$/', $line, $matches)) {
                if ($question && $options) {
                    $this->saveQuestion($exam, $question, $options);
                }
                $question = $matches[1];
                $options = [];
            } elseif (preg_match('/^[A-D]:\s*(.+)$/', $line, $matches)) {
                $options[] = $matches[1];
            } elseif (preg_match('/^Correct:\s*([A-D])$/', $line, $matches)) {
                $correct = ord($matches[1]) - ord('A');
                $options[$correct] .= ' (correct)';
            }
        }

        if ($question && $options) {
            $this->saveQuestion($exam, $question, $options);
        }

        return redirect()->route('exams.show', $exam)->with('success', 'Questions imported.');
    }

    private function saveQuestion($exam, $text, $options)
    {
        $question = Question::create([
            'exam_id' => $exam->id,
            'text' => $text,
            'type' => 'mcq',
        ]);

        foreach ($options as $index => $optionText) {
            Option::create([
                'question_id' => $question->id,
                'text' => str_replace(' (correct)', '', $optionText),
                'is_correct' => str_contains($optionText, '(correct)') ? 1 : 0,
            ]);
        }
    }
}
?>
