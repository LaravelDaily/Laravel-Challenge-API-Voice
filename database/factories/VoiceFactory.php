<?php

namespace Database\Factories;

use App\Models\Question;
use App\Models\User;
use App\Models\Voice;
use Illuminate\Database\Eloquent\Factories\Factory;

class VoiceFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Voice::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'user_id' => User::all()->random()->id,
            'question_id' => Question::all()->random()->id,
            'value' => $this->faker->boolean
        ];
    }
}
