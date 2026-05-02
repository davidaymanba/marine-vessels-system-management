<?php

namespace App\Notifications;

use App\Models\Movement;
use App\Notifications\Channels\WhatsAppChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MovementStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Movement $movement,
        public string $label,
    ) {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', WhatsAppChannel::class];
    }

    public function toWhatsapp(object $notifiable): string
    {
        $movementType = $this->movement->type === 'exit' ? 'خروج' : 'دخول';

        return implode("\n", [
            'Marine Vessels Management System',
            $this->label,
            'مرحباً ' . ($notifiable->name ?? ''),
            'الوسيلة: ' . ($this->movement->vessel?->name ?? '-'),
            'نوع الحركة: ' . $movementType,
            'المخرج: ' . ($this->movement->exit?->name ?? '-'),
            'المنفذ بواسطة: ' . ($this->movement->user?->name ?? '-'),
            'وقت الحركة: ' . optional($this->movement->moved_at)->format('Y-m-d H:i:s'),
            'عرض سجل الحركات: ' . route('movements.index'),
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return [
            'label' => $this->label,
            'movement_id' => $this->movement->id,
            'type' => $this->movement->type,
            'vessel_name' => $this->movement->vessel?->name,
            'exit_name' => $this->movement->exit?->name,
            'moved_at' => optional($this->movement->moved_at)->toDateTimeString(),
            'actor_name' => $this->movement->user?->name,
            'url' => route('movements.index'),
        ];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return $this->toDatabase($notifiable);
    }
}
