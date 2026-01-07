<?php

namespace App\Jobs;

use App\Mail\DailySalesReport;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendDailySalesReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $orders = Order::with('items.product')
            ->whereDate('created_at', today())
            ->get();

        if ($orders->isEmpty()) {
            return;
        }

        $adminEmail = config('mail.admin_email', 'admin@example.com');
        
        Mail::to($adminEmail)->send(new DailySalesReport($orders));
    }
}