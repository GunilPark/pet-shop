<?php

namespace App\Mail;

use App\Models\DogGoodsItem;
use App\Models\DogGoodsOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly DogGoodsOrder $order,
        public readonly DogGoodsItem  $item,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "【新規注文】#{$this->order->id} {$this->item->name} — {$this->order->user->name}",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.new-order-admin');
    }
}
