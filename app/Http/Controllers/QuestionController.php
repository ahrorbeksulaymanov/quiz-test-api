<?php

namespace App\Http\Controllers;

use App\Filters\UniversalFilter;
use App\Models\Question;
use App\Http\Requests\StoreQuestionRequest;
use App\Http\Requests\UpdateQuestionRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $imagePath = null;
        if ($request->hasFile('photo')) {
            $imagePath = $request->file('photo')->store('photos', 'public');
        }
        $data = [
            "title" => $request->title,
            "description" => $request->description,
            "order" => $request->order,
            "level_id" => $request->level_id,
            "category_id" => $request->category_id,
            "ball" => $request->ball,
            "photo" => $imagePath ?? null,
        ];

        $testIds = is_string($request->test_ids)
            ? json_decode($request->test_ids, true)
            : $request->test_ids;

        $question = Question::create($data);
        $question->tests()->attach($testIds);
        return $this->singleItemResponse($question, "Muvaffaqqiyatli saqlandi!");
    }

    public function show(Question $question)
    {
        return $this->singleItemResponse($question);
    }

    public function update(UpdateQuestionRequest $request, Question $question)
    {
        $data = $request->validated();
        if ($request->hasFile('photo')) {
            if ($question->photo && Storage::disk('public')->exists($question->photo)) {
                Storage::disk('public')->delete($question->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        } else {
            $data['photo'] = $question->photo;
        }

        $testIds = is_string($request->test_ids)
            ? json_decode($request->test_ids, true)
            : $request->test_ids;
    
        $question->tests()->detach();

        $question->update($data);
        $question->tests()->attach($testIds);
        return $this->singleItemResponse($question);
    }

    public function destroy(Question $question)
    {    
        $question->delete();
        return $this->singleItemResponse($question);
    }
}
