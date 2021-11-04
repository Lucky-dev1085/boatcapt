<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CreateBid extends Notification
{
    use Queueable;

    public $bid;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bid)
    {
        $this->bid = $bid;
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
        $url = url('/'.$this->bid->id.'/owner-view-bid');
        $total = round(($this->bid->hours * $this->bid->amount), 2);
        
        return (new MailMessage)
                    ->from($this->bid->profile->user->email, 'theCaptLive')
                    ->to($this->bid->trip->profile->user->email)
                    ->cc('contact@thecaptapp.com')
                    ->subject('New Bid Submitted')
                    ->line('Capt. '.$this->bid->profile->firstName.' has submitted a bid for your upcoming trip. Please review the bid and respond')
                    ->action('View Bid', $url)
                    ->line('Trip Start: '.$this->bid->trip->startLocation.' '.date('m/d/Y @ hA', strtotime($this->bid->trip->startTime)))
                    ->line('Trip End: '.$this->bid->trip->endLocation.' '.date('m/d/Y @ hA', strtotime($this->bid->trip->endTime)))
                    ->line('Captain\'s Notes: '.$this->bid->describe)
                    ->line('Total: '.$total);
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
