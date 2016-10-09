<?php

use App\Question;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class AnswerQuestionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that it fails if you're not logged in.
     *
     * @test
     */
    public function youMustBeLoggedIn()
    {
        // Arrange...
        $question = factory(Question::class)->create();
        $input = factory(Question::class)->make()->toArray();

        // Act ...
        $this->json('put', "questions/$question->id", $input);

        // Assert...
        $this->seeError('unauthenticated', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test that you can answer questions.
     *
     * @test
     */
    public function youCanAnswerQuestions()
    {
        // Arrange...
        $this->login();
        $question = factory(Question::class)->create();
        $input = factory(Question::class)->make()->toArray();

        // Act ...
        $this->json('put', "questions/$question->id", $input);

        // Assert...
        $this->seeSuccess($question->fresh(), Response::HTTP_OK);
        $this->seeSuccessStructure([
            'answer',
            'answeredAt',
            'description',
            'id',
            'lectureId'
        ]);
    }

    /**
     * Test that answer parameter cannot be set explicitly.
     *
     * @test
     */
    public function youCantSetAnsweredAtManually()
    {
        // Arrange...
        $this->login();
        $question = factory(Question::class)->create();
        $input = factory(Question::class)->make()->toArray();

        // Act ...
        $this->json('put', "questions/$question->id", array_merge($input, [
            'answered_at' => Carbon::yesterday()
        ]));

        // Assert...
        $this->seeJson([
            'answeredAt' => (string) Carbon::now()
        ]);
    }

    /**
     * Test that you cannot change description on update.
     *
     * @test
     */
    public function youCantChangeDescription()
    {
        // Arrange...
        $this->login();
        $question = factory(Question::class)->create();
        $input = factory(Question::class)->make()->toArray();

        // Act ...
        $this->json('put', "questions/$question->id", array_merge($input, [
            'description' => 'foo'
        ]));

        // Assert...
        $this->seeJson([
            'description' => $question->description
        ]);
    }

    /**
     * Test that answer parameter must be present.
     *
     * @test
     */
    public function answerParameterShouldBeRequired()
    {
        // Arrange...
        $this->login();
        $question = factory(Question::class)->create();
        $input = factory(Question::class)->make()->toArray();

        // Act...
        $this->json('put', "questions/$question->id", array_except($input, 'answer'));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that answer parameter must be a string.
     *
     * @test
     */
    public function answerParameterShouldBeAString()
    {
        // Arrange...
        $this->login();
        $question = factory(Question::class)->create();
        $input = factory(Question::class)->make()->toArray();

        // Act...
        $this->json('put', "questions/$question->id", array_merge($input, [
            'answer' => 123
        ]));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that you can fetch associated resources.
     *
     * @test
     */
    public function youCanFetchRelations()
    {
        // Arrange...
        $this->login();
        $question = factory(Question::class)->create();
        $input = factory(Question::class)->make()->toArray();

        // Act...
        $this->json('put', "questions/$question->id", array_merge($input, [
            'with' => 'lecture'
        ]));

        // Assert...
        $this->seeSuccessStructure([
            'lecture'
        ]);
    }
}