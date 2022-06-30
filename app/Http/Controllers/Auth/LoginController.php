<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Request\Api;
use App\Http\Controllers\Request\ApiData;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->api = new Api();
        $this->apiData = new ApiData();
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|exists:users,email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            flash('Sorry! Your Username or Password is required.')->error();

            return back()->withInput()->withErrors($validator);
        } else {

            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $userLogin = Auth::user();

                flash('Yey! Welcome ' . $userLogin->name . '.')->success();

                return redirect()->route('admin.dashboard.index');

            } else {
                flash('Sorry! Your Username or Password is wrong.')->error();

                return back()->withInput()->withErrors($validator);
            }
        }
    }

    public function logout(Request $request)
    {
        $userLogin = Auth::user();

        if ($userLogin->getRoleNames()->first() == "Administrator") {
            $redirect = 'login';
        } elseif ($userLogin->getRoleNames()->first() == "User") {
            $redirect = 'app.login';
        }

        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect()->route($redirect);
    }

    protected function guard()
    {
        return Auth::guard('web');
    }

}
