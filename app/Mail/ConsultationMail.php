<?php

namespace App\Mail;

use App\Models\DogGoodsItem;
use App\Models\DogGoodsOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ConsultationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly DogGoodsOrder $order,
        public readonly DogGoodsItem  $item,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "【{$this->item->name}相談申請】ご注文受付のお知らせ（注文番号 #{$this->order->id}）",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.consultation',
        );
    }
}
