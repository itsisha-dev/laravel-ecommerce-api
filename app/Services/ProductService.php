<?php

namespace App\Services;

use App\Repositories\Contracts\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductService
{
    public function __construct(protected ProductRepositoryInterface $productRepo) {}

    public function createProduct(array $data)
    {
        // Any business logic, e.g., default stock, validation beyond request
        $data['stock'] = $data['stock'] ?? 0;
        return DB::transaction(function () use ($data) {
            return $this->productRepo->create($data);
        });
    }

    public function listProducts(array $filters = [])
    {
        return $this->productRepo->getAll($filters);
    }

    public function getProductDetails(string $slug)
    {
        return $this->productRepo->getBySlug($slug);
    }

    public function getVendorProducts(int $vendorId)
    {
        return $this->productRepo->getByVendor($vendorId);
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(fn() => $this->productRepo->update($id, $data));
    }

    public function delete(int $id)
    {
        return DB::transaction(fn() => $this->productRepo->delete($id));
    }
}