<?php

use App\Http\Controllers\AgeCategoryController;
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


// Route::get("question-test", [QuestionTestController::class, "index"]);
Route::get('tests/{testId}/questions', [QuestionTestController::class, 'index']);
Route::post('tests/{testId}/questions', [TestController::class, 'questionAttach']);

Route::apiResource("categories", CategoryController::class);
Route::apiResource("levels", LevelController::class);
Route::apiResource("age-categories", AgeCategoryController::class);
Route::apiResource("tests", TestController::class);
Route::apiResource("questions", QuestionController::class);
Route::apiResource("options", OptionController::class);