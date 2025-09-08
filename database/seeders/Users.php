<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\User;

use App\Enums\Role;

class Users extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            "name" => "Akachukwu",
            "email" => "akalodave@gmail.com",
            "role" => Role::ADMIN->value,
            "password" => "akalo123"
        ]; 

        foreach($users as $user) {
            $userObj = User::where("email", $user['email'])->first();
            if(!$userObj) {
                $userObj = new User;
                $userObj->name = $user['name'];
                $userObj->email = $user['email'];
                $userObj->role = $user['role'];
                $userObj->password = $user['password'];
                $userObj->save();
            }
        }
    }
}
