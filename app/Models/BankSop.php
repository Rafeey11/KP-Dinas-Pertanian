<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankSop extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'deadline' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function targets()
    {
        return $this->hasMany(BankSopTarget::class);
    }

    public function fields()
    {
        return $this->hasMany(BankSopField::class)->orderBy('order');
    }

    public function submissions()
    {
        return $this->hasMany(BankSopSubmission::class);
    }
}
