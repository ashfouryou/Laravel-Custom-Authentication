<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;


class AuthController extends Controller
{
    function showLoginForm(){
        return view('auth/login');
    }

    function showRegisterForm(){
        return view('auth/register');
    }

    function register(Request $request){
    
       // Validation rules
       $rules = [
           'email' => 'required|email|unique:users,email',
           'name' => 'required|string|max:255',
           'password' => 'required|string|regex:/^\w+$/',
           'mobile' => 'required|numeric|unique:users,mobile',
       ];
   
       // Custom validation messages
       $messages = [
           'password.regex' => 'The password must be alphanumeric.',
       ];
       $data = $request->all();

   
       // Validate the request
       $validator = Validator::make($data, $rules, $messages);
    
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $data['password'] = bcrypt($data['password']);
    
        $user = User::create($data);
    
        return redirect()->route('app');
    }

    function login(Request $request){
        $data = request()->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if(auth()->attempt($data)){
            return redirect()->route('dashboard');
        }

        return back();
    }


    function logout(){
        Auth::logout();
        return redirect()->route('dashboard');
    }
}
