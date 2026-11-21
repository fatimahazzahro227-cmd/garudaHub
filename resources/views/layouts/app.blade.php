<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>@yield('title', 'Proyek') - Garuda-Hub</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<header>
    <div class="in">
        <a class="brand" href="{{ route('home') }}">Garuda-Hub</a>
        <a href="{{ route('home') }}">Proyek</a>
    </div>
</header>
<main>
    @if (session('ok'))
        <div class="flash ok">{{ session('ok') }}</div>
    @endif
    @if (session('err'))
        <div class="flash err">{{ session('err') }}</div>
    @endif
    @if ($errors->any())
        <div class="flash err">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
</main>
<footer>Garuda-Hub - aplikasi pembelajaran (Laravel MVC). Hanya data dummy.</footer>
</body>
</html>
