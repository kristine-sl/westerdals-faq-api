<?php

use App\Subject;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class CreateSubjectTest extends TestCase
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
        $subject = factory(Subject::class)->make();

        // Act...
        $this->json('post', 'subjects', $subject->toArray());

        // Assert...
        $this->seeError('unauthenticated', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test that you can create subjects.
     *
     * @test
     */
    public function youCanCreateSubjects()
    {
        // Arrange...
        $this->login();
        $subject = factory(Subject::class)->make();

        // Act...
        $this->json('post', 'subjects', $subject->toArray());

        // Assert...
        $this->seeStatusCode(Response::HTTP_CREATED);
        $this->seeSuccessStructure([
            'id',
            'name'
        ])->seeInDatabase('subjects', [
            'id' => $this->getSuccessData('id')
        ]);
    }

    /**
     * Test that name parameter must be present.
     *
     * @test
     */
    public function nameParameterShouldBeRequired()
    {
        // Arrange...
        $this->login();
        $subject = factory(Subject::class)->make();

        // Act...
        $this->json('post', 'subjects', array_except($subject->toArray(), 'name'));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that name parameter must be a string value.
     *
     * @test
     */
    public function nameParameterShouldBeString()
    {
        // Arrange...
        $this->login();
        $subject = factory(Subject::class)->make();

        // Act...
        $this->json('post', 'subjects', array_merge($subject->toArray(), [
            'name' => 123
        ]));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that name parameter must be less than 50 characters long.
     *
     * @test
     */
    public function nameParameterShouldBeMaximumFiftyCharacters()
    {
        // Arrange...
        $this->login();
        $subject = factory(Subject::class)->make();

        // Act...
        $this->json('post', 'subjects', array_merge($subject->toArray(), [
            'name' => str_random(51)
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
        $subject = factory(Subject::class)->make();

        // Act...
        $this->json('post', 'subjects', array_merge($subject->toArray(), [
            'with' => 'faculty'
        ]));

        // Assert...
        $this->seeSuccessStructure([
            'faculty'
        ]);
    }
}