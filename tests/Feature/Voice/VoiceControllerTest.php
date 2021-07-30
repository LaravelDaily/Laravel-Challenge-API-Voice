<?php


namespace Tests\Feature\Voice;


use App\Exceptions\Domain\VoteNotAllowed;
use App\Exceptions\Domain\VoteOnlyOnce;
use App\Models\Question;
use App\Models\User;
use App\Models\Voice;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoiceControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        /**
         * This disables the exception handling to display the stacktrace on the console
         * the same way as it shown on the browser
         */
        parent::setUp();
        $this->withoutExceptionHandling();
    }

    public function test_user_vote_invalid_params()
    {
        $user = User::factory()->create();

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $response = $this->actingAs($user, 'api')
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-type' => 'application/json'
            ])->json(
                'POST',
                '/api/voice',
                [
                    'question_id' => 'some question id',
                    'value' => 'some value'
                ]);

        $response->assertStatus(422);
    }

    public function test_user_vote_created()
    {
        $user = User::factory()->create();
        $another_user = User::factory()->create();
        $question = Question::factory()->create();
        $question->update(['user_id' => $another_user->id]);

        $response = $this->actingAs($user, 'api')
            ->withHeaders(
                [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json'
                ])
            ->json(
                'POST',
                '/api/voice',
                [
                    'question_id' => $question->id,
                    'value' => $question->value
                ]);

        $response->assertStatus(201);
    }

    public function test_see_question_not_found()
    {
        $user = User::factory()->create();
        $question_id = 99999999;
        $value = false;

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $response = $this->actingAs($user, 'api')
            ->withHeaders(
                [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json'
                ])
            ->json(
                'POST',
                '/api/voice',
                [
                    'question_id' => $question_id,
                    'value' => $value
                ]);

        $response->assertStatus(422)
            ->assertExactJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'question_id' => [
                        'The selected question id is invalid.'
                    ]
                ]
            ]);
    }

    public function test_see_vote_not_allowed()
    {
        $user = User::factory()->create();
        $question = Question::factory()->create();

        $this->expectException(VoteNotAllowed::class);

        $response = $this->actingAs($user, 'api')
            ->withHeaders(
                [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json'
                ])
            ->json(
                'POST',
                '/api/voice',
                [
                    'question_id' => $question->id,
                    'value' => $question->value
                ]);

        $response->assertStatus(500);
    }

    public function test_see_vote_only_once()
    {
        $user = User::factory()->create();
        $another_user = User::factory()->create();

        $question = Question::factory()->create();
        $question->update(['user_id' => $another_user->id]);

        $voice = Voice::factory()->create();
        $voice->update([ 'question_id' => $question->id, 'user_id' => $user->id ]);

        $this->expectException(VoteOnlyOnce::class);

        $response = $this->actingAs($user, 'api')
            ->withHeaders(
                [
                    'Accept' => 'application/json',
                    'Content-type' => 'application/json'
                ])
            ->json(
                'POST',
                '/api/voice',
                [
                    'question_id' => $question->id,
                    'value' => (int) $voice->value
                ]);

        $response->assertStatus(500);
    }
}
