<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use JWTAuth;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    //

    public function register(Request $request)
    {
        // created a new user with the request data = user_name, email, password. use try catch 
        try {
            $user = new User();
            $user->name = $request->user_name;
            $user->email = $request->email;
            $user->password = $request->password;
            $user->save();

            $success = true;
            $message = "ບັນທຶກຂໍ້ມູນສຳເລັດ!";
            
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
            
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);

    }

    public function login(Request $request)
    {
        try {
            // get the email and password from the request
            $UserLogin = [
                'email' => $request->email,
                'password' => $request->password
            ];

            // check remember me
            if($request->remember_me == 'true') {
                // set ttl
                JWTAuth::factory()->setTTL(60*24*7*4);
            }

            // check if the user is authenticated
            $token = JWTAuth::attempt($UserLogin);
            $user = Auth::user();

            if ($token) {
                $success = true;
                $message = "ການເຂົ້າລະບົບສຳເລັດ!";
            } else {
                $success = false;
                $message = "ການເຂົ້າລະບົບບໍ່ສຳເລັດ! ອີເມວ ຫຼື ລະຫັດຜ່ານບໍ່ຖືກຕ້ອງ!";
            }

        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
            $token = null;
            $user = null;
        }

        $response = [
            'success' => $success,
            'message' => $message,
            'token' => $token,
            'user' => $user
        ];

        return response()->json($response);
       

    }

    public function logout(Request $request)
    {
        try {

            $token = JWTAuth::getToken();
            JWTAuth::invalidate($token);

            $success = true;
            $message = "ອອກຈາກລະບົບສຳເລັດ!";

        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return response()->json($response);
    }
}
