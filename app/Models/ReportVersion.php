<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportVersion extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($version) {
            if ($version->file_path && \Illuminate\Support\Facades\Storage::disk('local')->exists($version->file_path)) {
                \Illuminate\Support\Facades\Storage::disk('local')->delete($version->file_path);
            }
        });
    }

    public function submission()
    {
        return $this->belongsTo(ReportSubmission::class, 'report_submission_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
