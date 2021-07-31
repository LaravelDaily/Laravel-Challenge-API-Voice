<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\VoiceRequest as Request;
use App\Models\Voice;

class VoiceController extends AbstractController
{
    public function __construct(Request $request, private \App\Services\VoiceService $voiceService)
    {
        parent::__construct($request);
    }

    public function store(Request $request): Voice
    {
        return $this->voiceService->createVoice($this->user);
    }
}
