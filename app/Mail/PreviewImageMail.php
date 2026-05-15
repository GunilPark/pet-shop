<?php

namespace App\Mail;

use App\Models\DogGoodsConsultation;
use App\Models\DogGoodsItem;
use App\Models\DogGoodsOrder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PreviewImageMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public readonly DogGoodsOrder        $order,
        public readonly DogGoodsItem         $item,
        public readonly DogGoodsConsultation $consultation,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "【加工プレビューのご確認】{$this->item->name}（注文番号 #{$this->order->id}）",
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.preview-image');
    }
}
