<?php

namespace App\Http\Controllers;

use App\Models\Level;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    use ApiResponse;
    
    public function index(Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $paginator = Level::paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse([
            'items' => $paginator->items(),
            'pagination' => $this->paginationResponse($paginator),
        ]);
    }

    public function store(StoreLevelRequest $request)
    {
        $data = [
            "title" => $request->title,
            "ball" => $request->ball,
        ];
        $level = Level::create($data);
        return $this->singleItemResponse($level);
    }

    public function show(Level $level)
    {
        return $this->singleItemResponse($level);
    }

    public function update(UpdateLevelRequest $request, Level $level)
    {
        $data = $request->validated();
        $level->update($data);
        return $this->singleItemResponse($level);
    }

    public function destroy(Level $level)
    {    
        $level->delete();
        return $this->singleItemResponse($level);
    }
}
