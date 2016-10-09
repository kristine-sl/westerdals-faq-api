<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class RegisterUserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @var User
     */
    protected $user;

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        // Arrange...
        $this->user = factory(User::class)->make()->makeVisible('password');
    }

    /**
     * Test that you can register users.
     *
     * @test
     */
    public function youCanRegisterUsers()
    {
        // Act...
        $this->json('post', 'users', $this->user->toArray());

        // Assert...
        $this->seeStatusCode(Response::HTTP_CREATED);
        $this->seeSuccessStructure([
            'approved',
            'email',
            'id',
            'name'
        ])->seeInDatabase('users', [
            'id' => $this->getSuccessData('id')
        ]);
    }

    /**
     * Test that you cannot register an approved user.
     *
     * @test
     */
    public function itShouldNotSetApproved()
    {
        // Act...
        $this->json('post', 'users', array_merge($this->user->toArray(), [
            'approved' => true
        ]));

        // Assert...
        $this->seeJson([
            'approved' => null
        ]);
    }

    /**
     * Test that email address parameter must be present.
     *
     * @test
     */
    public function emailParameterShouldBeRequired()
    {
        // Act...
        $this->json('post', 'users', array_except($this->user->toArray(), 'email'));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that email address parameter must be valid.
     *
     * @test
     */
    public function emailParameterShouldBeAValidEmail()
    {
        // Act...
        $this->json('post', 'users', array_merge($this->user->toArray(), [
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
        $user = factory(User::class)->create();

        // Act...
        $this->json('post', 'users', array_merge($this->user->toArray(), [
            'email' => $user->email
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
        // Act...
        $this->json('post', 'users', array_merge($this->user->toArray(), [
            'email' => 'foo@gmail'
        ]));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that name parameter must be present.
     *
     * @test
     */
    public function nameParameterShouldBeRequired()
    {
        // Act...
        $this->json('post', 'users', array_except($this->user->toArray(), 'name'));

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
        // Act...
        $this->json('post', 'users', array_merge($this->user->toArray(), [
            'name' => 123
        ]));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    /**
     * Test that password parameter must be present.
     *
     * @test
     */
    public function passwordParameterShouldBeRequired()
    {
        // Act...
        $this->json('post', 'users', array_except($this->user->toArray(), 'password'));

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
        // Act...
        $this->json('post', 'users', array_merge($this->user->toArray(), [
            'password' => 123
        ]));

        // Assert...
        $this->seeError('validation_failed', Response::HTTP_UNPROCESSABLE_ENTITY);
    }
}