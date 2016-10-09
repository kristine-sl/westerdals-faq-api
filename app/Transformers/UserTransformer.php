<?php

namespace App\Transformers;

use App\User;
use Flugg\Responder\Transformer;

class UserTransformer extends Transformer
{
    /**
     * Transform the model data into a generic array.
     *
     * @param  User $user
     * @return array
     */
    public function transform(User $user):array
    {
        return [
            'approved' => is_bool($user->approved) ? (bool) $user->approved : null,
            'id' => (int) $user->id,
            'email' => (string) $user->email,
            'name' => (string) $user->name
        ];
    }
}
