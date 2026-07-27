<?php

namespace App\Notifications\Auth;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends BaseVerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $url = $this->verificationUrl($notifiable);

        $greeting = filled($notifiable->name ?? null)
            ? __('auth.mail_greeting', ['name' => $notifiable->name])
            : __('auth.mail_greeting_generic');

        return (new MailMessage)
            ->subject(__('Verify Email Address'))
            ->view('mail.auth.verify-email', [
                'url' => $url,
                'greeting' => $greeting,
            ]);
    }
}
