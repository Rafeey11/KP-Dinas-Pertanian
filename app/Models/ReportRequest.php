<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportRequest extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'deadline' => 'date',
        'start_date' => 'date',
        'allowed_formats' => 'array',
    ];

    public function reportType()
    {
        return $this->belongsTo(ReportType::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function submissions()
    {
        return $this->hasMany(ReportSubmission::class);
    }

    public function isOverdue()
    {
        return $this->deadline->isPast() && $this->status === 'active';
    }
}
