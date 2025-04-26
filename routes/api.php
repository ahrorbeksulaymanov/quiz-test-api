<?php

use App\Http\Controllers\AgeCategoryController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\OptionController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionTestController;
use App\Http\Controllers\TestController;
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





























// Route::apiResource("levels", LevelController::class);
// Route::apiResource("age-categories", AgeCategoryController::class);
// Route::apiResource("tests", TestController::class);
// Route::apiResource("questions", QuestionController::class);
// Route::apiResource("options", OptionController::class);