<?php

namespace App\Repositories\Contracts;

interface OrderRepositoryInterface
{
    public function checkout($userId);
    public function createOrder($userId, $request);
    public function clearCart($userId);
    public function getOrder($id);
    public function getUserOrders($userId);
}