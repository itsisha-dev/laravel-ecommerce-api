<?php

namespace App\Services;

use App\Repositories\Contracts\VendorRepositoryInterface;
// use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;

class VendorService
{
    public function __construct(protected VendorRepositoryInterface $vendorRepo) {}

    public function createVendor(array $data): Vendor
    {
        $data['user_id'] = Auth()->user()->id;
        $data['status'] = $data['status'] ?? 'pending';
        return $this->vendorRepo->create($data);
    }

    public function listVendors(array $filters = [])
    {
        // if (!Gate::allows('view-vendors')) {
        //     throw new AuthorizationException('Only admin can view vendors list.');
        // }
        Gate::authorize('view-vendors');
        return $this->vendorRepo->getAll($filters);
    }

    public function getVendorDetails(int $id)
    {
        $vendor = $this->vendorRepo->getById($id);
        if (!$vendor) {
            throw new \Exception('Vendor not found'); 
        }

        // if (Gate::denies('manage-vendor', $vendor)) {
        //     throw new AuthorizationException();
        // }
        Gate::authorize('manage-vendor', $vendor);

        return $vendor;
    }

    public function updateProfile(array $data): Vendor
    {
        $vendor = Auth()->user()->vendorProfile; 
        return $this->vendorRepo->updateProfile($vendor, $data);
    }
}