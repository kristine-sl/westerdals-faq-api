<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UsersController extends Controller
{
    /**
     * Register user.
     *
     * @param  UserRequest $request
     * @return JsonResponse
     */
    public function store(UserRequest $request)
    {
        $user = User::create($request->except('approved'));

        return responder()->success($user, Response::HTTP_CREATED);
    }

    /**
     * Update the user.
     *
     * @param  UserRequest $request
     * @param  User        $user
     * @return JsonResponse
     */
    public function update(UserRequest $request, User $user)
    {
        $user->update($request->all());

        return responder()->success($user, Response::HTTP_OK);
    }
}