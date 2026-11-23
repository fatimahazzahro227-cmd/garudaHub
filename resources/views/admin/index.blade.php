@extends('layouts.app')

@section('title', 'Admin')

@section('content')
<div class="card">
    <h1>Kelola User</h1>
    <table>
        <tr><th>ID</th><th>Nama</th><th>Email</th><th>Peran</th><th>Proyek</th><th>Penawaran</th><th></th></tr>
        @foreach ($users as $u)
            <tr>
                <td>{{ $u->id }}</td>
                <td>{{ $u->nama }}</td>
                <td>{{ $u->email }}</td>
                <td>
                    @if ($u->is(auth()->user()))
                        {{ $u->role }} <span class="muted">(Anda)</span>
                    @else
                        <form class="inline" method="POST" action="{{ route('admin.users.role', $u) }}">
                            @csrf
                            @method('PATCH')
                            <select name="role">
                                @foreach (\App\Models\User::ROLES as $role)
                                    <option value="{{ $role }}" @selected($u->role === $role)>{{ $role }}</option>
                                @endforeach
                            </select>
                            <button class="sm sec" type="submit">Ubah</button>
                        </form>
                    @endif
                </td>
                <td>{{ $u->projects_count }}</td>
                <td>{{ $u->bids_count }}</td>
                <td>
                    @unless ($u->is(auth()->user()))
                        <form class="inline" method="POST" action="{{ route('admin.users.destroy', $u) }}"
                              data-confirm=('Hapus user ini beserta proyek & penawarannya?')">
                            @csrf
                            @method('DELETE')
                            <button class="sm danger" type="submit">Hapus</button>
                        </form>
                    @endunless
                </td>
            </tr>
        @endforeach
    </table>
</div>

<div class="card">
    <h1>Kelola Proyek</h1>
    <table>
        <tr><th>ID</th><th>Judul</th><th>Klien</th><th>Status</th><th>Penawaran</th><th></th></tr>
        @foreach ($projects as $p)
            <tr>
                <td>{{ $p->id }}</td>
                <td><a href="{{ route('projects.show', $p) }}">{{ $p->judul }}</a></td>
                <td>{{ $p->client->nama }}</td>
                <td>@include('partials.status', ['status' => $p->status])</td>
                <td>{{ $p->bids_count }}</td>
                <td>
                    <form class="inline" method="POST" action="{{ route('projects.destroy', $p) }}"
                          data-confirm=('Hapus proyek ini?')">
                        @csrf
                        @method('DELETE')
                        <button class="sm danger" type="submit">Hapus</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</div>
@endsection