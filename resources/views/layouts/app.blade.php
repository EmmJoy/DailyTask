<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Daily' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fraunces:opsz,wght@9..144,560;9..144,640&family=Outfit:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @auth
        <div class="shell">
            <header class="topbar">
                <a class="brand" href="{{ route('records.index') }}">
                    <span class="mark">D</span>
                    <strong>Daily</strong>
                </a>
                <nav class="nav">
                    <a href="{{ route('records.index') }}">Records</a>
                    <a class="button" href="{{ route('records.create') }}">New entry</a>
                    <span class="who">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="ghost" type="submit">Log out</button>
                    </form>
                </nav>
            </header>
            <main>
                @if (session('status'))
                    <div class="banner">{{ session('status') }}</div>
                @endif
                @yield('content')
            </main>
        </div>
    @else
        @yield('content')
    @endauth
</body>
</html>
