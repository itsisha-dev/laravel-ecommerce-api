<?php

namespace App\Repositories\Eloquent;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Repositories\Contracts\CartRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CartRepository implements CartRepositoryInterface
{
    public function getCart($userId = null, $sessionId = null)
    {
        return Cart::firstOrCreate([
            'user_id' => $userId,
            'session_id' => $sessionId
        ]);
    }

    public function addItem($cart, $productId, $quantity)
    {
        $item = $cart->items()->where('product_id', $productId)->first();
        $product = Product::findOrFail($productId);

        if ($item) {
            // Update
            $item->quantity += $quantity;
            $item->price = $product->price;
            $item->save();
        } else {
            // Insert new item
            $item = $cart->items()->create([
                'product_id' => $productId,
                'quantity'   => $quantity,
                'price'      => $product->price
            ]);
        }

        return $item;
    }

    public function updateItem($cartItemId, $quantity)
    {
        $cartItem = CartItem::findOrFail($cartItemId);
        $product = Product::findOrFail($cartItem->product_id);

        // Update price
        $cartItem->price = $product->price;

        // Update quantity
        $cartItem->quantity = $quantity;

        $cartItem->save();

        return $cartItem;
    }

    public function removeItem($cartItemId)
    {
        return CartItem::where('id', $cartItemId)->delete();
    }

    public function getCartItems($user= null, $sessionId = null)
    {
        $query = Cart::with('items.product');

        if ($user) {
            $query->where('user_id', $user);
        } elseif (!empty($sessionId)) {
            $query->where('session_id', $sessionId);
        } else {
            return null; 
        }

        $cart = $query->first();

        if (!$cart) {
            return [
                'cart' => null,
                'total' => 0
            ];
        }

        // Sync price in DB (important)
        foreach ($cart->items as $item) {
            $latestPrice = $item->product->price;

            if ($item->price != $latestPrice) {
                $item->update([
                    'price' => $latestPrice
                ]);
            }
        }

        // Refresh relation after update
        $cart->load('items.product');

        return [
            'cart' => $cart,
            'total' => $cart->getTotal()
        ];
    }

    public function assignUser($userId, $cartToken)
    {
        $userCart = Cart::where('user_id', $userId)->first();

        $guestCart = Cart::where('session_id', $cartToken)->first();

        if (!$guestCart) return false;

        if ($userCart) {
            foreach ($guestCart->items as $item) {
                $product = Product::findOrFail($item->product_id);

                $userCart->items()->updateOrCreate(
                    ['product_id' => $item->product_id],
                    ['quantity' => $item->quantity, 'price' => $product->price],
                );
            }
            $guestCart->delete();
        }
        else {
            // Assign user
            $guestCart->user_id = $userId;
            $guestCart->session_id = null;
            $guestCart->save();
        }

        return true;
    }
}