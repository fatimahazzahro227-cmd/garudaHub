@extends('layouts.app')

@section('title', 'Pasang Proyek')

@section('content')
<div class="card">
    <h1>Pasang Proyek</h1>
    <form method="POST" action="{{ route('projects.store') }}">
        @csrf
        @include('projects._form')
        <p><button type="submit">Pasang proyek</button></p>
    </form>
</div>
@endsection
