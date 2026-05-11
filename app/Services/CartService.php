<?php

namespace App\Services;

use App\Repositories\Contracts\CartRepositoryInterface;
use App\Events\CartItemAdded;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Str;

class CartService
{
    public function __construct(protected CartRepositoryInterface $cartRepo) {}

    public function getOrCreateCart($request)
    {
        // Check if user is logged in
        $userId = auth('sanctum')->user()?->id; 
        $sessionId = null;

        if (!$userId) {
            // For guest, get session ID from header or generate one
            $token = $request->header('X-Cart-Token') ?? (string) Str::uuid();
            $sessionId = $token;
            // client will need to set sessionid in localstorage - localStorage.setItem('sessionId', data.session_id);
            // and then client will sent it through headers - headers: {'X-Session-Id': sessionId}
        }
        
        // Find or create cart
        $cart = $this->cartRepo->getCart($userId, $sessionId);

        return $cart;
    }

    public function addToCart($request)
    {
        $cart = $this->getOrCreateCart($request);

        $item = $this->cartRepo->addItem(
            $cart,
            $request->product_id,
            $request->quantity
        );

        event(new CartItemAdded($item, $cart->user_id ?? $cart->session_id));
        // broadcast(new CartItemAdded($item, $cart->user_id ?? $cart->session_id))->toOthers();

        if (!is_null($cart->session_id)) {
                return [
                'cart' => $cart->load('items'),
                'cart_token' => $cart->session_id
            ];
        }
        else {
            return [
                'cart' => $cart->load('items')
            ];
        }
    }

    public function updateCart($request)
    {
        return $this->cartRepo->updateItem(
            $request->cart_item_id,
            $request->quantity
        );
    }

    public function removeItem($id)
    {
        $this->cartRepo->removeItem($id);
    }

    public function getCartItems($user, $sessionId)
    {
        return $this->cartRepo->getCartItems($user, $sessionId);
    }

    public function deleteOldGuestCarts()
    {
        return \App\Models\Cart::whereNull('user_id')
            ->where('created_at', '<', now()->subHours(12))     // delete after 12 hours
            // ->where('created_at', '<', now()->subDay())      // delete after a day
            ->delete();
    }

    public function assignUser($userId, $cartToken)
    {
        return $this->cartRepo->assignUser($userId, $cartToken);
    }
}

