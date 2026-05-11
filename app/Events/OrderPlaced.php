<?php

namespace App\Events;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OrderPlaced implements ShouldQueue, ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(public $order) {}

    public function broadcastOn()
    {
        return new PrivateChannel('orders.' . $this->order->user_id);
    }

    public function broadcastAs()
    {
        return 'order.placed';
    }
}


// use App\Models\Order;
// use Illuminate\Broadcasting\PrivateChannel;
// use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
// use Illuminate\Foundation\Events\Dispatchable;
// use Illuminate\Queue\SerializesModels;

// class OrderPlaced implements ShouldBroadcast
// {
//     use Dispatchable, SerializesModels;

//     public function __construct(public Order $order) {}

//     public function broadcastOn(): PrivateChannel
//     {
//         return new PrivateChannel('orders.' . $this->order->user_id);
//     }

//     public function broadcastAs(): string
//     {
//         return 'order.placed';
//     }

//     public function broadcastWith(): array
//     {
//         return [
//             'order_id' => $this->order->id,
//             'status' => $this->order->status,
//         ];
//     }
// }