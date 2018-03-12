<?php

namespace App\Mail;

use App\Order;
use App\Email;
use App\EmailSent;
use App\Rate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderProcess extends Mailable
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
        if($this->order->status == Order::ORDER_UNPAID) {
            return true;
        }

        $email = new Email();
        $code_view = $email->get_codeView('order', $this->order->status);
        $code = $code_view['code'];
        $view = $code_view['view'];

        if($code == '' && $view == '') {
            return true;
        }
        $email = Email::where('code',$code)->first();

        return $this->subject($email->subject)
            ->markdown($view, [
                'email' => $email
            ]);
    }
}
