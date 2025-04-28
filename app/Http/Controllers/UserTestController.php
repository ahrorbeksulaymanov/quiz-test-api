<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\UserAnswer;
use App\Models\UserTest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserTestController extends Controller
{

    use ApiResponse;
    
    public function startTest(Request $request)
    {
        // Foydalanuvchi testni boshlash
        $userTest = UserTest::create([
            'user_id' => Auth::id(),  // Hozirgi foydalanuvchi ID
            'test_id' => $request->test_id, // Test ID
            'start_time' => now(), // Hozirgi vaqtni yozish
        ]);
        return $this->singleItemResponse($userTest);
    }

    public function finishTest(Request $request)
    {
        $userTest = UserTest::where('user_id', Auth::id())
            ->where('test_id', $request->test_id)
            ->latest('start_time')
            ->first();
    
        if ($userTest) {
            $startTime = Carbon::parse($userTest->start_time);
            $endTime = Carbon::now();
    
            $userTest->end_time = $endTime;
            $userTest->time_spent = $startTime->diffInSeconds($endTime);
    
            $score = UserAnswer::where('user_id', Auth::id())
                ->where('test_id', $request->test_id)
                ->where('is_correct', true)
                ->count();
    
            // Testga tegishli savollarni olish
            $totalQuestions = Question::whereHas('tests', function ($query) use ($request) {
                $query->where('test_id', $request->test_id);
            })->count();
    
            // Agar savollar soni 0 bo'lsa, foizni hisoblamaymiz
            if ($totalQuestions > 0) {
                $userTest->score = ($score / $totalQuestions) * 100;
            } else {
                $userTest->score = 0; // Yoki boshqa mos qiymat (xato xabari)
            }
    
            $userTest->save();
            return $this->singleItemResponse($userTest);
        }
    
        return response()->json(['message' => 'Test topilmadi.', "status" => 0], 404);
    }
    
    public function getTestResults($test_id)
    {
        $userTest = UserTest::with('user', 'test') // Foydalanuvchi va test bilan aloqani olish
            ->where('user_id', Auth::id()) // Foydalanuvchi ID
            ->where('test_id', $test_id) // Test ID
            ->first(); // Birinchi natijani olish

        if ($userTest) {
            return $this->singleItemResponse($userTest);
        }

        return response()->json(['message' => 'Natijalar topilmadi.', "status" => 0], 404); // Agar natija topilmasa
    }
}
