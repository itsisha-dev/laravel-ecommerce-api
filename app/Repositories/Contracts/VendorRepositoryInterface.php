<?php

namespace App\Repositories\Contracts;

use App\Models\Vendor;

interface VendorRepositoryInterface
{
    public function create(array $data): Vendor;
    public function getAll(array $filters = []);
    public function getById(int $id): ?Vendor;
    public function updateProfile(Vendor $vendor, array $data): Vendor;
}