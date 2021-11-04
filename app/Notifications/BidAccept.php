<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class BidAccept extends Notification
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
        $url = url('/'.$this->bid->trip->tripId.'/captain-trip-detail');

        return (new MailMessage)
                    ->from($this->bid->trip->profile->user->email, 'theCaptLive')
                    ->to($this->bid->profile->user->email)
                    ->cc('contact@thecaptapp.com')
                    ->subject('Bid Accepted! You Have A Trip Booked ID #'.$this->bid->trip->tripId)
                    ->line($this->bid->trip->profile->user->name.' has accepted your bid and the trip has been booked! You can review the full trip details')
                    ->action('View Trip', $url)
                    ->line('Trip Start: '.$this->bid->trip->startLocation.' '.date('m/d/Y @ hA', strtotime($this->bid->trip->startTime)))
                    ->line('Trip End: '.$this->bid->trip->endLocation.' '.date('m/d/Y @ hA', strtotime($this->bid->trip->endTime)))
                    ->line('Captain\'s Notes: '.$this->bid->describe)
                    ->line('You can also reach out to the owner directly:')
                    ->line($this->bid->trip->profile->user->name)
                    ->line($this->bid->trip->profile->phone)
                    ->line($this->bid->trip->profile->user->email);
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
