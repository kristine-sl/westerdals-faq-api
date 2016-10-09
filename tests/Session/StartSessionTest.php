<?php

use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\Response;

class StartSessionTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test that you can log users in.
     *
     * @test
     */
    public function youCanLogUsersIn()
    {
        // Arrange...
        $guard = $this->mockGuard();
        $user = factory(User::class)->create([
            'password' => 'foo'
        ]);

        $guard->shouldReceive('attempt')->once()->andReturn('123');
        $guard->shouldReceive('user')->once()->andReturn($user);

        // Act...
        $this->json('post', 'sessions', array_merge($user->toArray(), [
            'password' => 'foo'
        ]));

        // Assert...
        $this->seeStatusCode(Response::HTTP_CREATED);
        $this->seeSuccessStructure([
            'approved',
            'email',
            'id',
            'name'
        ])->seeJson([
            'token' => '123'
        ]);
    }
}