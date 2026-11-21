@extends('layouts.app')

@section('title', 'Dasbor')

@section('content')
<div class="card">
    <h1>Proyek Saya</h1>
    <p><a href="{{ route('projects.create') }}">Pasang proyek baru</a></p>
</div>

@forelse ($projects as $project)
    <div class="card">
        <h2>
            <a href="{{ route('projects.show', $project) }}">{{ $project->judul }}</a>
            @include('partials.status', ['status' => $project->status])
        </h2>
        <p class="muted">
            Budget: @rupiah($project->budget_min) - @rupiah($project->budget_max)
            | Batas: {{ $project->batas_lelang->format('Y-m-d') }} | {{ $project->bids_count }} penawaran
        </p>
    </div>
@empty
    <div class="card"><p>Anda belum memasang proyek.</p></div>
@endforelse
@endsection