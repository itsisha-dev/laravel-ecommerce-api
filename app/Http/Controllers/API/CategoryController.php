<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Responses\ApiResponse;

class CategoryController extends Controller
{
    public function __construct(protected CategoryService $categoryService) {}

    public function index()
    {
        return ApiResponse::success(
            $this->categoryService->getAll()
        );
    }

    public function create(CreateCategoryRequest $request)
    {
        $category = $this->categoryService->create($request->validated());

        return ApiResponse::success($category, 201);
    }

    public function show(int $id)
    {
        return ApiResponse::success(
            $this->categoryService->getAll()->find($id)
        );
    }

    public function update(UpdateCategoryRequest $request, int $id)
    {
        $category = $this->categoryService->update($id, $request->validated());

        return ApiResponse::success($category);
    }

    public function destroy(int $id)
    {
        $data = $this->categoryService->getAll()->find($id);
        $this->categoryService->delete($id);

        return ApiResponse::success($data, 'Deleted');
    }
}