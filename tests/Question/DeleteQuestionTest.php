<?php

use App\Question;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DeleteQuestionTest extends TestCase
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

        // Act...
        $this->json('delete', "questions/$question->id");

        // Assert...
        $this->seeError('unauthenticated', \Illuminate\Http\Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test that you can delete questions.
     *
     * @test
     */
    public function youCanDeleteQuestions()
    {
        // Arrange...
        $this->login();
        $question = factory(Question::class)->create();

        // Act...
        $this->json('delete', "questions/$question->id");

        // Assert...
        $this->seeInDatabase('questions', [
            'deleted_at' => Carbon::now()
        ]);
    }
}