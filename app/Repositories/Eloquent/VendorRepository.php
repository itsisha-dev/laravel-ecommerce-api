<?php

namespace App\Repositories\Eloquent;

use App\Models\Vendor;
use App\Repositories\Contracts\VendorRepositoryInterface;

class VendorRepository implements VendorRepositoryInterface
{
    public function create(array $data): Vendor
    {
        return Vendor::create($data);
    }

    public function getAll(array $filters = [])
    {
        $query = Vendor::query();

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['search'])) {
            $query->where('name', 'ilike', "%{$filters['search']}%");
        }

        return $query->cursorPaginate(10); // pagination
    }

    public function getById(int $id): ?Vendor
    {
        return Vendor::find($id);
    }

    public function updateProfile(Vendor $vendor, array $data): Vendor
    {
        $vendor->update($data);
        return $vendor;
    }
}