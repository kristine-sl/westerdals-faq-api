<?php

namespace App\Http\Controllers;

use App\Http\Requests\SessionRequest;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Http\Response;

class SessionsController extends Controller
{
    /**
     * The authenticator.
     *
     * @var \Illuminate\Contracts\Auth\Guard
     */
    protected $auth;

    /**
     * SessionsController constructor.
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Log the user in.
     *
     * @param  SessionRequest $request
     * @return Response
     */
    public function store(SessionRequest $request)
    {
        if (! $token = $this->auth->attempt($request->only('email', 'password'))){
            return responder()->error('invalid_credentials', Response::HTTP_BAD_REQUEST);
        }

        return responder()->success($this->auth->user(), Response::HTTP_CREATED, [
            'token' => $token
        ]);
    }

    /**
     * Log the user out.
     *
     * @return Response
     */
    public function destroy()
    {
        $this->auth->logout();

        return responder()->success(Response::HTTP_OK);
    }
}
