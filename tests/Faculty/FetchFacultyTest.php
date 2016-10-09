<?php

use App\Faculty;
use App\Subject;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class FetchFacultyTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that you can fetch faculties.
     *
     * @test
     */
    public function youCanFetchFaculties()
    {
        // Act...
        $this->json('get', 'faculties');

        // Assert...
        $this->seeStatusCode(Response::HTTP_OK);

        foreach (Faculty::KEYS as $key => $id) {
            $this->seeJson([
                'id' => $id,
                'key' => $key
            ]);
        }
    }

    /**
     * Test that you can fetch faculty by its id.
     *
     * @test
     */
    public function youCanFetchFacultyById()
    {
        foreach (Faculty::KEYS as $key => $id) {

            // Act...
            $this->json('get', "faculties/$id");

            // Assert...
            $this->seeStatusCode(Response::HTTP_OK);
            $this->seeJson([
                'id' => $id,
                'key' => $key
            ]);

        }
    }

    /**
     * Test that you can fetch associated resources.
     *
     * @test
     */
    public function youCanFetchRelations()
    {
        // Act...
        $this->json('get', 'faculties', [
            'with' => 'subjects'
        ]);

        // Assert...
        $this->seeSuccessStructure([
            '*' => [
                'subjects'
            ]
        ]);
    }

    /**
     * Test that you can fetch resources associated with a specific faculty.
     *
     * @test
     */
    public function youCanFetchRelationsByFaculty()
    {
        // Act...
        $subject = factory(Subject::class)->create();

        // Act...
        $this->json('get', "faculties/$subject->faculty_id", [
            'with' => 'subjects'
        ]);

        // Assert...
        $this->seeSuccessStructure([
            'subjects'
        ]);
    }
}