<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\VoiceRequest;
use App\User;
use Auth;
use Illuminate\Routing\Controller as BaseController;

abstract class AbstractController extends BaseController
{
    protected User $user;
    
    public function __construct(VoiceRequest $request)
    {
        $request->replace($request->validated());
        $this->user = Auth::user();
    }
}
