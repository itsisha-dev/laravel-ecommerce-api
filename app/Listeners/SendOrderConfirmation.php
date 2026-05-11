<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmation implements ShouldQueue
{
    public function handle(OrderPlaced $event)
    {
        Log::info('Order placed: '.$event->order->id);
        Redis::publish('order-events', json_encode([
            'event' => 'order_placed',
            'order_id' => $order->id,
            'user_id' => $order->user_id
        ]));
    }
}