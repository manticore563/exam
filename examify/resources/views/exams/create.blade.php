@extends('layouts.app')

@section('content')
    <div class="max-w-lg mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-bold mb-4">Create Exam</h2>
        <form method="POST" action="{{ route('exams.store') }}">
            @csrf
            <div class="mb-4">
                <label for="title" class="block text-gray-700">Title</label>
                <input type="text" name="title" id="title" class="w-full p-2 border rounded" required>
                @error('title')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="description" class="block text-gray-700">Description</label>
                <textarea name="description" id="description" class="w-full p-2 border rounded"></textarea>
            </div>
            <div class="mb-4">
                <label for="duration" class="block text-gray-700">Duration (minutes)</label>
                <input type="number" name="duration" id="duration" class="w-full p-2 border rounded" required>
                @error('duration')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="start_time" class="block text-gray-700">Start Time</label>
                <input type="datetime-local" name="start_time" id="start_time" class="w-full p-2 border rounded" required>
                @error('start_time')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <label for="end_time" class="block text-gray-700">End Time</label>
                <input type="datetime-local" name="end_time" id="end_time" class="w-full p-2 border rounded" required>
                @error('end_time')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <button type="submit" class="bg-blue-500 text-white p-2 rounded w-full">Create Exam</button>
        </form>
    </div>
@endsection