<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
// use Illuminate\Support\HtmlString;
use Mail;
use  App\Notifications\MyNotification;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('myapptoken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        // // Check email
        // $user = User::where('email', $fields['email'])->first();

        // // Check password
        // if(!$user || !Hash::check($fields['password'], $user->password)) {
        //     return response([
        //         'message' => 'Bad creds'
        //     ], 401);
        // }

        // $token = $user->createToken('myapptoken')->plainTextToken;

        // $response = [
        //     'user' => $user,
        //     'token' => $token
        // ];
        $credentials = ['email' => $request->email, 'password' => $request->password];

        if (Auth::attempt($credentials)) {
//            Auth::user()->tokens()->delete();
            $token = Auth::user()->createToken('myapptoken')->plainTextToken;
            $user = User::where('email', $fields['email'])->first();
            $response = [
                'user' => $user,
                'token' => $token
            ];
            return response($response, 200);
          
        }else{
            $response = [
                'message' => "Email or Password Invalid",

            ];
            return response($response, 200);
        }

     
    }

//     

    public function updatepassword(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'new_password' => 'required|string|confirmed',
        ]);
        $user = User::where('email', $fields['email'])->first();

        if ($user) {
            $user->password = Hash::make($request->new_password);
            $user->save();

            $response = [
                'user' => $user,
            ];
            return response($response, 200);
          
        }else{
            $response = [
                'message' => "Email invalid",
            ];
            return response($response, 200);
        }

     
    }


    public function logout(Request $request) {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Logged out'
        ];
    }
    public function forgetpassword(Request $request) {
        $user = User::where('email', $request->email)->first();
        if( $user ){
            \Notification::route('mail', 'waseem53@gmail.com')->notify(new MyNotification($request));
            $response = [
                'message' => "Forget Password link has been sent to your email address",

            ];
        }else{
            $response = [
                'message' => "Email or Password Invalid",

            ];

        } 
         return response($response, 200);
        

        
    }
}
