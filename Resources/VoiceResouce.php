<?php

/**
 * Class VoiceResource
 *
 * @package App\Http\Resources\Voices
 * @property-read Voice $resource
 */
class VoiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $voice = $this->resource;

        return [
            'id' => $voice->id,
        ];
    }
}
