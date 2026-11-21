@extends('layouts.app')

@section('title', 'Ubah Penawaran')

@section('content')
<div class="card">
    <h1>Ubah Penawaran</h1>
    <p class="muted">Proyek: <a href="{{ route('projects.show', $bid->project) }}">{{ $bid->project->judul }}</a></p>
    <form method="POST" action="{{ route('bids.update', $bid) }}">
        @csrf
        @method('PUT')
        @include('bids._form', ['bid' => $bid])
        <div class="actions">
            <button type="submit">Simpan</button>
            <a class="btn sec" href="{{ route('projects.show', $bid->project) }}">Batal</a>
        </div>
    </form>
</div>
@endsection
