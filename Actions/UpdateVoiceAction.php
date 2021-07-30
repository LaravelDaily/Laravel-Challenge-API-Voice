<?php

/**
 * Class UpdateVoiceAction
 */
class UpdateVoiceAction
{
    /**
     * @param UpdateVoiceRequest $request
     * @param Voice              $voice
     *
     * @return Voice
     */
    public function handle(UpdateVoiceRequest $request, Voice $voice): Voice
    {
        $voice->value = $request->input('value');

        $voice->save();

        return $voice;
    }
}
