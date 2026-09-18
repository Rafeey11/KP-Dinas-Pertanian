<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Request;

class AuditLogService
{
    /**
     * Log an activity.
     *
     * @param string $action
     * @param string $module
     * @param mixed $subject
     * @param string|null $description
     * @param array|null $oldValues
     * @param array|null $newValues
     * @return \App\Models\ActivityLog|null
     */
    public static function log(string $action, string $module, $subject = null, string $description = null, array $oldValues = null, array $newValues = null)
    {
        try {
            $log = new ActivityLog();
            $log->user_id = auth()->id();
            $log->action = $action;
            $log->module = $module;
            
            if ($subject) {
                $log->subject_type = get_class($subject);
                $log->subject_id = $subject->id;
            }
            
            $log->description = $description;
            $log->old_values = $oldValues;
            $log->new_values = $newValues;
            $log->ip_address = Request::ip();
            $log->user_agent = Request::userAgent();
            $log->save();
            
            return $log;
        } catch (\Exception $e) {
            // Silently fail to avoid breaking the main application flow
            \Illuminate\Support\Facades\Log::error('Failed to create Audit Log: ' . $e->getMessage());
            return null;
        }
    }
}
