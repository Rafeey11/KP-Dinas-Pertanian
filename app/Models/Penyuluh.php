<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penyuluh extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bpp()
    {
        return $this->belongsTo(Bpp::class);
    }

    public function news()
    {
        return $this->hasMany(News::class, 'author_penyuluh_id');
    }
}
