<div class="row">
    <div>
        <label for="harga">Harga (Rp)</label>
        <input id="harga" type="number" name="harga" min="1" required value="{{ old('harga', $bid?->harga) }}">
    </div>
    <div>
        <label for="durasi">Durasi (hari)</label>
        <input id="durasi" type="number" name="durasi" min="1" max="3650" required value="{{ old('durasi', $bid?->durasi) }}">
    </div>
</div>
<label for="proposal">Proposal</label>
<textarea id="proposal" name="proposal" maxlength="5000" required>{{ old('proposal', $bid?->proposal) }}</textarea>