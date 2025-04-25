<?php

namespace App\Http\Controllers;

use App\Models\AgeCategory;
use App\Http\Requests\StoreAgeCategoryRequest;
use App\Http\Requests\UpdateAgeCategoryRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class AgeCategoryController extends Controller
{
    use ApiResponse;
    
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $paginator = AgeCategory::paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse([
            'items' => $paginator->items(),
            'pagination' => $this->paginationResponse($paginator),
        ]);
    }

    public function store(StoreAgeCategoryRequest $request)
    {
        $data = [
            "title" => $request->title,
            "from" => $request->from,
            "to" => $request->to,
        ];
        $level = AgeCategory::create($data);
        return $this->singleItemResponse($level);
    }

    public function show(AgeCategory $ageCategory)
    {
        return $this->singleItemResponse($ageCategory);
    }

    public function update(UpdateAgeCategoryRequest $request, AgeCategory $ageCategory)
    {
        $data = $request->validated();
        $ageCategory->update($data);
        return $this->singleItemResponse($ageCategory);
    }

    public function destroy(AgeCategory $ageCategory)
    {    
        $ageCategory->delete();
        return $this->singleItemResponse($ageCategory);
    }
}
