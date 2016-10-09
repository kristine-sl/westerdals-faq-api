<?php

namespace App;

use App\Transformers\FacultyTransformer;
use Flugg\Responder\Contracts\Transformable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faculty extends Model implements Transformable
{
    /**
     * @var array
     */
    const KEYS = [
        'arts' => 1,
        'communication' => 2,
        'film_tv_games' => 3,
        'management' => 4,
        'technology' => 5
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'key'
    ];

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The path to the transformer class.
     *
     * @return string
     */
    public static function transformer()
    {
        return FacultyTransformer::class;
    }

    /**
     * Fetch associated subject.
     *
     * @return HasMany
     */
    public function subjects()
    {
        return $this->hasMany(Subject::class);
    }
}
