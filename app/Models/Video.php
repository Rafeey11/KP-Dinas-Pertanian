<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'activity_date' => 'date',
    ];

    public function bpp()
    {
        return $this->belongsTo(Bpp::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
