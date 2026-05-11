<?php

namespace App\Repositories\Contracts;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function create(array $data): Product;
    public function getAll(array $filters = []);
    public function getById(int $id): ?Product;
    public function getBySlug(string $id): ?Product;
    public function getByVendor(int $vendorId);
    public function update(int $id, array $data): Product;
    public function delete(int $id): bool;
}