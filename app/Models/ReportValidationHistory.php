<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportValidationHistory extends Model
{
    protected $fillable = ['report_submission_id', 'report_version_id', 'validated_by', 'status', 'notes'];

    public function submission()
    {
        return $this->belongsTo(ReportSubmission::class, 'report_submission_id');
    }

    public function version()
    {
        return $this->belongsTo(ReportVersion::class, 'report_version_id');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }
}
