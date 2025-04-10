<?php

namespace App\Mail;

use App\Models\Orders;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatus extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    public function __construct(Orders $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this->subject('Cập nhật trạng thái đơn hàng #' . $this->order->id)
                    ->view('emails.orders_status')
                    ->with([
                        'order' => $this->order,
                    ]);
    }
}