<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\UserAnswer;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserAnswerController extends Controller
{

    use ApiResponse;

    public function saveAnswer(Request $request)
    {
        // Foydalanuvchi tomonidan kiritilgan javobni saqlashdan oldin validatsiya qilish
        $request->validate([
            'question_id' => 'required|exists:questions,id',
            'answer_id' => 'required|exists:options,id',
            'test_id' => 'required|exists:tests,id',
        ]);
    
        // Savolni olish
        $question = Question::findOrFail($request->question_id);
    
        // To'g'ri javobni olish
        $correctAnswer = $question->correct_answer_id;  // `correct_answer_id` bu savolga tegishli to'g'ri javobni belgilaydi
    
        // Javobni tekshirish
        $isCorrect = $correctAnswer == $request->answer_id;
    
        // Foydalanuvchi javobini saqlash
        $userAnswer = UserAnswer::create([
            'user_id' => Auth::id(),  // Hozirgi foydalanuvchi ID
            'test_id' => $request->test_id,  // Test ID
            'question_id' => $request->question_id,  // Savol ID
            'answer_id' => $request->answer_id,  // Javob ID
            'is_correct' => $isCorrect  // To'g'ri yoki noto'g'ri javobni hisoblash
        ]);
    
        return $this->singleItemResponse($userAnswer);
    }
    

    public function updateAnswer(Request $request, $id)
    {
        $request->validate([
            'answer_id' => 'required|exists:options,id',
            'test_id' => 'required|exists:tests,id',
            'question_id' => 'required|exists:questions,id',
        ]);
    
        $userAnswer = UserAnswer::findOrFail($id);
        $question = Question::findOrFail($request->question_id);
        $correctAnswer = $question->correct_answer_id;
    
        $isCorrect = $correctAnswer == $request->answer_id;
    
        $userAnswer->answer_id = $request->answer_id;
        $userAnswer->is_correct = $isCorrect;
        $userAnswer->save();
    
        return $this->singleItemResponse($userAnswer);
    }
    
    public function getUserAnswers($test_id, Request $request)
    {
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 10);

        $userAnswers = UserAnswer::with('question', 'answer')
            ->where('user_id', Auth::id())
            ->where('test_id', $test_id)
            ->paginate($perPage, ['*'], 'page', $page);

        return $this->successResponse([
            'items' => $userAnswers->items(),
            'pagination' => $this->paginationResponse($userAnswers),
        ]);
    }
}
