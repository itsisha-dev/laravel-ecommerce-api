<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateVendorRequest;
use App\Http\Requests\UpdateVendorRequest;
use App\Http\Requests\SearchVendorRequest;
use App\Services\VendorService;
use App\Http\Responses\ApiResponse;
use App\Models\Vendor;

class VendorController extends Controller
{
    public function __construct(protected VendorService $vendorService) {}

    public function create(CreateVendorRequest $request)
    {
        $vendor = $this->vendorService->createVendor($request->validated());
        return ApiResponse::success($vendor, 201);
    }

    public function list(SearchVendorRequest $request)
    {
        $vendors = $this->vendorService->listVendors($request->only(['search', 'user_id']));
        return ApiResponse::success($vendors);
    }

    public function details(int $id)
    {
        $vendor = $this->vendorService->getVendorDetails($id);
        if (!$vendor) {
            return ApiResponse::success(['message' => 'Vendor not found'], 404);
        }
        return ApiResponse::success($vendor);
    }

    public function updateProfile(UpdateVendorRequest $request)
    {
        $vendor = $this->vendorService->updateProfile($request->validated());
        return ApiResponse::success($vendor, 201);
    }
}