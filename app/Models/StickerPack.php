<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StickerPack extends Model
{
    protected $fillable = ['name', 'cover_path', 'min_plan', 'sort', 'is_active'];

    protected $casts = ['is_active' => 'boolean'];

    public function stickers()
    {
        return $this->hasMany(Sticker::class)->orderBy('sort')->orderBy('id');
    }
}
