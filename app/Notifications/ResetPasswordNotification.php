<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPassword
{
    protected function buildMailMessage($url): MailMessage
    {
        $expire = config('auth.passwords.'.config('auth.defaults.passwords').'.expire');

        return (new MailMessage)
            ->subject('Restablecer contraseña - AppSalon')
            ->greeting('Hola,')
            ->line('Recibiste este correo porque solicitaste restablecer la contraseña de tu cuenta.')
            ->action('Restablecer contraseña', $url)
            ->line("Este enlace expirará en {$expire} minutos.")
            ->line('Si no solicitaste el cambio, no necesitas hacer nada.')
            ->salutation('Saludos, el equipo de AppSalon');
    }
}
