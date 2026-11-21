@extends('layouts.app')

@section('title', 'Dasbor')

@section('content')
<div class="card"><h1>Penawaran Saya</h1></div>

@forelse ($bids as $bid)
    <div class="card">
        <h2>
            <a href="{{ route('projects.show', $bid->project) }}">{{ $bid->project->judul }}</a>
            @include('partials.status', ['status' => $bid->project->status])
            @if ($bid->is_winner) <span class="badge win">Pemenang</span> @endif
        </h2>
        <p class="muted">
            Klien: {{ $bid->project->client->nama }} | Harga: @rupiah($bid->harga)
            | Durasi: {{ $bid->durasi }} hari | Batas: {{ $bid->project->batas_lelang->format('Y-m-d') }}
        </p>
    </div>
@empty
    <div class="card"><p>Anda belum mengajukan penawaran.</p></div>
@endforelse
@endsection

