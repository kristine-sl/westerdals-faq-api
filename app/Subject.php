<?php

namespace App;

use App\Transformers\SubjectTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subject extends Model implements Transformable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'faculty_id'
    ];

    /**
     * The path to the transformer class.
     *
     * @return string
     */
    public static function transformer()
    {
        return SubjectTransformer::class;
    }

    /**
     * Fetch associated faculty.
     *
     * @return BelongsTo
     */
    public function faculty()
    {
        return $this->belongsTo(Faculty::class);
    }

    /**
     * Fetch associated lectures.
     *
     * @return HasMany
     */
    public function lectures()
    {
        return $this->hasMany(Lecture::class);
    }
}
