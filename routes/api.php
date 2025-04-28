<?php

use App\Http\Controllers\AgeCategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionTestController;
use App\Http\Controllers\TestController;
use App\Http\Controllers\UserAnswerController;
use App\Http\Controllers\UserTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {
    Route::post("/login", [AuthController::class, "login"]);
    Route::post("/register", [AuthController::class, "register"]);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post("/logout", [AuthController::class, "logout"]);
        Route::get("/me", [AuthController::class, "me"]);
    });
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('tests/{testId}/questions', [QuestionTestController::class, 'index']);
    Route::post('tests/{testId}/questions', [TestController::class, 'questionAttach']);

    Route::post('/test/start', [UserTestController::class, 'startTest']); // Testni boshlash
    Route::post('/test/finish', [UserTestController::class, 'finishTest']); // Testni tugatish
    Route::get('/test/results/{test_id}', [UserTestController::class, 'getTestResults']); // test natijasini olish
    
    Route::post('/answer/save', [UserAnswerController::class, 'saveAnswer']);  // Javobni saqlash
    Route::put('/answer/update/{id}', [UserAnswerController::class, 'updateAnswer']);  // Javobni yangilash
    Route::get('/answers/{test_id}', [UserAnswerController::class, 'getUserAnswers']);  // Foydalanuvchi javoblarini olish
});

Route::apiResource("categories", CategoryController::class);

$routes_array = [
    'categories' => CategoryController::class,
    'levels' => LevelController::class,
    'age-categories' => AgeCategoryController::class,
    'tests' => TestController::class,
    'questions' => QuestionController::class,
    'options' => OptionController::class,
];

Route::apiResources($routes_array, ['only' => ['index', 'show']]);

Route::middleware('auth:sanctum')->group(function () use ($routes_array) {
    Route::apiResources($routes_array, ['except' => ['index', 'show']]);
});
