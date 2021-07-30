<?php

/**
 * Class StoreVoiceAction
 */
class StoreVoiceAction
{
    /**
     * @param StoreVoiceRequest $request
     *
     * @return Voice
     */
    public function handle(StoreVoiceRequest $request): Voice
    {
        $voice = new Voice();

        $voice->value = $request->input('value');

        $voice->save();

        return $voice;
    }
}
