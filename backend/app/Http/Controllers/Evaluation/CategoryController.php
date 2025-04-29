<?php

namespace App\Http\Controllers\Evaluation;

use App\Http\Controllers\Controller;
use App\Http\Requests\Evaluation\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class CategoryController extends Controller
{
    public function index(): JsonResponse
    {
        return Response::success(Category::all());
    }

    public function store(CategoryRequest $request): JsonResponse
    {
        $input = $request->validated();
        $category = Category::query()->create($input);
        return Response::success($category);
    }

    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $input = $request->validated();
        $category->update($input);
        return Response::success($category);
    }

    public function destroy(Category $category): JsonResponse
    {
        $category->delete();
        return Response::success();
    }

    public function show(Category $category): JsonResponse
    {
        return Response::success($category);
    }
}
