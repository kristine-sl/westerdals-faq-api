<?php

namespace App;

use App\Transformers\QuestionTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;

class Question extends Model implements Transformable
{
    use Searchable;
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'answered_at',
        'deleted_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'answer',
        'answered_at',
        'description',
        'lecture_id'
    ];

    /**
     * Fetch associated lecture.
     *
     * @return BelongsTo
     */
    public function lecture()
    {
        return $this->belongsTo(Lecture::class);
    }

    /**
     * The path to the transformer class.
     *
     * @return string
     */
    public static function transformer()
    {
        return QuestionTransformer::class;
    }
}
