<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class ReportSubmittedNotification extends Notification
{
    use Queueable;

    protected $reportSubmission;
    protected $bpp;

    /**
     * Create a new notification instance.
     */
    public function __construct($reportSubmission, $bpp)
    {
        $this->reportSubmission = $reportSubmission;
        $this->bpp = $bpp;
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
        return [
            'title' => 'Laporan Baru Menunggu Validasi',
            'message' => "Laporan {$this->reportSubmission->penyuluh->name} dari {$this->bpp->name} telah dikirim dan menunggu validasi.",
            'action_url' => route('admin.report-validation.show', $this->reportSubmission->id),
            'module' => 'Laporan',
            'reference_id' => $this->reportSubmission->id,
        ];
    }
}
