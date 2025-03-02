@extends('layouts.app')

@section('content')
    <div class="bg-white p-6 rounded shadow-md">
        <h2 class="text-2xl font-bold mb-4">{{ auth()->user()->isAdmin() ? 'All Results' : 'My Results' }}</h2>
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200">
                    <th class="p-2 border">Exam</th>
                    <th class="p-2 border">Student</th>
                    <th class="p-2 border">Score</th>
                    <th class="p-2 border">Submitted At</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($results as $result)
                    <tr>
                        <td class="p-2 border">{{ $result->exam->title }}</td>
                        <td class="p-2 border">{{ auth()->user()->isAdmin() ? $result->user->name : auth()->user()->name }}</td>
                        <td class="p-2 border">{{ $result->score }}</td>
                        <td class="p-2 border">{{ $result->submitted_at ?? 'N/A' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-2 border text-center">No results available.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection