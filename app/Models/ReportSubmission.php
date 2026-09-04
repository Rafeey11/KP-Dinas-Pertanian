<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSubmission extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'validated_at' => 'datetime',
    ];

    public function reportRequest()
    {
        return $this->belongsTo(ReportRequest::class);
    }

    public function penyuluh()
    {
        return $this->belongsTo(Penyuluh::class);
    }

    public function bpp()
    {
        return $this->belongsTo(Bpp::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function validator()
    {
        return $this->belongsTo(User::class, 'validated_by');
    }

    public function versions()
    {
        return $this->hasMany(ReportVersion::class)->orderBy('version_number', 'desc');
    }

    public function latestVersion()
    {
        return $this->hasOne(ReportVersion::class)->latestOfMany('version_number');
    }
}
