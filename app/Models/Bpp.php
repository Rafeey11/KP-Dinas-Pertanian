<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Bpp extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function penyuluhs()
    {
        return $this->hasMany(Penyuluh::class);
    }
    
    public function users()
    {
        return $this->hasMany(User::class);
    }
    
    public function news()
    {
        return $this->hasMany(News::class);
    }
    
    public function videos()
    {
        return $this->hasMany(Video::class);
    }

    public function admin_tim_kerjas()
    {
        return $this->belongsToMany(User::class, 'admin_tim_kerja_bpp', 'bpp_id', 'user_id');
    }
}
