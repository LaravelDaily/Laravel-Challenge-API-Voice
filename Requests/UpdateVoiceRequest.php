<?php

/**
 * Class UpdateUserRequest
 *
 * @package App\Http\Requests\Users
 */
class UpdateVoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        if (!$this->getVoice()->user->is($this->user())) {
            throw (new ModelNotFoundException())->setModel(
                Voice::class, $this->getVoice()->id
            );
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'voice_id' => [
                'required',
                Rule::unique('voices', 'id'),
                'int',
            ],
        ];
    }

    /**
     * Get the voice that belongs to the request.
     *
     * @return Voice
     */
    public function getVoice(): ?Voice
    {
        /** @var Voice $voice */
        $voice = $this->route('voice');

        return $voice;
    }
}
