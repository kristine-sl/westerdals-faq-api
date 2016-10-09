<?php

namespace App\Transformers;

use App\Faculty;
use Flugg\Responder\Transformer;

class FacultyTransformer extends Transformer
{
    /**
     * Transform the model data into a generic array.
     *
     * @param  Faculty $faculty
     * @return array
     */
    public function transform(Faculty $faculty):array
    {
        return [
            'id' => (int) $faculty->id,
            'key' => (string) $faculty->key
        ];
    }
}
