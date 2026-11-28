@extends('layouts.app')

@section('title', $project->judul)

@section('content')
@php($user = auth()->user())
<div class="card">
    <h1>{{ $project->judul }} @include('partials.status', ['status' => $project->status])</h1>
    <p class="muted">
        Kategori: {{ $project->kategori }} | Klien: {{ $project->client->nama }}
        | Budget: @rupiah($project->budget_min) - @rupiah($project->budget_max)
        | Batas lelang: {{ $project->batas_lelang->format('Y-m-d') }}
    </p>
    <p>{!! nl2br(e($project->deskripsi)) !!}</p>

    <div class="actions">
        @can('update', $project)
            <a class="btn sec sm" href="{{ route('projects.edit', $project) }}">Edit proyek</a>
        @endcan
        @can('delete', $project)
            <form class="inline" method="POST" action="{{ route('projects.destroy', $project) }}"
                  data-confirm="Hapus proyek ini beserta semua penawarannya?">
                @csrf
                @method('DELETE')
                <button class="sm danger" type="submit">Hapus proyek</button>
            </form>
        @endcan
    </div>
</div>

<div class="card">
    <h2>Penawaran ({{ $project->bids->count() }})</h2>
    @if ($project->bids->isEmpty())
        <p>Belum ada penawaran.</p>
    @else
        <table>
            <tr><th>Developer</th><th>Harga</th><th>Durasi</th><th>Proposal</th><th></th></tr>
            @foreach ($project->bids as $bid)
                <tr class="{{ $bid->is_winner ? 'winner' : '' }}">
                    <td>
                        {{ $bid->developer->nama }}
                        @if ($bid->developer->ratings_count > 0)
                            <span class="muted">★ {{ number_format($bid->developer->ratings_avg_skor, 1) }} ({{ $bid->developer->ratings_count }})</span>
                        @endif
                        @if ($bid->is_winner) <span class="badge win">Pemenang</span> @endif
                    </td>
                    <td>@rupiah($bid->harga)</td>
                    <td>{{ $bid->durasi }} hari</td>
                    <td>{!! nl2br(e($bid->proposal)) !!}</td>
                    <td>
                        @can('pickWinner', $project)
                            <form method="POST" action="{{ route('bids.pick', [$project, $bid]) }}"
                                 data-confirm="Pilih penawaran ini sebagai pemenang?">
                                @csrf
                                <button class="sm" type="submit">Pilih pemenang</button>
                            </form>
                        @endcan
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
</div>

@if ($user && $user->role === 'developer')
    @if ($myBid)
        <div class="card">
            <h2>Penawaran Anda</h2>
            <p class="muted">@rupiah($myBid->harga) · {{ $myBid->durasi }} hari</p>
            @can('update', $myBid)
                <div class="actions">
                    <a class="btn sec sm" href="{{ route('bids.edit', $myBid) }}">Ubah penawaran</a>
                    <form class="inline" method="POST" action="{{ route('bids.destroy', $myBid) }}"
                        data-confirm="Tarik penawaran ini?">
                        @csrf
                        @method('DELETE')
                        <button class="sm danger" type="submit">Tarik penawaran</button>
                    </form>
                </div>
            @endcan
        </div>
    @elseif ($project->isOpen())
        <div class="card">
            <h2>Ajukan Penawaran</h2>
            <form method="POST" action="{{ route('bids.store', $project) }}">
                @csrf
                @include('bids._form', ['bid' => null])
                <p><button type="submit">Kirim penawaran</button></p>
            </form>
        </div>
    @else
        <div class="card"><p class="muted">Lelang sudah ditutup; penawaran tidak dapat diajukan.</p></div>
    @endif
@elseif (! $user && $project->isOpen())
    <div class="card"><p><a href="{{ route('login') }}">Masuk</a> sebagai developer untuk mengajukan penawaran.</p></div>
@endif

@can('rate', $project)
    <div class="card">
        <h2>Beri rating untuk {{ $project->winningBid->developer->nama }}</h2>
        <form method="POST" action="{{ route('ratings.store', $project) }}">
            @csrf
            <p>
                <label>Skor
                    <select name="skor" required>
                        @for ($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}">{{ $i }}</option>
                        @endfor
                    </select>
                </label>
            </p>
            <p><label>Ulasan (opsional) <textarea name="ulasan" rows="3" maxlength="500">{{ old('ulasan') }}</textarea></label></p>
            <p><button type="submit">Kirim rating</button></p>
        </form>
    </div>
@endcan

@if ($project->rating)
    <div class="card">
        <h2>Rating</h2>
        <p>★ {{ $project->rating->skor }}/5</p>
        @if ($project->rating->ulasan)
            <p>{!! nl2br(e($project->rating->ulasan)) !!}</p>
        @endif
    </div>
@endif
@endsection