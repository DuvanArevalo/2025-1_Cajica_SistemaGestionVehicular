<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Bus\Queueable;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        // Se arma la URL para el restablecimiento de contraseña
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Recuperación de contraseña - EPC')
            ->view('emails.password-reset', [
                'url' => $url,
                // Aquí se llamará al accessor getNameAttribute() de la clase User.
                'nombre' => $notifiable->name,
            ]);
    }
}
