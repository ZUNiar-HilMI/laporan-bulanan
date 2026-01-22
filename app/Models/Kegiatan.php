<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kegiatan extends Model
{
    protected $fillable = [
        'user_id',
        'nama_kegiatan',
        'deskripsi',
        'anggaran',
        'latitude',
        'longitude',
        'foto_sebelum',
        'foto_sesudah',
        'status',
        'catatan_revisi',
    ];

    protected $casts = [
        'anggaran' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function needsRevision(): bool
    {
        return $this->status === 'revision';
    }
}
