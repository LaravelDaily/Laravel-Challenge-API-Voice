<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use App\Models\Voice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->actingAs($this->user);
        $this->question = Question::factory()->create();
    }

    public function test_create_voice()
    {
        $response = $this->post(
            route('voice.upsert'),
            ['question_id' => $this->question->id, 'value' => true]
        );

        $this->assertDatabaseHas(
            'voices',
            [
                'user_id' => $this->user->id,
                'question_id' => $this->question->id,
                'value' => true
            ]
        );
        $response->assertExactJson(
            [
                'status' => 200,
                'message'=>'Voting completed successfully'
            ]
        );
    }

    public function test_update_voice()
    {
        $voice = Voice::factory()->create(
            [
                'question_id' => $this->question->id,
                'user_id' => $this->user->id
            ]
        );
        $response = $this->post(
            route('voice.upsert'),
            ['question_id' => $this->question->id, 'value' => false]
        );

        $this->assertDatabaseHas(
            'voices',
            [
                'id' => $voice->id,
                'value' => false
            ]
        );
        $response->assertExactJson(
            [
                'status' => 201,
                'message'=>'update your voice'
            ]
        );
    }

    public function test_404()
    {
        $response = $this->post(
            route(
                'voice.upsert',
                ['question_id' => $this->question->id, 'value' => true]
            )
        );

        $response->assertExactJson(
            [
                'status' => 404,
                'message'=>'not found question ..'
            ]
        );
    }

    public function test_nonexistent_question()
    {
        $response = $this->post(
            route('voice.upsert'),
            ['question_id' => 0, 'value' => false]
        );

        $response->assertInvalid('question_id');
    }

    public function test_not_allowed()
    {
        $response = $this->post(
            route('voice.upsert'),
            [
                'question_id' => Question::factory()->create(['user_id' => $this->user->id])->id,
                'value' => false
            ]
        );

        $response->assertExactJson(
            [
                'status' => 500,
                'message'=>'The user is not allowed to vote to your question'
            ]
        );
    }
}
