<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BankSopSubmissionValue extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function submission()
    {
        return $this->belongsTo(BankSopSubmission::class, 'submission_id');
    }

    public function field()
    {
        return $this->belongsTo(BankSopField::class, 'field_id');
    }
}
