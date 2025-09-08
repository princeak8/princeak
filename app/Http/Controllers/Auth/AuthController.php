<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Requests\Auth\Login;
use App\Http\Requests\Auth\SignUp;

use App\Services\UserService;

use App\Utilities;

class AuthController extends Controller
{

    public function login(Login $request)
    {
        try{
            try {
                $request->authenticate();
                
                $request->session()->regenerate();
                return back();
            } catch (\Exception $e) {
                return Utilities::error402("wrong email/password");
            }
        }catch(\Exception $e) {
            return Utilities::error($e, 'An error occurred while trying to login, Please try again later or contact support');
        }
    }

    public function signUp(SignUp $request)
    {
        try{
            $data = $request->validated();
            $userService = new UserService;

            $user = $userService->save($data);

            Auth::attempt(["email" => $user->email, "password" => $data['password']]);

            $request->session()->regenerate();
            return back();
        }catch(\Exception $e) {
            return Utilities::error($e, 'An error occurred while trying to login, Please try again later or contact support');
        }
    }
}
