<?php

use App\Http\Controllers\QuizController;

Route::multilingual('/quizzes/{quiz}', [QuizController::class, 'show'])
    ->name('quizzes.show');

Route::multilingual('/quizzes/{quiz}/result', [QuizController::class, 'storeQuizResult'])
    ->method('post')
    ->name('quizzes.show-result');
