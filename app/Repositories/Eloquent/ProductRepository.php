<?php

namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Repositories\Contracts\ProductRepositoryInterface;

class ProductRepository implements ProductRepositoryInterface
{
    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function getAll(array $filters = [])
    {
        $query = Product::query();

        // Only active products
        $query->where('is_active', true);

        if (!empty($filters['search'])) {
            $search = $filters['search'];

            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%") // product name
                ->orWhereHas('category', function ($q2) use ($search) {
                    $q2->where('name', 'ilike', "%{$search}%"); // category name
                })
                ->orWhereHas('vendor', function ($q3) use ($search) {
                    $q3->where('store_name', 'ilike', "%{$search}%"); // vendor name
                });
            });
        }

        // if (!empty($filters['category'])) {
        //     $query->whereHas('category', function ($q) use ($filters) {
        //         $q->where('name', 'ilike', '%' . $filters['search'] . '%');
        //     });
        // }

        // return $query->cursorPaginate(10); // pagination
        return $query->paginate(10); // pagination
    }

    public function getById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function getBySlug(string $slug): ?Product
    {
        return Product::with('vendor:id,store_name')
            ->where('slug', $slug)
            ->first();
    }

    public function getByVendor(int $vendorId)
    {
        return Product::where('vendor_id', $vendorId)->cursorPaginate(10);
    }

    public function update(int $id, array $data): Product
    {
        $product = $this->findOrFail($id);
        $product->update($data);

        return $product;
    }

    public function delete(int $id): bool
    {
        return $this->findOrFail($id)->delete();
    }
}