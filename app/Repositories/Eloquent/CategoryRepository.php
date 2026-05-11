<?php

namespace App\Repositories\Eloquent;

use App\Models\Category;
use App\Repositories\Contracts\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function all()
    {
        return Category::with('children')->get();
    }

    public function find(int $id): ?Category
    {
        return Category::find($id);
    }

    public function create(array $data): Category
    {
        return Category::create($data);
    }

    public function update(int $id, array $data): Category
    {
        $category = $this->findOrFail($id);
        $category->update($data);

        return $category;
    }

    public function delete(int $id): bool
    {
        $category = $this->findOrFail($id);
        return $category->delete();
    }

    private function findOrFail(int $id): Category
    {
        return Category::findOrFail($id);
    }
}