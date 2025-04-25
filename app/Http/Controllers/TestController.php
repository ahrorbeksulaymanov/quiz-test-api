<?php

namespace App\Http\Controllers;

use App\Filters\UniversalFilter;
use App\Models\Test;
use App\Http\Requests\StoreTestRequest;
use App\Http\Requests\UpdateTestRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestController extends Controller
{
    use ApiResponse;
    
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $allowedFilters = ['age_category_id'];
        $query = (new UniversalFilter($request, $allowedFilters))->apply(Test::query());
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse([
            'items' => $paginator->items(),
            'pagination' => $this->paginationResponse($paginator),
        ]);
    }

    public function store(StoreTestRequest $request)
    {

        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('photos', 'public');
        }

        $data = [
            "title" => $request->title,
            "description" => $request->description,
            "order" => $request->order,
            "ball" => $request->ball,
            "duration" => $request->duration,
            "age_category_id" => $request->age_category_id,
            "photo" => $imagePath ?? null,
        ];

        $test = Test::create($data);
        return $this->singleItemResponse($test);
    }

    public function show(Test $test)
    {
        return $this->singleItemResponse($test);
    }

    public function update(UpdateTestRequest $request, Test $test)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            if ($test->photo && Storage::disk('public')->exists($test->photo)) {
                Storage::disk('public')->delete($test->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        } else {
            $data['photo'] = $test->photo;
        }
    
        $test->update($data);
        return $this->singleItemResponse($test);
    }

    public function destroy(Test $test)
    {    
        $test->delete();
        return $this->singleItemResponse($test);
    }

    public function questionAttach(Request $request, $testId)
    {
        $validated = $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
        ]);

        $test = Test::findOrFail($testId);

        $test->questions()->attach($validated['question_ids']);
        return $this->singleItemResponse($test);

    }
}
