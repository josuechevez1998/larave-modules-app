<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public function __construct(public string $token) {}

    /**
     * @return list<string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $expire = (int) config('auth.passwords.'.config('auth.defaults.passwords').'.expire', 60);

        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        $greeting = filled($notifiable->name ?? null)
            ? __('auth.mail_greeting', ['name' => $notifiable->name])
            : __('auth.mail_greeting_generic');

        return (new MailMessage)
            ->subject(__('Reset Password Notification'))
            ->view('mail.auth.reset-password', [
                'url' => $url,
                'expire' => $expire,
                'greeting' => $greeting,
            ]);
    }
}
