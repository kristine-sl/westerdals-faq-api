<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubjectRequest;
use App\Subject;
use Illuminate\Http\Response;

class SubjectsController extends Controller
{
    /**
     * Display a listing of the subjects.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $subjects = Subject::all();
        $subjects->load(request()->with ?: []);

        return responder()->success($subjects, Response::HTTP_OK);
    }

    /**
     * Display a subject by its id.
     *
     * @param  Subject $subject
     * @return JsonResponse
     */
    public function show(Subject $subject)
    {
        $subject->load(request()->with ?: []);

        return responder()->success($subject, Response::HTTP_OK);
    }

    /**
     * Create a new subject.
     *
     * @param  SubjectRequest $request
     * @return JsonResponse
     */
    public function store(SubjectRequest $request)
    {
        $subject = Subject::create($request->all());
        $subject->load($request->with ?: []);

        return responder()->success($subject, Response::HTTP_CREATED);
    }
}
