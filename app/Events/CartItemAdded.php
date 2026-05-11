<?php

namespace App\Events;

// use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CartItemAdded implements ShouldBroadcast
{
    use Dispatchable, SerializesModels;

    public function __construct(public $item, public $userId) {}

    public function broadcastOn()
    {
        return new PrivateChannel('cart.' . $this->userId);
    }

    public function broadcastAs()
    {
        return 'cart.updated';
    }
}
// namespace App\Events;

// use Illuminate\Queue\SerializesModels;
// use App\Models\CartItem;

// class CartItemAdded
// {
//     use SerializesModels;

//     public $item;

//     public function __construct(CartItem $item)
//     {
//         $this->item = $item;
//     }
// }