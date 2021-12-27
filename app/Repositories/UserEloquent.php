<?php

namespace App\Repositories;

use App\Http\Controllers\Api\BaseController;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class UserEloquent extends BaseController
{
    private $model;

    public function __construct(User $user)
    {
        $this->model = $user;
    }

    public function register(array $data)
    {
        $data['password'] = bcrypt($data['password']);

        $user = User::create($data);

        $token = $user->createToken('API Token')->accessToken;

        return response(['user' => $user, 'token' => $token]);
    }

    public function login()
    {

        $proxy = Request::create('oauth/token', 'POST');
        $response = Route::dispatch($proxy);
        $statusCode = $response->getStatusCode();
        $response = json_decode($response->getContent());
        if ($statusCode != 200)
            return $this->sendError($response->message);

        $response_token = $response;
        $token = $response->access_token;
        \request()->headers->set('Authorization', 'Bearer ' . $token);

        $proxy = Request::create('api/getUser', 'GET');
        $response = Route::dispatch($proxy);

        $statusCode = $response->getStatusCode();
        $user = json_decode($response->getContent())->items;
        return $this->sendResponse('Successfully Login', ['token' => $response_token, 'user' => $user]);
        // dd($response);

        /*  if (!auth()->attempt($data)) {
              return response(['error_message' => 'Incorrect Details.
              Please try again']);
          }
          $token = auth()->user()->createToken('API Token')->accessToken;

          return response(['success'=> '200' ,
              'user' => auth()->user(),
              'token' => $token]);
  */
    }

//    public function forgetPassword(array $data)
//    {
//        // dd($data);
//        $validator = Validator::make($data, [
//            'email' => "required|email"
//        ]);
//        if ($validator->fails()) {
//            return response()->json($validator->errors());
//        }
//        //  $response = Password::sendResetLink($data['email']);
//        $response = Password::sendResetLink(['email' => $data['email']]);
//        $message = $response == Password::RESET_LINK_SENT ? 'Mail send successfully' : 'SOMETHING_WRONG';
//
//        if ($message != Password::RESET_LINK_SENT) {
//            return $this->sendError($message);
//        }
//        return $this->sendResponse($message, '');
//
//
//    }



    function changePassword(array $data)
    {


        $validator = Validator::make($data, [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
            'new_password_confirmation' => 'required',
        ]);
        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }
        $user_id = auth()->user()->id;


        if ((Hash::check(request('old_password'), auth()->user()->password)) == false) {
            $message = "كلمة المرور الحالية غير صحيحة .";
            return $this->sendError($message);
        } else {
            //User::where('id', $user_id)->update(['password' => Hash::make($input['new_password'])]);
            User::where('id', $user_id)->update(['password' => bcrypt($data['new_password'])]);
            $message = "تم تغيير كلمة المرور بنجاح.";
        }
        return $this->sendResponse($message, '');

    }

    public function editUser(array $data)
    {
        $user = Auth::user();
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->phone = $data['phone'];

        if ($data['photo'] != 'undefined') {
            // $image = $request->file('image');
            $filename = $data['photo']->store('public/images');
            $imagename = $data['photo']->hashName();
            $data['photo'] = $imagename;
        }
        $user->photo = $data['photo'];
        $user->update();
        return $this->sendResponse('Success editUser', $user);

    }

    public function logout(array $data)
    {
        $user = Auth::user()->token();
        $user->revoke();
        return response(['message' => 'user has been log out successfully '], 200);
    }
}
