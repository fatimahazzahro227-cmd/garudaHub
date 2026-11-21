@extends('layouts.app')

@section('title', 'Daftar')

@section('content')
<div class="card">
    <h1>Daftar</h1>
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <label for="nama">Nama</label>
        <input id="nama" name="nama" maxlength="100" required value="{{ old('nama') }}">

        <label for="email">Email</label>
        <input id="email" name="email" type="email" maxlength="100" required value="{{ old('email') }}">

        <label for="password">Password (min. 6 karakter)</label>
        <input id="password" name="password" type="password" minlength="6" maxlength="72" required autocomplete="new-password">

        <label for="role">Peran</label>
        <select id="role" name="role">
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected(old('role', 'klien') === $role)>{{ ucfirst($role) }}</option>
            @endforeach
        </select>

        <p><button type="submit">Daftar</button></p>
    </form>
</div>
@endsection
