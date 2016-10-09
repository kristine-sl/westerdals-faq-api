<?php

use App\Lecture;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class StartLectureTest extends TestCase
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
        $lecture = factory(Lecture::class)->make();

        // Act...
        $this->json('post', 'lectures', $lecture->toArray());

        // Assert...
        $this->seeError('unauthenticated', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test that you can start lectures.
     *
     * @test
     */
    public function youCanStartLectures()
    {
        // Arrange...
        $this->login();
        $lecture = factory(Lecture::class)->make();

        // Act...
        $this->json('post', 'lectures', $lecture->toArray());

        // Assert...
        $this->seeStatusCode(Response::HTTP_CREATED);
        $this->seeSuccessStructure([
            'accessCode',
            'endedAt',
            'id',
            'startedAt',
            'subjectId'
        ])->seeInDatabase('lectures', [
            'id' => $this->getSuccessData('id')
        ]);
    }

    /**
     * Test that started at parameter cannot be set explicitly.
     *
     * @test
     */
    public function youCantSetStartedAtManually()
    {
        // Arrange...
        $this->login();
        $lecture = factory(Lecture::class)->make();
        $yesterday = Carbon::yesterday()->toDateTimeString();

        // Act...
        $this->json('post', 'lectures', array_merge($lecture->toArray(), [
            'started_at' => $yesterday
        ]));

        // Assert...
        $this->dontSeeJson([
            'startedAt' => $yesterday
        ]);
    }

    /**
     * Test that access code parameter cannot be set explicitly.
     *
     * @test
     */
    public function itShouldSetAccessCode()
    {
        // Arrange...
        $this->login();
        $lecture = factory(Lecture::class)->make();

        // Act...
        $this->json('post', 'lectures', $lecture->toArray() );

        // Assert...
        $this->dontSeeJson([
            'accessCode' => ''
        ]);
    }

    /**
     * Test that access code parameter cannot be set explicitly.
     *
     * @test
     */
    public function youCantSetAccessCodeManually()
    {
        // Arrange...
        $this->login();
        $lecture = factory(Lecture::class)->make();

        // Act...
        $this->json('post', 'lectures', $lecture->toArray() );

        // Assert...
        $this->dontSeeJson([
            'accessCode' => $lecture->access_code
        ]);
    }

    /**
     * Test that ended at parameter should be set to null when starting a lecture.
     *
     * @test
     */
    public function itShoulNotSetEndedAt()
    {
        // Arrange...
        $this->login();
        $lecture = factory(Lecture::class)->make();

        // Act...
        $this->json('post', 'lectures', array_merge($lecture->toArray(), [
            'ended_at' => Carbon::yesterday()->toDateTimeString()
        ]));

        // Assert...
        $this->seeJson([
            'endedAt' => null
        ]);
    }

    /**
     * Test that subject id parameter must be present.
     *
     * @test
     */
    public function subjectIdParameterShouldBeRequired()
    {
        // Arrange...
        $this->login();
        $lecture = factory(Lecture::class)->make();

        // Act...
        $this->json('post', 'lectures', array_except($lecture->toArray(), 'subject_id'));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that subject id parameter must be an integer.
     *
     * @test
     */
    public function subjectIdParameterShouldBeInteger()
    {
        // Arrange...
        $this->login();
        $lecture = factory(Lecture::class)->make();

        // Act...
        $this->json('post', 'lectures', array_merge($lecture->toArray(), [
            'subject_id' => 'foo'
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
        $lecture = factory(Lecture::class)->make();

        // Act...
        $this->json('post', 'lectures', array_merge($lecture->toArray(), [
            'with' => 'subject'
        ]));

        // Assert...
        $this->seeSuccessStructure([
            'subject'
        ]);
    }
}