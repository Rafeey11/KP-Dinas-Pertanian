<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSopSubmission extends Model
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

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitter_id');
    }

    public function values()
    {
        return $this->hasMany(BankSopSubmissionValue::class, 'submission_id');
    }
}
