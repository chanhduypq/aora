<?php

namespace App\Mail;

use App\Order;
use App\Email;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return true;
        $email = Email::where('code','order_created')->first();
        return $this->subject($email->subject)
            ->markdown('mail.orders.created', [
                'email' => $email
            ]);
    }
}
