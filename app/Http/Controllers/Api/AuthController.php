<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Models\User;
use App\Repositories\UserEloquent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
//use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;
class AuthController extends BaseController
{
    public function __construct(UserEloquent $userEloquent)
    {
        $this->user= $userEloquent;
    }
    public function register(RegisterRequest $request)
    {
        return $this->user->register($request->all());
    }
    public function login()
    {
        return $this->user->login();
    }

    public function logout(Request $request)
    {
        return $this->user->logout($request->all());
    }

    public function getUser($user_id = null)
    {
        if (isset($user_id)) {
            $user = User::find($user_id);
        } else
            $user = auth()->user();

        $data=[
            'status' => true,
            'statusCode' => 200,
            'message' => 'Success',
            'items' => $user
        ];
        return response()->json($data);
    }


//    public function forgetPassword(Request $request){
//        return $this->user->forgetPassword($request->all());
//    }


    public function changePassword(Request $request){
        return $this->user->changePassword($request->all());
    }
    public function editUser(Request $request){
        return $this->user->editUser($request->all());
    }

}
