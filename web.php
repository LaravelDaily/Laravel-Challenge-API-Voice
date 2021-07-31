<?php

use App\Http\Controllers\VoteQuestionController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::post('questions/{question}/vote', VoteQuestionController::class);
});

