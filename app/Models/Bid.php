<?php

namespace App\Models;

use Database\Factories\BidFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bid extends Model
{

    use HasFactory;

    // project_id, developer_id & is_winner diisi oleh controller
    protected $fillable = ['harga', 'durasi', 'proposal'];

    protected function casts(): array
    {
        return [
            'project_id' => 'integer',
            'developer_id' => 'integer',
            'harga' => 'integer',
            'durasi' => 'integer',
            'is_winner' => 'boolean',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'developer_id');
    }
}

