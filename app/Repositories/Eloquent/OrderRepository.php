<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\UserAddress;
use App\Models\Order;
use App\Repositories\Contracts\OrderRepositoryInterface;

class OrderRepository implements OrderRepositoryInterface
{
    public function checkout($userId) {
        return UserAddress::where('user_id', $userId)
                    ->where('is_default', true)
                    ->first();
    }

    public function createOrder($userId, $request)
    {
        $total = 0;
        $cart = Cart::with('items')->where('user_id', $userId)->first();

        if (!$cart || $cart->items->isEmpty()) {
            throw new \Exception('Your cart is empty.');
        }

        // update shipping address
        $address = UserAddress::updateOrCreate(
            ['user_id' => $userId, 'label' => $request->label],
            [
                'fullname' => $request->fullname,
                'phone' => $request->phone,
                'label' => $request->label,
                'address' => $request->address,
                'city' => $request->city,
                'state' => $request->state,
                'country' => $request->country ?? "India",
                'postal_code' => $request->postal_code,
                'is_default' => true,
            ]
        );
        UserAddress::where('user_id', $userId)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);

        foreach ($cart->items as $item) {
            $total += $item->price * $item->quantity;
        }

        $order = Order::create([
            'user_id' => $userId,
            'total' => $total,
            'shipping_address' => collect($address->toArray())
                                    ->except(['id','user_id','updated_at','created_at','is_default'])
                                    ->toArray(),
        ]);

        foreach ($cart->items as $item) {
            $order->items()->create([
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price
            ]);
        }
        return $order;
    }

    public function clearCart($userId)
    {
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            $cart->items()->delete();
        }
    }

    public function getOrder($id)
    {
        return Order::with(['items.product'])->where('id', $id)->first();
    }

    public function getUserOrders($userId)
    {
        return Order::with('items.product')->where('user_id', $userId)->latest()->get();
    }
}