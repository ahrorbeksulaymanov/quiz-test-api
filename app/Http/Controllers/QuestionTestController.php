<?php

namespace App\Http\Controllers;

use App\Models\Test;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class QuestionTestController extends Controller
{
    use ApiResponse;
    
    public function index(Request $request, $testId)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);
        $search = $request->query('q');
    
        $test = Test::findOrFail($testId);
    
        $query = $test->questions();
    
        if ($search) {
            $query->where('title', 'like', "%{$search}%");
        }
    
        // Pagination
        $paginator = $query->paginate($perPage, ['*'], 'page', $page);
    
        return $this->successResponse([
            'items' => $paginator->items(),
            'pagination' => $this->paginationResponse($paginator),
        ]);
    }
    
}
