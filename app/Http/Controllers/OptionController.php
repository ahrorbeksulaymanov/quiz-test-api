<?php

namespace App\Http\Controllers;

use App\Filters\UniversalFilter;
use App\Models\Option;
use App\Http\Requests\StoreOptionRequest;
use App\Http\Requests\UpdateOptionRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OptionController extends Controller
{
    use ApiResponse;
    
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $allowedFilters = ['question_id'];
        $query = (new UniversalFilter($request, $allowedFilters))->apply(Option::query());
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse([
            'items' => $paginator->items(),
            'pagination' => $this->paginationResponse($paginator),
        ]);
    }

    public function store(StoreOptionRequest $request)
    {
        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('photos', 'public');
        }

        $data = [
            "title" => $request->title,
            "question_id" => $request->question_id,
            "photo" => $imagePath ?? null,
            "is_correct" => $request->is_correct,
        ];

        $test = Option::create($data);
        return $this->singleItemResponse($test);
    }

    public function show(Option $option)
    {
        return $this->singleItemResponse($option);
    }

    public function update(UpdateOptionRequest $request, Option $option)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            if ($option->photo && Storage::disk('public')->exists($option->photo)) {
                Storage::disk('public')->delete($option->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        } else {
            $data['photo'] = $option->photo;
        }
    
        $option->update($data);
        return $this->singleItemResponse($option);
    }

    public function destroy(Option $option)
    {    
        $option->delete();
        return $this->singleItemResponse($option);
    }
}
