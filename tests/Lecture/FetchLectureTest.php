<?php

use App\Lecture;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class FetchLectureTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that you can fetch lectures.
     *
     * @test
     */
    public function youCanFetchLectures()
    {
        // Arrange...
        $lectures = factory(Lecture::class, 3)->create();

        // Act...
        $this->json('get', 'lectures');

        // Assert...
        $this->seeSuccess($lectures, Response::HTTP_OK);
        $this->seeSuccessStructure([
            '*' => [
                'accessCode',
                'endedAt',
                'id',
                'startedAt',
                'subjectId'
            ]
        ]);
    }

    /**
     * Test that you can fetch lecture by its id.
     *
     * @test
     */
    public function youCanFetchLectureById()
    {
        // Arrange...
        $lecture = factory(Lecture::class)->create();

        // Act...
        $this->json('get', "lectures/$lecture->id");

        // Assert...
        $this->seeSuccess($lecture, Response::HTTP_OK);
        $this->seeSuccessStructure([
            'endedAt',
            'id',
            'startedAt',
            'subjectId'
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
        factory(Lecture::class, 3)->create();

        // Act...
        $this->json('get', 'lectures', [
            'with' => [
                'subject'
            ]
        ]);

        // Assert...
        $this->seeSuccessStructure([
            '*' => [
                'subject'
            ]
        ]);
    }

    /**
     * Test that you can fetch resources associated with a specific lecture.
     *
     * @test
     */
    public function youCanFetchRelationsByLecture()
    {
        // Act...
        $lecture = factory(Lecture::class)->create();

        // Act...
        $this->json('get', "lectures/$lecture->id", [
            'with' => [
                'subject'
            ]
        ]);

        // Assert...
        $this->seeSuccessStructure([
            'subject'
        ]);
    }
}