<?php

namespace App\Listeners;

use App\Events\CartItemAdded;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class SendCartAddedNotification implements ShouldQueue
{
    public function handle(CartItemAdded $event)
    {
        //$cartItem = $event->item;
        // send email, push notification, etc.

        Log::info('CartItemAdded fired', [
            'cart_item_id' => $event->item->id,
            'product_id' => $event->item->product_id,
            'quantity' => $event->item->quantity,
            'price' => $event->item->price,
        ]);
    }
}