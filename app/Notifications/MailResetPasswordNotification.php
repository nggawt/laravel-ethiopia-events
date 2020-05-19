<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $link = "http://localhost:4200/password/reset/?token=" . $this->token;
        
        // $link = url( "/password/reset/?token=" . $this->token );
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->subject('Reset Password Notification')
                    ->action( 'Reset Password', $link )
                    ->line('Thank you for using our application!');

            // ->subject(Lang::getFromJson('Reset Password Notification'))
            // ->line(Lang::getFromJson('You are receiving this email because we received a password reset request for your account.'))
            // ->action(Lang::getFromJson('Reset Password'), url(config('app.url').route('password.reset', $this->token, false)))
            // ->line(Lang::getFromJson('If you did not request a password reset, no further action is required.'));



           // return ( new MailMessage )
           //    ->view('reset.emailer')
           //    ->from('info@example.com')
           //    ->subject( 'Reset your password' )
           //    ->line( "Hey, We've successfully changed the text " )
           //    ->action( 'Reset Password', $link )
           //    ->attach('reset.attachment')
           //    ->line( 'Thank you!' );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
