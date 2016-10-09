<?php

namespace App\Transformers;

use App\Subject;
use Flugg\Responder\Transformer;

class SubjectTransformer extends Transformer
{
    /**
     * Transform the model data into a generic array.
     *
     * @param  Subject $subject
     * @return array
     */
    public function transform(Subject $subject):array
    {
        return [
            'facultyId' => (int) $subject->faculty_id,
            'id' => (int) $subject->id,
            'name' => (string) $subject->name
        ];
    }
}
