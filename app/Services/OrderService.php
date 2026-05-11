<?php

namespace App\Services;

use App\Repositories\Contracts\OrderRepositoryInterface;
use App\Events\OrderPlaced;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function __construct(private OrderRepositoryInterface $orderRepo) {}

    public function checkout($userId)
    {
        return $this->orderRepo->checkout($userId);
    }

    public function placeOrder($user, $request)
    {
        DB::beginTransaction();

        try {

            $order = $this->orderRepo->createOrder($user->id, $request);

            DB::commit();

            event(new OrderPlaced($order));

            $this->orderRepo->clearCart($user->id);

            return $this->orderRepo->getOrder($order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function getOrder(int $id)
    {
        return $this->orderRepo->getOrder($id);
    }

    public function getUserOrders(int $userId)
    {
        return $this->orderRepo->getUserOrders($userId);
    }
}