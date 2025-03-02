@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-bold mb-4">{{ $exam->title }}</h2>
        <p>{{ $exam->description }}</p>
        <p>Duration: {{ $exam->duration }} minutes</p>
        <p>Starts: {{ $exam->start_time }}</p>
        <p>Ends: {{ $exam->end_time }}</p>

        @if (auth()->user()->isAdmin())
            <a href="{{ route('questions.create', $exam) }}" class="bg-blue-500 text-white p-2 rounded mt-4 inline-block">Add Question</a>
            <h3 class="text-xl font-semibold mt-4">Questions</h3>
            <ul class="list-disc pl-5">
                @forelse ($exam->questions as $question)
                    <li>{{ $question->text }}
                        <ul class="list-circle pl-5">
                            @foreach ($question->options as $option)
                                <li>{{ $option->text }} {{ $option->is_correct ? '(Correct)' : '' }}</li>
                            @endforeach
                        </ul>
                    </li>
                @empty
                    <li>No questions yet.</li>
                @endforelse
            </ul>
        @else
            @if (now()->between($exam->start_time, $exam->end_time))
                <form method="POST" action="{{ route('student.exam.submit', $exam) }}" class="mt-4">
                    @csrf
                    @foreach ($exam->questions as $question)
                        <div class="mb-4">
                            <p class="font-semibold">{{ $question->text }}</p>
                            @if ($question->type === 'mcq')
                                @foreach ($question->options as $option)
                                    <label class="block">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $option->id }}" required>
                                        {{ $option->text }}
                                    </label>
                                @endforeach
                            @else
                                <textarea name="answers[{{ $question->id }}]" class="w-full p-2 border rounded" required></textarea>
                            @endif
                        </div>
                    @endforeach
                    <button type="submit" class="bg-green-500 text-white p-2 rounded">Submit Exam</button>
                </form>
            @else
                <p class="text-red-500 mt-4">This exam is not currently active.</p>
            @endif
        @endif
    </div>
@endsection