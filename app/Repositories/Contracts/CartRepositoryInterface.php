<?php

namespace App\Repositories\Contracts;

interface CartRepositoryInterface
{
    public function getCart($userId = null, $sessionId = null);
    public function addItem($cart, $productId, $quantity);
    public function updateItem($cartItemId, $quantity);
    public function removeItem($cartItemId);
    public function getCartItems($sessionId = null);
}