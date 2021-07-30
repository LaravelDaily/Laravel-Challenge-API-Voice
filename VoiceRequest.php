class VoiceRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'question_id' => 'required|exists:questions',
            'value' => 'required|boolean',
        ];
    }
}