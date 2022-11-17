<?php

namespace Irpcpro\TableSoft\Testing\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Irpcpro\TableSoft\Testing\Http\Controllers\TableSoftControllers as Controller;

class LoginController extends Controller
{

    public function login()
    {
        return view('tableSoft::login');
    }

    public function loginPost(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|',
        ]);
        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withErrors($validator)
                ->withInput();
        }

        if(!Auth::guard('web')->attempt($request->except('_token'), true)){
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['login-error' => 'username or password is incorrect']);
        }

        Auth::login(User::where('email', $request->email)->first());
        return redirect()->route('tableview.home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('tableview.login');
    }

}
