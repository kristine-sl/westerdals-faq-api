<?php

use App\Question;
use App\User;
use Carbon\Carbon;
use Flugg\Responder\Traits\MakesApiRequests;
use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;

abstract class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    use MakesApiRequests;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl = 'http://localhost';

    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__ . '/../bootstrap/app.php';

        $app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

        Carbon::setTestNow(Carbon::now());

        return $app;
    }

    /**
     * Setup the test environment.
     *
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();

        Question::disableSearchSyncing();
    }

    /**
     * Mock the authenticator.
     *
     * @return \Mockery\MockInterface
     */
    protected function mockAuth()
    {
        $auth = Mockery::mock(Factory::class);
        $this->app->instance(Factory::class, $auth);

        return $auth;
    }

    /**
     * Mock the JWT guard.
     *
     * @return \Mockery\MockInterface
     */
    protected function mockGuard()
    {
        $guard = Mockery::mock(Guard::class);
        $this->app->instance(Guard::class, $guard);

        return $guard;
    }

    /**
     * Log the user in by mocking the authenticator.
     *
     * @return void
     */
    protected function login()
    {
        $user = factory(User::class)->create();
        $auth = $this->mockAuth();
        $auth->shouldReceive('authenticate')->once()->andReturn($user);

        $this->actingAs($user);
    }
}
