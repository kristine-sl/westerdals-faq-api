<?php

namespace App\Transformers;

use App\Lecture;
use Flugg\Responder\Transformer;

class LectureTransformer extends Transformer
{
    /**
     * Transform the model data into a generic array.
     *
     * @param  Lecture $lecture
     * @return array
     */
    public function transform(Lecture $lecture):array
    {
        return [
            'accessCode' => (string) $lecture->access_code,
            'endedAt' => (string) $lecture->ended_at ?: null,
            'id' => (int) $lecture->id,
            'startedAt' => (string) $lecture->started_at,
            'subjectId' => (int) $lecture->subject_id
        ];
    }
}
