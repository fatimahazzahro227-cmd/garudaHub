@extends('layouts.app')

@section('content')
<div class="container auth_box">
    <h2>Masuk ke Garuda-Hub</h2>

    @if ($errors->any())
        <div class="auth_error">
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
        <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus class="auth_input">

        <label for="password">Password</label>
        <input id="password" name="password" type="password" required class="auth_input auth_input_akhir">

        <button type="submit" class="auth_tombol auth_tombol_login">Masuk</button>
    </form>

    <p class="auth_tautan">
        Belum punya akun? <a href="{{ route('register') }}">Daftar di sini</a>
    </p>
</div>
@endsection