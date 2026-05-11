<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\PlaceOrderRequest;
use App\Services\OrderService;
use App\Repositories\Eloquent\OrderRepository;
use App\Http\Responses\ApiResponse;
// use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct(private OrderService $service) {}

    public function checkout()
    {
        $order = $this->service->checkout(auth()->user()->id);

        return  ApiResponse::success($order, 'User address', 201);
    }

    public function placeOrder(placeOrderRequest $request)
    {
        try {
            // \Log::info('Checkout Request:', $request->all());
            $order = $this->service->placeOrder(auth()->user(), $request);

            return  ApiResponse::success($order, 'Order placed successfully.', 201);

        } catch (\Exception $e) {
            \Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            return ApiResponse::error(
                config('app.debug') ? $e->getMessage() : 'Something went wrong',
                500
            );
        }
    }

    public function orderDetail(int $id)
    {
        return  ApiResponse::success(
            $this->service->getOrder($id)
        );
    }

    public function history()
    {
        return  ApiResponse::success(
            $this->service->getUserOrders(auth()->id())
        );
    }
}