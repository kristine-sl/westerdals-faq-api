<?php

namespace App\Http\Controllers;

use App\Http\Requests\LectureRequest;
use App\Lecture;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LecturesController extends Controller
{
    /**
     * Display a listing of the lectures.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $lectures = Lecture::all();
        $lectures->load(request()->with ?: []);

        return responder()->success($lectures, Response::HTTP_OK);
    }

    /**
     * Display a lecture by its id.
     *
     * @param  Lecture $lecture
     * @return JsonResponse
     */
    public function show(Lecture $lecture)
    {
        $lecture->load(request()->with ?: []);

        return responder()->success($lecture, Response::HTTP_OK);
    }

    /**
     * Create a new lecture.
     *
     * @param  LectureRequest $request
     * @return JsonResponse
     */
    public function store(LectureRequest $request)
    {
        $lecture = Lecture::create(array_merge($request->all(), [
            'access_code' => str_random(5),
            'ended_at' => null,
            'started_at' => Carbon::now()
        ]))->load($request->with ?: []);

        return responder()->success($lecture, Response::HTTP_CREATED);
    }

    /**
     * Create a new lecture.
     *
     * @param  LectureRequest $request
     * @param  Lecture        $lecture
     * @return JsonResponse
     */
    public function destroy(Request $request, Lecture $lecture)
    {
        $lecture->update([
            'ended_at' => Carbon::now()
        ]);
        $lecture->load($request->with ?: []);

        return responder()->success($lecture, Response::HTTP_OK);
    }
}
