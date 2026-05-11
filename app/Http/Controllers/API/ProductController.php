<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Services\ProductService;
use Illuminate\Http\Request;
use App\Http\Responses\ApiResponse;

class ProductController extends Controller
{
    public function __construct(protected ProductService $productService) {}

    public function create(ProductRequest $request)
    {
        $product = $this->productService->createProduct($request->validated());
        return ApiResponse::success($product, 201);
    }

    public function list(Request $request)
    {
        $products = $this->productService->listProducts($request->only(['search', 'vendor_id']));
        $responseData = [
            // 'items' => $products->items(), // actual product array
            // 'next_cursor' => $products->nextCursor()?->encode(),
            // 'prev_cursor' => $products->previousCursor()?->encode(),
            // 'per_page' => $products->perPage(),
            // 'message' => count($products) ? '' : 'No products found',
            'items' => $products->items(), // actual product array
            'currentPage' => $products->currentPage(),
            'lastPage' => $products->lastPage(),
            'total' => $products->total(),
            'message' => count($products) ? '' : 'No products found',
        ];

        return ApiResponse::success($responseData); 
    }

    public function details(string $slug)
    {
        $product = $this->productService->getProductDetails($slug);
        if (!$product) {
            return ApiResponse::error('Product not found', 404, '');
        }
        return ApiResponse::success($product);
    }

    public function vendorProducts(int $vendorId)
    {
        $products = $this->productService->getVendorProducts($vendorId);
        $responseData = [
            'items' => $products->items(), // actual product array
            'next_cursor' => $products->nextCursor()?->encode(),
            'prev_cursor' => $products->previousCursor()?->encode(),
            'per_page' => $products->perPage(),
            'message' => count($products) ? '' : 'No products found',
        ];

        return ApiResponse::success($responseData); 
    }
}