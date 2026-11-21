<label for="judul">Judul</label>
<input id="judul" name="judul" maxlength="200" required value="{{ old('judul', $project->judul) }}">

<label for="deskripsi">Deskripsi</label>
<textarea id="deskripsi" name="deskripsi" maxlength="5000" required>{{ old('deskripsi', $project->deskripsi) }}</textarea>

<label for="kategori">Kategori</label>
<input id="kategori" name="kategori" maxlength="50" placeholder="Web, Mobile, Desain, Data, AI..." required
       value="{{ old('kategori', $project->kategori) }}">

<div class="row">
    <div>
        <label for="budget_min">Budget minimum (Rp)</label>
        <input id="budget_min" type="number" name="budget_min" min="0" required value="{{ old('budget_min', $project->budget_min) }}">
    </div>
    <div>
        <label for="budget_max">Budget maksimum (Rp)</label>
        <input id="budget_max" type="number" name="budget_max" min="0" required value="{{ old('budget_max', $project->budget_max) }}">
    </div>
    <div>
        <label for="batas_lelang">Batas lelang</label>
        <input id="batas_lelang" type="date" name="batas_lelang" required
               value="{{ old('batas_lelang', $project->batas_lelang?->format('Y-m-d')) }}">
    </div>
</div>
