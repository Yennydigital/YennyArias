<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Sticker extends Model
{
    protected $fillable = ['sticker_pack_id', 'path', 'sort'];

    public function pack()
    {
        return $this->belongsTo(StickerPack::class, 'sticker_pack_id');
    }

    public function getUrlAttribute(): string
    {
        return Storage::disk('public')->url($this->path);
    }
}
