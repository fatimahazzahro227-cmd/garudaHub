<?php

namespace App\Models;

use Database\Factories\ProjectFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Project extends Model
{
    /** @use HasFactory<ProjectFactory> */
    use HasFactory;

    public const STATUS_OPEN = 'terbuka';
    public const STATUS_CLOSED = 'ditutup';

    protected $fillable = ['judul', 'deskripsi', 'kategori', 'budget_min', 'budget_max', 'batas_lelang'];

    protected $attributes = ['status' => self::STATUS_OPEN];

    protected function casts(): array
    {
        return [
            'client_id' => 'integer',
            'batas_lelang' => 'date',
            'budget_min' => 'integer',
            'budget_max' => 'integer',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function bids(): HasMany
    {
        return $this->hasMany(Bid::class);
    }

    public function winningBid(): HasOne
    {
        return $this->hasOne(Bid::class)->where('is_winner', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        $term = trim((string) $term);
        if ($term === '') {
            return $query;
        }
        $like = '%'.addcslashes($term, '\\%_').'%';

        return $query->where(fn (Builder $q) => $q->where('judul', 'like', $like)->orWhere('kategori', 'like', $like));
    }

    public function isOpen(): bool
    {
        return $this->status === self::STATUS_OPEN && $this->batas_lelang->endOfDay()->isFuture();
    }

    public function rating()
    {
    return $this->hasOne(Rating::class);
    }
}