<?php

namespace App\Notifications;

use App\Models\Rendezvous;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class RendezvousNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Rendezvous $rdv,
        public string $event
    ) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->subject())
            ->line($this->message())
            ->line('Date : ' . $this->rdv->scheduled_at->format('d/m/Y H:i'))
            ->action('Voir sur LupusCare', url('/rendezvous'));
    }

    public function toArray(object $notifiable): array
    {
        return [
            'rendezvous_id' => $this->rdv->id,
            'event'         => $this->event,
            'scheduled_at'  => $this->rdv->scheduled_at->toDateTimeString(),
            'message'       => $this->message(),
        ];
    }

    protected function subject(): string
    {
        return match($this->event) {
            'created'   => 'LupusCare — Nouvelle demande de rendez-vous',
            'confirmed' => 'LupusCare — Rendez-vous confirmé',
            'refused'   => 'LupusCare — Rendez-vous refusé',
            'cancelled' => 'LupusCare — Rendez-vous annulé',
            'completed' => 'LupusCare — Consultation terminée',
            default     => 'LupusCare — Mise à jour de rendez-vous',
        };
    }

    protected function message(): string
    {
        return match($this->event) {
            'created'   => 'Une nouvelle demande de rendez-vous a été soumise.',
            'confirmed' => 'Votre rendez-vous a été confirmé.',
            'refused'   => 'Votre demande de rendez-vous a été refusée.',
            'cancelled' => 'Un rendez-vous a été annulé.',
            'completed' => 'Votre consultation est marquée comme terminée.',
            default     => 'Votre rendez-vous a été mis à jour.',
        };
    }
}