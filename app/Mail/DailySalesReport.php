<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DailySalesReport extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public $orders
    )
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Daily Sales Report',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $totalRevenue = $this->orders->sum('total_amount');
        $totalOrders = $this->orders->count();
        
        $productsSold = $this->orders->flatMap(function ($order) {
            return $order->items;
        })->groupBy('product_id')->map(function ($items) {
            $firstItem = $items->first();
            return [
                'product_name' => $firstItem->product->name,
                'quantity_sold' => $items->sum('quantity'),
                'revenue' => $items->sum(function ($item) {
                    return $item->quantity * $item->price;
                }),
            ];
        })->values();

        return new Content(
            view: 'emails.daily-sales-report',
            with: [
                'totalRevenue' => $totalRevenue,
                'totalOrders' => $totalOrders,
                'productsSold' => $productsSold,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
