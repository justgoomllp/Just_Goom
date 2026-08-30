<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class FrontVerifyEmail extends VerifyEmail
{
    public function toMail($notifiable): MailMessage
    {
        $expireMinutes = Config::get('auth.verification.expire', 60);
        $name = trim((string) ($notifiable->fname ?? ''));

        return (new MailMessage)
            ->subject('Verify Your Email — '.config('app.name'))
            ->greeting($name !== '' ? 'Hello '.$name.'!' : 'Hello!')
            ->line('Thank you for registering on '.config('app.name').'.')
            ->line('Please click the button below to verify your email address and activate your account.')
            ->action('Verify Email Address', $this->verificationUrl($notifiable))
            ->line('This verification link will expire in '.$expireMinutes.' minutes.')
            ->line('If you did not create an account, no further action is required.');
    }

    protected function verificationUrl($notifiable): string
    {
        return URL::temporarySignedRoute(
            'front.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
