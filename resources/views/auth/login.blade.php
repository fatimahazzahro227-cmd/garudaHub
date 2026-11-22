@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 400px; margin: 40px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
    <h2>Masuk ke Garuda-Hub</h2>

    @if ($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <label for="email">Alamat Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus style="width: 100%; padding: 8px; margin-bottom: 12px;">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required style="width: 100%; padding: 8px; margin-bottom: 20px;">

        <button type="submit" style="width: 100%; padding: 10px; background: #800000; color: white; border: none; border-radius: 4px; cursor: pointer;">Masuk</button>
    </form>

    <p style="text-align: center; margin-top: 15px;">
        Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
    </p>
</div>
@endsection