<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ImportQuestionsRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'exam_id' => 'required|exists:exams,id',
            'word_file' => 'required|file|mimes:docx|max:2048',
        ];
    }
}
?>
