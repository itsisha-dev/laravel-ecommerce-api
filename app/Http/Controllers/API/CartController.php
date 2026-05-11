<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\UpdateCartRequest;
use App\Services\CartService;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;

class CartController extends Controller
{
    public function __construct(protected CartService $cartService) {}

    public function add(AddToCartRequest $request)
    {
        try {
            $data = $this->cartService->addToCart($request);
            $cartToken = $data['cart_token'] ?? null;

            $response = ApiResponse::success($data, 'Item added to cart');

            // If the request has no X-Cart-Token, set it in response header
            if (!$request->header('X-Cart-Token') && !is_null($cartToken)) {
                ApiResponse::success($data, 'Item added to cart')->header('X-Cart-Token', $cartToken);
            }

            return $response;
        } catch (\Exception $e) {

            \Log::error($e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
        }
    }

    public function update(UpdateCartRequest $request)
    {
        $data = $this->cartService->updateCart($request);

        return ApiResponse::success($data, 'Cart updated');
    }

    public function remove($id)
    {
        $data = $this->cartService->removeItem($id);

        return ApiResponse::success($data, 'Item removed');
    }

    public function getCart(Request $request)
    {
        $user = $this->user()?->id;
        $sessionId = $request->header('X-Cart-Token') ?? null;
        $data = $this->cartService->getCartItems($user, $sessionId);

        return ApiResponse::success($data);
    }

    public function assignUser(Request $request)
    {
        $user = Auth()->user()->id;
        $cart_token = $request->cart_token;

        if (!$cart_token) {
            return ApiResponse::error('Cart token missing', 400);
        }

        $data = $this->cartService->assignUser($user, $cart_token);

        if ($data == false) {
            return ApiResponse::error('No items found in cart', 404);
        }

        return ApiResponse::success("", 'Cart assigned to user');
    }

    private function user()
    {
        return auth('sanctum')->user();
    }
}