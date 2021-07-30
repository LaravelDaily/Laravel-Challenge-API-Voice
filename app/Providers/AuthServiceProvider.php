<?php

namespace App\Providers;

use App\Models\Question;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('vote', function (User $user, Question $question) {
            return $user->id !== $question->user_id
                ? Response::allow()
                : Response::deny('You are not allowed to vote on your own question.');
        });
    }
}