<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QuestionController;

Route::post('questions/{question}/voice', [QuestionController::class, 'voice'])->name('questions.voice');