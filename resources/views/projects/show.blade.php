@extends('layouts.app')

@section('title', $project->judul)

@section('content')
<div class="card">
    <h1>{{ $project->judul }} @include('partials.status', ['status' => $project->status])</h1>
    <p class="muted">
        Kategori: {{ $project->kategori }} | Klien: {{ $project->client->nama }}
        | Budget: @rupiah(project->budgetmin)-@rupiah(project->budget_max)
        | Batas lelang: {{ $project->batas_lelang->format('Y-m-d') }}
    </p>
    <p>{!! nl2br(e($project->deskripsi)) !!}</p>
        <div class="actions">
        @can('update', $project)
            <a class="btn sec sm" href="{{ route('projects.edit', $project) }}">Edit proyek</a>
        @endcan
        @can('delete', $project)
            <form class="inline" method="POST" action="{{ route('projects.destroy', $project) }}"
                  onsubmit="return confirm('Hapus proyek ini beserta semua penawarannya?')">
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
            <tr><th>Developer</th><th>Harga</th><th>Durasi</th><th>Proposal</th></tr>
            @foreach ($project->bids as $bid)
                <tr class="{{ $bid->is_winner ? 'winner' : '' }}">
                    <td>
                        {{ $bid->developer->nama }}
                        @if ($bid->is_winner) <span class="badge win">Pemenang</span> @endif
                    </td>
                    <td>@rupiah($bid->harga)</td>
                    <td>{{ $bid->durasi }} hari</td>
                    <td>{!! nl2br(e($bid->proposal)) !!}</td>
                </tr>
            @endforeach
        </table>
    @endif
</div>
@endsection
