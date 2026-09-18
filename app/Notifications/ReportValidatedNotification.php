<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportValidatedNotification extends Notification
{
    use Queueable;

    protected $reportSubmission;
    protected $status;
    protected $adminName;

    /**
     * Create a new notification instance.
     */
    public function __construct($reportSubmission, $status, $adminName)
    {
        $this->reportSubmission = $reportSubmission;
        $this->status = $status;
        $this->adminName = $adminName;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->status === 'approved') {
            $title = 'Laporan Disetujui';
            $message = "Laporan {$this->reportSubmission->penyuluh->name} telah disetujui oleh {$this->adminName}.";
        } elseif ($this->status === 'revision') {
            $title = 'Laporan Perlu Revisi';
            $message = "Laporan {$this->reportSubmission->penyuluh->name} memerlukan revisi. Silakan periksa catatan validator.";
        } else {
            $title = 'Laporan Ditolak';
            $message = "Laporan {$this->reportSubmission->penyuluh->name} ditolak. Silakan periksa alasan penolakan.";
        }

        return [
            'title' => $title,
            'message' => $message,
            'action_url' => route('bpp.laporan.show', $this->reportSubmission->report_request_id),
            'module' => 'Laporan',
            'reference_id' => $this->reportSubmission->id,
        ];
    }
}
