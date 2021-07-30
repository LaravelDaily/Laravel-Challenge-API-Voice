<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoiceRequest;
use App\Usecases\NewVoiceFromUser;

class VoiceController extends Controller
{
    public function voice(VoiceRequest $request){
        $useCase = new NewVoiceFromUser($request);
        return $useCase->updateOrCreateVote();
    }
}
