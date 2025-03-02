@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-bold mb-4">{{ auth()->user()->isAdmin() ? 'Manage Exams' : 'Available Exams' }}</h2>
        @if (auth()->user()->isAdmin())
            <a href="{{ route('exams.create') }}" class="bg-blue-500 text-white p-2 rounded mb-4 inline-block">Create Exam</a>
        @endif
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($exams as $exam)
                <div class="border p-4 rounded">
                    <h3 class="text-lg font-semibold">{{ $exam->title }}</h3>
                    <p>{{ $exam->description }}</p>
                    <p>Duration: {{ $exam->duration }} minutes</p>
                    <p>Starts: {{ $exam->start_time }}</p>
                    <p>Ends: {{ $exam->end_time }}</p>
                    <a href="{{ route(auth()->user()->isAdmin() ? 'exams.show' : 'student.exam.take', $exam) }}"
                       class="bg-green-500 text-white p-2 rounded mt-2 inline-block">
                        {{ auth()->user()->isAdmin() ? 'View' : 'Take Exam' }}
                    </a>
                </div>
            @empty
                <p>No exams available.</p>
            @endforelse
        </div>
    </div>
@endsection