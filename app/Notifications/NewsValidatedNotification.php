<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewsValidatedNotification extends Notification
{
    use Queueable;

    protected $news;
    protected $status;

    /**
     * Create a new notification instance.
     */
    public function __construct($news, $status)
    {
        $this->news = $news;
        $this->status = $status;
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
        if ($this->status === 'published') {
            $title = 'Berita Dipublikasikan';
            $message = "Berita '{$this->news->title}' telah dipublikasikan.";
        } elseif ($this->status === 'approved') {
            $title = 'Berita Disetujui';
            $message = "Berita '{$this->news->title}' telah disetujui.";
        } else {
            $title = 'Berita Ditolak';
            $message = "Berita '{$this->news->title}' ditolak. Silakan periksa catatan reviewer.";
        }

        return [
            'title' => $title,
            'message' => $message,
            'action_url' => route('bpp.berita.show', $this->news->id),
            'module' => 'Berita',
            'reference_id' => $this->news->id,
        ];
    }
}
