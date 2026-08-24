<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class DoctorStatusNotification extends Notification
{
    use Queueable;

    public function __construct(public string $status) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        if ($this->status === 'active') {
            return (new MailMessage)
                ->subject('LupusCare — Compte validé ✓')
                ->greeting('Bonjour Dr. ' . $notifiable->name . ',')
                ->line('Votre compte médecin a été validé par notre équipe.')
                ->line('Vous pouvez maintenant vous connecter et accéder à votre espace.')
                ->action('Se connecter', url('/login'));
        }

        return (new MailMessage)
            ->subject('LupusCare — Compte refusé')
            ->greeting('Bonjour Dr. ' . $notifiable->name . ',')
            ->line('Votre demande de compte médecin a été refusée.')
            ->line('Pour plus d\'informations, contactez notre équipe.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'message' => $this->status === 'active'
                ? 'Votre compte médecin a été validé.'
                : 'Votre compte médecin a été refusé.',
        ];
    }
}