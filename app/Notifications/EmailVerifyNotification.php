<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Ichtrojan\Otp\Otp;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerifyNotification extends Notification
{
    use Queueable;

    private $otp;
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        $this->otp = new Otp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        //generate otp
        $otp = new Otp;
        $otp = $otp->generate($notifiable->email, 'alpha_numeric', 6, 3);

        return (new MailMessage)
            ->subject('Email Verification')
            ->markdown('mail.register_mail', [
                'otp' => $otp->token,
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}