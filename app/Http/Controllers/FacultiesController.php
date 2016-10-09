<?php

namespace App\Http\Controllers;

use App\Faculty;
use Illuminate\Http\Response;

class FacultiesController extends Controller
{
    /**
     * Display a listing of the faculties.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $faculties = Faculty::all();
        $faculties->load(request()->with ?: []);

        return responder()->success($faculties, Response::HTTP_OK);
    }

    /**
     * Display a faculty by its id.
     *
     * @param  Faculty $faculty
     * @return JsonResponse
     */
    public function show(Faculty $faculty)
    {
        $faculty->load(request()->with ?: []);

        return responder()->success($faculty, Response::HTTP_OK);
    }
}
