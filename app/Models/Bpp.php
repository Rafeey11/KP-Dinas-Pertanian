<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bpp extends Model
{
    use HasFactory;

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
}
