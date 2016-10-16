<?php

namespace App\Http\Controllers;

use App\Events\QuestionPosted;
use App\Http\Requests\QuestionRequest;
use App\Question;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class QuestionsController extends Controller
{
    /**
     * Fetch a list of questions.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $questions = request()->has('search') ? Question::search(request()->search)->get() : Question::all();
        $questions->load(request()->with ?: []);

        return responder()->success($questions, Response::HTTP_OK);
    }

    /**
     * Fetch a question.
     *
     * @param  Question $question
     * @return JsonResponse
     */
    public function show(Question $question)
    {
        $question->load(request()->with ?: []);

        return responder()->success($question, Response::HTTP_OK);
    }

    /**
     * Ask a question.
     *
     * @param  QuestionRequest $request
     * @return JsonResponse
     */
    public function store(QuestionRequest $request)
    {
        $question = Question::create(array_merge($request->all(), [
            'answer' => null,
            'answered_at' => null
        ]));

        event(QuestionPosted::class, $question);

        $question->load($request->with ?: []);

        return responder()->success($question, Response::HTTP_CREATED);
    }

    /**
     * Answer a question.
     *
     * @param  Question        $question
     * @param  QuestionRequest $request
     * @return JsonResponse
     */
    public function update(QuestionRequest $request, Question $question)
    {
        $question->update([
            'answer' => $request->answer,
            'answered_at' => Carbon::now()
        ]);

        $question->load($request->with ?: []);

        return responder()->success($question, Response::HTTP_OK);
    }

    /**
     * Ask a question.
     *
     * @param  Question $question
     * @return JsonResponse
     */
    public function destroy(Question $question)
    {
        $question->delete();

        return responder()->success(Response::HTTP_OK);
    }
}
