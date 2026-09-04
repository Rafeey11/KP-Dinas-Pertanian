<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportVersion extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function submission()
    {
        return $this->belongsTo(ReportSubmission::class, 'report_submission_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
