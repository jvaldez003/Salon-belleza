<?php

namespace App\Notifications;

use App\Models\Cita;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CitaConfirmadaNotification extends Notification
{
    use Queueable;

    public function __construct(public Cita $cita)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $servicios = $this->cita->servicios->pluck('nombre')->implode(', ');

        return (new MailMessage)
            ->subject('Confirmación de cita - AppSalon')
            ->greeting('¡Hola '.$notifiable->name.'!')
            ->line('Tu cita ha sido registrada correctamente.')
            ->line('Fecha: '.$this->cita->fecha->format('d/m/Y'))
            ->line('Hora: '.substr((string) $this->cita->hora, 0, 5))
            ->line('Servicios: '.$servicios)
            ->line('Total: $'.number_format($this->cita->total, 0, ',', '.'))
            ->line('Estado: '.ucfirst($this->cita->estado))
            ->action('Ver mis citas', url('/citas'))
            ->line('Gracias por confiar en nosotros.');
    }
}
