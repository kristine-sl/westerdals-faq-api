<?php

use App\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class FetchQuestionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that you can fetch questions.
     *
     * @test
     */
    public function youCanFetchQuestions()
    {
        // Arrange...
        $questions = factory(Question::class, 3)->create();

        // Act...
        $this->json('get', 'questions');

        // Assert...
        $this->seeSuccess($questions, Response::HTTP_OK);
        $this->seeSuccessStructure([
            '*' => [
                'answer',
                'answeredAt',
                'description',
                'id',
                'lectureId'
            ]
        ]);
    }

    /**
     * Test that you can fetch question by its id.
     *
     * @test
     */
    public function youCanFetchQuestionById()
    {
        // Arrange...
        $question = factory(Question::class)->create();

        // Act...
        $this->json('get', "questions/$question->id");

        // Assert...
        $this->seeSuccess($question, Response::HTTP_OK);
        $this->seeSuccessStructure([
            'answer',
            'answeredAt',
            'description',
            'id',
            'lectureId'
        ]);
    }

    /**
     * Test that you can fetch associated resources.
     *
     * @test
     */
    public function youCanFetchRelations()
    {
        // Arrange...
        factory(Question::class, 3)->create();

        // Act...
        $this->json('get', 'questions', [
            'with' => [
                'lecture'
            ]
        ]);

        // Assert...
        $this->seeSuccessStructure([
            '*' => [
                'lecture'
            ]
        ]);
    }

    /**
     * Test that you can fetch resources associated with a specific question.
     *
     * @test
     */
    public function youCanFetchRelationsByQuestion()
    {
        // Act...
        $question = factory(Question::class)->create();

        // Act...
        $this->json('get', "questions/$question->id", [
            'with' => [
                'lecture'
            ]
        ]);

        // Assert...
        $this->seeSuccessStructure([
            'lecture'
        ]);
    }
}