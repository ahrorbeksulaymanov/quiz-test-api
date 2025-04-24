<?php

namespace App\Http\Controllers;

use App\Filters\UniversalFilter;
use App\Models\Question;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    use ApiResponse;
    
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $allowedFilters = ['test_id', 'level_id', 'category_id'];
        $query = (new UniversalFilter($request, $allowedFilters))->apply(Question::query());
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse([
            'items' => $paginator->items(),
            'pagination' => $this->paginationResponse($paginator),
        ]);
    }

    public function store(StoreQuestionRequest $request)
    {
        $validated = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'photo' => 'nullable|string',
            'order' => 'nullable|integer',
            'level_id' => 'nullable|exists:levels,id',
            'category_id' => 'nullable|exists:categories,id',
            'correct_answer_id' => 'nullable|integer',
            'ball' => 'required|integer',
            'test_ids' => 'required|array',
        ]);
    
        $question = Question::create($validated);
    
        $question->tests()->attach($validated['test_ids']);
    
        return $this->singleItemResponse($question, "Muvaffaqqiyatli saqlandi!");
    }

    public function show(Question $question)
    {
        //
    }

    public function edit(Question $question)
    {
        //
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        //
    }

    public function destroy(Question $question)
    {
        //
    }
}
