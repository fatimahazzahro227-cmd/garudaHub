@extends('layouts.app')

@section('content')
<div class="container auth_box auth_box_lebar">
    <h2>Daftar Akun Garuda-Hub</h2>

    @if ($errors->any())
        <div class="auth_error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <label for="name">Nama Lengkap</label>
        <input id="name" name="nama" type="text" value="{{ old('nama') }}" required autofocus class="auth_input">

        <label for="email">Alamat Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required class="auth_input">

        <label for="password">Password (min. 6 karakter)</label>
        <input id="password" name="password" type="password" minlength="6" required class="auth_input">

        <label for="password_confirmation">Konfirmasi Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required class="auth_input">

        <label for="role">Peran (Role)</label>
        <select id="role" name="role" class="auth_input auth_input_akhir">
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected(old('role', 'klien') === $role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>

        <button type="submit" class="auth_tombol auth_tombol_daftar">Daftar Sekarang</button>
    </form>
    
    <p class="auth_tautan">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </p>
</div>
@endsection