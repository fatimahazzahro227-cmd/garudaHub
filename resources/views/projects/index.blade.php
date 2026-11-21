@extends('layouts.app')

@section('title', 'Proyek')

@section('content')
<div class="card">
    <h1>Daftar Proyek</h1>
    <form class="search" method="GET" action="{{ route('home') }}">
        <input name="q" maxlength="100" placeholder="Cari judul atau kategori..." value="{{ $q }}">
        <button type="submit">Cari</button>
    </form>
    @if ($q !== '')
        <p class="muted">Hasil pencarian untuk: <b>{{ $q }}</b> ({{ $projects->total() }} proyek)</p>
    @endif
</div>

@forelse ($projects as $project)
    <div class="card">
        <h2>
            <a href="{{ route('projects.show', $project) }}">{{ $project->judul }}</a>
            @include('partials.status', ['status' => $project->status])
        </h2>
        <p class="muted">
            Kategori: {{ $project->kategori }} | Klien: {{ $project->client->nama }}
            | Budget: @rupiah(project->budgetmin)-@rupiah(project->budget_max)
            | Batas: {{ $project->batas_lelang->format('Y-m-d') }} | {{ $project->bids_count }} penawaran
        </p>
        <p>{{ Str::limit($project->deskripsi, 160) }}</p>
    </div>
@empty
    <div class="card"><p>Belum ada proyek yang cocok.</p></div>
@endforelse

{{ $projects->links() }}
@endsection
