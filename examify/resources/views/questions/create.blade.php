@extends('layouts.app')

@section('content')
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-bold mb-4">Add Question to {{ $exam->title }}</h2>
        <form method="POST" action="{{ route('questions.store') }}">
            @csrf
            <input type="hidden" name="exam_id" value="{{ $exam->id }}">
            <div class="mb-4">
                <label for="text" class="block text-gray-700">Question Text</label>
                <textarea name="text" id="text" class="w-full p-2 border rounded" required></textarea>
                @error('text')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="type" class="block text-gray-700">Type</label>
                <select name="type" id="type" class="w-full p-2 border rounded" required>
                    <option value="mcq">Multiple Choice</option>
                    <option value="short_answer">Short Answer</option>
                </select>
            </div>
            <div id="mcq-options" class="mb-4 hidden">
                <label class="block text-gray-700">Options</label>
                @for ($i = 0; $i < 4; $i++)
                    <input type="text" name="options[]" class="w-full p-2 border rounded mb-2" placeholder="Option {{ $i + 1 }}">
                @endfor
                <label for="correct_option" class="block text-gray-700">Correct Option (0-3)</label>
                <input type="number" name="correct_option" id="correct_option" class="w-full p-2 border rounded" min="0" max="3">
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Add Question</button>
        </form>
    </div>

    <script>
        $('#type').change(function() {
            $('#mcq-options').toggleClass('hidden', this.value !== 'mcq');
        });
    </script>
@endsection