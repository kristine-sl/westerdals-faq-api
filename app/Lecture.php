<?php

namespace App;

use App\Transformers\LectureTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model implements Transformable
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'ended_at',
        'started_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'access_code',
        'ended_at',
        'started_at',
        'subject_id'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The path to the transformer class.
     *
     * @return string
     */
    public static function transformer()
    {
        return LectureTransformer::class;
    }

    /**
     * Fetch associated subject.
     *
     * @return BelongsTo
     */
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
}
