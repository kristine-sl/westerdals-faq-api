<?php

namespace App\Transformers;

use App\Question;
use Flugg\Responder\Transformer;

class QuestionTransformer extends Transformer
{
    /**
     * Transform the model data into a generic array.
     *
     * @param  Question $question
     * @return array
     */
    public function transform(Question $question):array
    {
        return [
            'answer' => (string) $question->answer ?: null,
            'answeredAt' => (string) $question->answered_at ?: null,
            'createdAt' => (string) $question->created_at ?: null,
            'deletedAt' => (string) $question->deleted_at ?: null,
            'description' => (string) $question->description,
            'id' => (int) $question->id,
            'lectureId' => (string) $question->lecture_id
            'updatedAt' => (string) $question->updated_at ?: null,
        ];
    }
}
