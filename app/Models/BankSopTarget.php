<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSopTarget extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function bankSop()
    {
        return $this->belongsTo(BankSop::class);
    }

    public function bpp()
    {
        return $this->belongsTo(Bpp::class);
    }
}
