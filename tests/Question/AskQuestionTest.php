<?php

use App\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class AskQuestionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that you can ask questions.
     *
     * @test
     */
    public function youCanAskQuestions()
    {
        // Arrange...
        $question = factory(Question::class)->make();

        // Act ...
        $this->json('post', 'questions', $question->toArray());

        // Assert...
        $this->seeStatusCode(Response::HTTP_CREATED);
        $this->seeSuccessStructure([
            'answer',
            'answeredAt',
            'description',
            'id',
            'lectureId'
        ])->seeInDatabase('questions', [
            'id' => $this->getSuccessData('id')
        ]);
    }

    /**
     * Test that answer parameter cannot be set explicitly.
     *
     * @test
     */
    public function youCantSetAnswerManually()
    {
        // Arrange...
        $question = factory(Question::class)->make();

        // Act ...
        $this->json('post', 'questions', $question->toArray());

        // Assert...
        $this->seeJson([
            'answer' => null,
            'answeredAt' => null
        ]);
    }

    /**
     * Test that description parameter must be present.
     *
     * @test
     */
    public function descriptionParameterShouldBeRequired()
    {
        // Arrange...
        $question = factory(Question::class)->make();

        // Act...
        $this->json('post', 'questions', array_except($question->toArray(), 'description'));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that description parameter must be a string.
     *
     * @test
     */
    public function descriptionParameterShouldBeAString()
    {
        // Arrange...
        $question = factory(Question::class)->make();

        // Act...
        $this->json('post', 'questions', array_merge($question->toArray(), [
            'description' => 123
        ]));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that lecture id parameter must be present.
     *
     * @test
     */
    public function lectureIdParameterShouldBeRequired()
    {
        // Arrange...
        $question = factory(Question::class)->make();

        // Act...
        $this->json('post', 'questions', array_except($question->toArray(), 'lecture_id'));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that lecture id parameter must be an integer.
     *
     * @test
     */
    public function lectureIdParameterShouldBeInteger()
    {
        // Arrange...
        $question = factory(Question::class)->make();

        // Act...
        $this->json('post', 'questions', array_merge($question->toArray(), [
            'lecture_id' => 'foo'
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
        $question = factory(Question::class)->make();

        // Act...
        $this->json('post', 'questions', array_merge($question->toArray(), [
            'with' => 'lecture'
        ]));

        // Assert...
        $this->seeSuccessStructure([
            'lecture'
        ]);
    }
}