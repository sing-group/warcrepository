<?php

namespace App\Transformers;

use League\Fractal\TransformerAbstract;
use App\User;


class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        return [
            'id' => $user->uuid,
            'name' => $user->name,
            'email' => $user->email,
            'created_at' => date('d-m-Y', strtotime($user->created_at))
        ];
    }
}