<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use ApiResponse;
    
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $paginator = Category::paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse([
            'items' => $paginator->items(),
            'pagination' => $this->paginationResponse($paginator),
        ]);
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = [
            "title" => $request->title,
        ];

        $category = Category::create($data);
        return $this->singleItemResponse($category);
    }

    public function show(Category $category)
    {
        return $this->singleItemResponse($category);
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {    
        $data = $request->validated();
    
        $category->update($data);
        return $this->singleItemResponse($category);
    }

    public function destroy(Category $category)
    {    
        $category->delete();
        return $this->singleItemResponse($category);
    }
}
