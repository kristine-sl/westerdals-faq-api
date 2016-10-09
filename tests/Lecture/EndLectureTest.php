<?php

use App\Lecture;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class EndLectureTest extends TestCase
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
        $lecture = factory(Lecture::class)->create();

        // Act...
        $this->json('delete', "lectures/$lecture->id");

        // Assert...
        $this->seeError('unauthenticated', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test that you can end lectures.
     *
     * @test
     */
    public function youCanEndLectures()
    {
        // Arrange...
        $this->login();
        $lecture = factory(Lecture::class)->create();

        // Act...
        $this->json('delete', "lectures/$lecture->id");

        // Assert...
        $this->seeSuccess($lecture->fresh(), Response::HTTP_OK);
        $this->seeJson([
            'endedAt' => (string) Carbon::now()
        ])->seeInDatabase('lectures', [
            'id' => $lecture->id,
            'ended_at' => (string) Carbon::now()
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
        $this->login();
        $lecture = factory(Lecture::class)->create();

        // Act...
        $this->json('delete', "lectures/$lecture->id", [
            'with' => 'subject'
        ]);

        // Assert...
        $this->seeSuccessStructure([
            'subject'
        ]);
    }
}