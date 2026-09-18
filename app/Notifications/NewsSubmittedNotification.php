<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewsSubmittedNotification extends Notification
{
    use Queueable;

    protected $news;
    protected $bpp;

    /**
     * Create a new notification instance.
     */
    public function __construct($news, $bpp)
    {
        $this->news = $news;
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
            'title' => 'Berita Baru Menunggu Review',
            'message' => "{$this->bpp->name} mengirim berita baru dan menunggu review.",
            'action_url' => route('admin.news.show', $this->news->id),
            'module' => 'Berita',
            'reference_id' => $this->news->id,
        ];
    }
}
