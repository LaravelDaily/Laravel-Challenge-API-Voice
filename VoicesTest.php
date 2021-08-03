<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\User;
use App\Models\Voice;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Tests\TestCase;

/**
 * @internal
 * @coversNothing
 */
class VoicesTest extends TestCase
{
    use RefreshDatabase;

    public function testVoicePostIsNotPublic()
    {
        $response = $this->postJson('api/voices/1', ['value' => 1]);

        $response->assertStatus(Response::HTTP_UNAUTHORIZED);
    }

    public function testCanPostVoiceOnSomeoneElsesQuestion()
    {
        $question_user = User::factory()->create();
        $question      = Question::factory()->create(['user_id' => $question_user->id]);

        $voice_user = User::factory()->create();
        $response   = $this->actingAs($voice_user, 'api')->postJson("api/voices/{$question->id}", ['value' => 1]);

        $response->assertStatus(Response::HTTP_CREATED);

        $response->assertJson(['message' => 'Voting completed successfully']);

        $this->assertDatabaseHas(
            Voice::class,
            [
                'user_id'     => $voice_user->id,
                'question_id' => $question->id,
                'value'       => 1,
            ]);
    }

    public function testCannotPostVoiceOnNonexistingQuestion()
    {
        $question_user = User::factory()->create();
        $question      = Question::factory()->create(['user_id' => $question_user->id]);

        $voice_user = User::factory()->create();
        $response   = $this->actingAs($voice_user, 'api')->postJson('api/voices/' . ++$question->id, ['value' => '1']);

        $response->assertStatus(Response::HTTP_NOT_FOUND);
    }

    public function testCannotPostVoiceOnYourOwnQuestion()
    {
        $user     = User::factory()->create();
        $question = Question::factory()->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'api')->postJson("api/voices/{$question->id}", ['value' => '1']);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'You\'re not allowed to vote for your own question',
        ]);
    }

    public function testCannotPostSameVoiceTwice()
    {
        $question_user = User::factory()->create();
        $question      = Question::factory()->create(['user_id' => $question_user->id]);

        $voice_user = User::factory()->create();
        $this->actingAs($voice_user, 'api')->postJson("api/voices/{$question->id}", ['value' => '1']);

        $response = $this->actingAs($voice_user, 'api')->postJson("api/voices/{$question->id}", ['value' => '1', 'aaa' => 'bbb']);

        $response->assertStatus(Response::HTTP_FORBIDDEN);
        $response->assertJson([
            'message' => 'You already voted',
        ]);
    }

    public function testCanUpdateYourOwnVoice()
    {
        $question_user = User::factory()->create();
        $question      = Question::factory()->create(['user_id' => $question_user->id]);

        $voice_user = User::factory()->create();
        $this->actingAs($voice_user, 'api')->postJson("api/voices/{$question->id}", ['value' => '1']);

        $response = $this->actingAs($voice_user, 'api')->postJson("api/voices/{$question->id}", ['value' => '0']);

        $response->assertStatus(Response::HTTP_ACCEPTED);

        $response->assertJson([
            'message' => 'Your vote updated',
        ]);
    }
}
