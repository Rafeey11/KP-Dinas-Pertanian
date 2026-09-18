<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BankSopPublishedNotification extends Notification
{
    use Queueable;

    protected $bankSop;
    protected $type; // 'new_sop', 'new_format', 'new_document'

    /**
     * Create a new notification instance.
     */
    public function __construct($bankSop, $type = 'new_sop')
    {
        $this->bankSop = $bankSop;
        $this->type = $type;
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
        if ($this->type === 'new_format') {
            $title = 'Format Baru Tersedia';
            $message = "Format baru tersedia: {$this->bankSop->title}.";
        } elseif ($this->type === 'new_document') {
            $title = 'Dokumen Baru Tersedia';
            $message = "Dokumen baru tersedia: {$this->bankSop->title}.";
        } else {
            $title = 'Bank SOP Baru Tersedia';
            $message = "Bank SOP baru tersedia: {$this->bankSop->title}.";
        }

        return [
            'title' => $title,
            'message' => $message,
            'action_url' => route('bpp.bank-sops.show', $this->bankSop->id),
            'module' => 'Bank SOP',
            'reference_id' => $this->bankSop->id,
        ];
    }
}
