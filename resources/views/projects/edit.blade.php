@extends('layouts.app')

@section('title', 'Edit Proyek')

@section('content')
<div class="card">
    <h1>Edit Proyek</h1>
    <form method="POST" action="{{ route('projects.update', $project) }}">
        @csrf
        @method('PUT')
        @include('projects._form')
        <div class="actions">
            <button type="submit">Simpan perubahan</button>
            <a class="btn sec" href="{{ route('projects.show', $project) }}">Batal</a>
        </div>
    </form>
</div>
@endsection
