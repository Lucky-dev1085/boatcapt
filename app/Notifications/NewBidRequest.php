<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewBidRequest extends Notification
{
    use Queueable;

    public $bidRequest;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($bidRequest)
    {
        $this->bidRequest = $bidRequest;
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
        $url = url('/'.$this->bidRequest->trip->tripId.'/bid-request-detail');

        return (new MailMessage)
                    ->from($this->bidRequest->trip->profile->user->email, 'theCaptLive')
                    ->to($this->bidRequest->profile->user->email)
                    ->cc('contact@thecaptapp.com')
                    ->subject('New Bid Request')
                    ->line('A boat owner has an upcoming trip and would like you to provide a bid. If interested please review the bid')
                    ->action('View Bid Request', $url)
                    ->line('Trip Start: '.$this->bidRequest->trip->startLocation.' '.date('m/d/Y @ hA', strtotime($this->bidRequest->trip->startTime)))
                    ->line('Trip End: '.$this->bidRequest->trip->endLocation.' '.date('m/d/Y @ hA', strtotime($this->bidRequest->trip->endTime)))
                    ->line('Notes: '.$this->bidRequest->trip->describe);
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
