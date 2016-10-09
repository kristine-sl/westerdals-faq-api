<?php

use App\Subject;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class FetchSubjectTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that you can fetch subjects.
     *
     * @test
     */
    public function youCanFetchSubjects()
    {
        // Arrange...
        $subjects = factory(Subject::class, 3)->create();

        // Act...
        $this->json('get', 'subjects');

        // Assert...
        $this->seeSuccess($subjects, Response::HTTP_OK);
        $this->seeSuccessStructure([
            '*' => [
                'id',
                'facultyId',
                'name'
            ]
        ]);
    }

    /**
     * Test that you can fetch subject by its id.
     *
     * @test
     */
    public function youCanFetchSubjectById()
    {
        // Arrange...
        $subject = factory(Subject::class)->create();

        // Act...
        $this->json('get', "subjects/$subject->id");

        // Assert...
        $this->seeSuccess($subject, Response::HTTP_OK);
        $this->seeSuccessStructure([
            'id',
            'facultyId',
            'name'
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
        factory(Subject::class, 3)->create();

        // Act...
        $this->json('get', 'subjects', [
            'with' => [
                'faculty',
                'lectures'
            ]
        ]);

        // Assert...
        $this->seeSuccessStructure([
            '*' => [
                'faculty',
                'lectures'
            ]
        ]);
    }

    /**
     * Test that you can fetch resources associated with a specific subject.
     *
     * @test
     */
    public function youCanFetchRelationsBySubject()
    {
        // Act...
        $subject = factory(Subject::class)->create();

        // Act...
        $this->json('get', "subjects/$subject->id", [
            'with' => [
                'faculty',
                'lectures'
            ]
        ]);

        // Assert...
        $this->seeSuccessStructure([
            'faculty',
            'lectures'
        ]);
    }
}