<?php

namespace App\Services;

use App\Models\User;

use App\Enums\Role;

class UserService
{
    public function save($data)
    {
        $user = new User;
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = Role::USER->value;
        $user->password = $data['password'];

        $user->save();

        return $user;
    }
}