<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    protected function buildMailMessage($url): MailMessage
    {
        return (new MailMessage)
            ->subject('Confirma tu correo - AppSalon')
            ->greeting('¡Bienvenido a AppSalon!')
            ->line('Gracias por registrarte. Para activar tu cuenta, confirma tu dirección de correo electrónico.')
            ->action('Verificar mi correo', $url)
            ->line('Si no creaste esta cuenta, puedes ignorar este mensaje.')
            ->salutation('Saludos, el equipo de AppSalon');
    }
}
