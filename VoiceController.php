<?php

class VoiceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param StoreVoiceRequest $request
     * @param StoreVoiceAction  $action
     *
     * @return VoiceResource
     * @throws Exception
     */
    public function store(StoreVoiceRequest $request, StoreVoiceAction $action): VoiceResource
    {
        $voice = $action->handle($request);

        return new VoiceResource($voice);
    }

    /**
     * Display the specified resource.
     *
     * @param ShowVoiceRequest $request
     * @param Voice            $voice
     *
     * @return VoiceResource
     */
    public function show(ShowVoiceRequest $request, Voice $voice): VoiceResource
    {
        return new VoiceResource($voice);
    }

    /**
     * Update an voice.
     *
     * @param UpdateVoiceRequest $request
     * @param Voice              $voice
     * @param UpdateVoiceAction  $action
     *
     * @return VoiceResource
     * @throws Exception
     */
    public function update(UpdateVoiceRequest $request, Voice $voice, UpdateVoiceAction $action): VoiceResource
    {
        $voice = $action->handle($request, $voice);

        return new VoiceResource($voice);
    }
}
