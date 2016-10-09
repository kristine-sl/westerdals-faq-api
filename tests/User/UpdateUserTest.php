<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class UpdateUserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var array
     */
    protected $input;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        // Arrange...
        $this->user = factory(User::class)->create();
        $this->input = factory(User::class)->make()->makeVisible('password')->toArray();
    }

    /**
     * Test that it fails if you're not logged in.
     *
     * @test
     */
    public function youMustBeLoggedIn()
    {
        // Act...
        $this->json('put', "users/{$this->user->id}", $this->input);

        // Assert...
        $this->seeError('unauthenticated', Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Test that you can update users.
     *
     * @test
     */
    public function youCanUpdateUsers()
    {
        // Arrange...
        $this->login();

        // Act...
        $this->json('put', "users/{$this->user->id}", $this->input);

        // Assert...
        $this->seeSuccess($this->user->fresh(), Response::HTTP_OK);
        $this->seeSuccessStructure([
            'approved',
            'email',
            'id',
            'name'
        ])->seeInDatabase('users', [
            'id' => $this->user->id,
            'email' => $this->input['email']
        ]);
    }

    /**
     * Test that you can approve users.
     *
     * @test
     */
    public function youCanApproveUsers()
    {
        // Arrange...
        $this->login();

        // Act...
        $this->json('put', "users/{$this->user->id}", [
            'approved' => true
        ]);

        // Assert...
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJson([
            'approved' => true
        ])->seeInDatabase('users', [
            'approved' => true
        ]);
    }

    /**
     * Test that you can disapprove users.
     *
     * @test
     */
    public function youCanDisapproveUsers()
    {
        // Arrange...
        $this->login();

        // Act...
        $this->json('put', "users/{$this->user->id}", [
            'approved' => false
        ]);

        // Assert...
        $this->seeStatusCode(Response::HTTP_OK);
        $this->seeJson([
            'approved' => false
        ])->seeInDatabase('users', [
            'approved' => false
        ]);
    }

    /**
     * Test that email address parameter must be valid.
     *
     * @test
     */
    public function emailParameterShouldBeAValidEmail()
    {
        // Arrange...
        $this->login();

        // Act...
        $this->json('put', "users/{$this->user->id}", array_merge($this->input, [
            'email' => 'foowesterdals.no'
        ]));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that email address parameter must be unique.
     *
     * @test
     */
    public function emailParameterShouldBeUnique()
    {
        // Arrange...
        $this->login();

        // Act...
        $this->json('put', "users/{$this->user->id}", array_merge($this->input, [
            'email' => $this->user->email
        ]));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that email address parameter must be valid Westerdals email.
     *
     * @test
     */
    public function emailParameterShouldBeAWesterdalsEmail()
    {
        // Arrange...
        $this->login();

        // Act...
        $this->json('put', "users/{$this->user->id}", array_merge($this->input, [
            'email' => 'foo@gmail'
        ]));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that name parameter must be a string value.
     *
     * @test
     */
    public function nameParameterShouldBeAString()
    {
        // Arrange...
        $this->login();

        // Act...
        $this->json('put', "users/{$this->user->id}", array_merge($this->input, [
            'name' => 123
        ]));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that password parameter must be a string value.
     *
     * @test
     */
    public function passwordParameterShouldBeAString()
    {
        // Arrange...
        $this->login();

        // Act...
        $this->json('put', "users/{$this->user->id}", array_merge($this->input, [
            'password' => 123
        ]));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}