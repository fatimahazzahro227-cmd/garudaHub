@extends('layouts.app')

@section('content')
<div class="container" style="max-width: 500px; margin: 40px auto; padding: 20px; border: 1px solid #ddd; border-radius: 8px;">
    <h2>Daftar Akun Garuda-Hub</h2>

    @if ($errors->any())
        <div style="background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 4px;">
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
    <input id="name" name="nama" type="text" value="{{ old('nama') }}" required autofocus style="width: 100%; padding: 8px; margin-bottom: 12px;">

        <label for="email">Alamat Email</label>
        <input id="email" name="email" type="email" value="{{ old('email') }}" required style="width: 100%; padding: 8px; margin-bottom: 12px;">

        <label for="password">Password (min. 6 karakter)</label>
        <input id="password" name="password" type="password" minlength="6" required style="width: 100%; padding: 8px; margin-bottom: 12px;">

        <label for="password_confirmation">Konfirmasi Password</label>
        <input id="password_confirmation" name="password_confirmation" type="password" required style="width: 100%; padding: 8px; margin-bottom: 12px;">

        <label for="role">Peran (Role)</label>
        <select id="role" name="role" style="width: 100%; padding: 8px; margin-bottom: 20px;">
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected(old('role', 'klien') === $role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>

        <button type="submit" style="width: 100%; padding: 10px; background: #0d6efd; color: white; border: none; border-radius: 4px; cursor: pointer;">Daftar Sekarang</button>
    </form>
    
    <p style="text-align: center; margin-top: 15px;">
        Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
    </p>
</div>
@endsection