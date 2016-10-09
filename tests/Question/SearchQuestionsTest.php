<?php

use App\Question;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchQuestionsTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that you can search for questions.
     *
     * TODO: Should not speak to third party service.
     *
     * @test
     */
    public function youCanSearchForQuestions()
    {
        return;

        // Arrange...
        Question::enableSearchSyncing();

        $includedQuestion = factory(Question::class)->create([
            'description' => 'Why do planes fly?'
        ]);

        $excludedQuestion = factory(Question::class)->create([
            'description' => 'Why do not cars fly?'
        ]);

        // Act ...
        $this->json('get', 'questions', [
            'search' => 'plane'
        ]);

        // Assert...
        $this->seeJson([
            'id' => $includedQuestion->id
        ]);

        $this->dontSeeJson([
            'id' => $excludedQuestion->id
        ]);

        $includedQuestion->delete();
        $excludedQuestion->delete();
    }
}