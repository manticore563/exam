<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateQuestionRequest extends FormRequest
{
    public function authorize()
    {
        return auth()->user()->isAdmin();
    }

    public function rules()
    {
        return [
            'exam_id' => 'required|exists:exams,id',
            'text' => 'required|string',
            'type' => 'required|in:mcq,short_answer',
            'options' => 'required_if:type,mcq|array|min:4|max:4',
            'correct_option' => 'required_if:type,mcq|integer|between:0,3',
        ];
    }
}
?>
