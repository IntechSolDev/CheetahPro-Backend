<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Member extends Notification implements ShouldQueue
{
    use Queueable;
    protected $info;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($info)
    {
        $this->info = $info;
 
     
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
        return (new MailMessage)
                    ->line('Congrats! now you are our new member.')
                    ->line("You Refferal by ".$this->info['referral_by'])
                    ->line("You Name is ".$this->info['name'])
                    ->line("Your Email is ".$this->info['email'])
                    ->line("Your Password is ".$this->info['password'])
                    ->line("Your Refferal Code ".$this->info['code'])
                    ->line('Thank you for sending referral code now you are part of our family.!');
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
