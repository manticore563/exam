<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Examify') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
</head>
<body class="bg-gray-100 font-sans">
    <!-- Navigation -->
    <nav class="bg-blue-600 p-4 text-white">
        <div class="container mx-auto flex justify-between items-center flex-col sm:flex-row">
            <a href="{{ route('home') }}" class="text-xl font-bold">{{ config('app.name') }}</a>
            @auth
                <div class="mt-2 sm:mt-0 space-y-2 sm:space-y-0 sm:space-x-4 flex flex-col sm:flex-row items-center">
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('exams.index') }}" class="hover:underline">Exams</a>
                        <a href="{{ route('questions.import') }}" class="hover:underline">Import Questions</a>
                        <a href="{{ route('results.index') }}" class="hover:underline">Results</a>
                        <a href="{{ route('admin.reset-password') }}" class="hover:underline">Reset Password</a>
                    @else
                        <a href="{{ route('student.exams') }}" class="hover:underline">Exams</a>
                        <a href="{{ route('student.results') }}" class="hover:underline">Results</a>
                    @endif
                    <form action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="hover:underline">Logout</button>
                    </form>
                </div>
            @else
                <div class="mt-2 sm:mt-0 space-x-4">
                    <a href="{{ route('login') }}" class="hover:underline">Login</a>
                    <a href="{{ route('register') }}" class="hover:underline">Register</a>
                </div>
            @endauth
        </div>
    </nav>

    <!-- Main content -->
    <div class="container mx-auto p-4">
        @if (session('error'))
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
        @yield('content')
    </div>
</body>
</html>