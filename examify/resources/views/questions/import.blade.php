@extends('layouts.app')

@section('content')
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-bold mb-4">Import Questions from Word</h2>
        <p class="mb-4">Format: "Q: [question] A: [opt1] B: [opt2] C: [opt3] D: [opt4] Correct: [A-D]"</p>
        <form method="POST" action="{{ route('questions.import.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-4">
                <label for="exam_id" class="block text-gray-700">Select Exam</label>
                <select name="exam_id" id="exam_id" class="w-full p-2 border rounded" required>
                    @foreach ($exams as $exam)
                        <option value="{{ $exam->id }}">{{ $exam->title }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label for="word_file" class="block text-gray-700">Upload Word File (.docx)</label>
                <input type="file" name="word_file" id="word_file" class="w-full p-2 border rounded" accept=".docx" required>
                @error('word_file')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Import Questions</button>
        </form>
    </div>
@endsection