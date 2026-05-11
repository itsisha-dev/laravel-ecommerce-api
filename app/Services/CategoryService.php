<?php

namespace App\Services;

use App\Repositories\Contracts\CategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

class CategoryService
{
    public function __construct(protected CategoryRepositoryInterface $categoryRepo) {}

    public function getAll()
    {
        return $this->categoryRepo->all();
    }

    public function create(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->categoryRepo->create($data);
        });
    }

    public function update(int $id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            return $this->categoryRepo->update($id, $data);
        });
    }

    public function delete(int $id)
    {
        return DB::transaction(function () use ($id) {
            return $this->categoryRepo->delete($id);
        });
    }
}