<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    protected $guard = 'web';
    use AuthenticatesUsers;
    protected $redirectTo = '/home';
    protected $loginPath = '/login';
    public function __construct()
    {
        $this->middleware('web')->except('logout');

    }


    public function login()
    {
        if (Auth::guard('web')->check()) {
            return redirect('/home');
        }
        return view('web.auth.login');
    }

    public function process_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if( $validator->fails()){
            return redirect()
                ->back()
                ->withErrors($validator);
        }

        if (Auth::guard('web')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect('/home');
        }
        return redirect($this->loginPath)->with('error','Your email or password is invalid!');
    }


    public function process_signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6',
        ]);
        if( $validator->fails()){
            return redirect()
                ->back()
                ->withErrors($validator);
        }
        User::create(['username' => trim($request->input('username')),'email' => strtolower($request->input('email')),
            'password' => bcrypt($request->input('password')),
        ]);
        return redirect('/login')->with('success','Your account is created');
    }

    public function passwordResetForm()
    {
        if (Auth::guard('web')->check()) {
            return redirect('/');
        }
        return view('admin/auth/rest-pass-form');
    }

    public function confirmForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin');
        }
        return view('admin/auth/reset-pass-code');
    }

    public function forgot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);
        if ($validator->fails()) {
            return redirect('admin/reset_password')->with('error','Your email or password is invalid!');
        }
        $user = User::where('email', $request->email)->first();
        if (!$user)
            return redirect('admin/reset_password')->with('error','User not found by this Email!');

        $passwordReset = PasswordReset::updateOrCreate(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => mt_rand(1000, 9999),
            ]
        );
        if ($user && $passwordReset)
            $user->notify(
                new PasswordResetRequest($passwordReset->token)
            );

        return redirect('/admin/confirm-form')->with('success','We have e-mailed your password reset link!');
    }

    public function reset(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string|confirmed',
            'token' => 'required|string'
        ]);
        if ($validator->fails()) {
            return redirect('admin/confirm-form')->with('error',$validator->errors());

        }

        $passwordReset = PasswordReset::where([
            ['token', $request->token],
            ['email', $request->email]
        ])->first();
        if (!$passwordReset)
            return redirect('/admin/confirm-form')->with('error','This password reset token is invalid.');
        $user = Admin::where('email', $passwordReset->email)->first();
        if (!$user)
            return redirect('admin/confirm-form')->with('error', "We can't find a user with that e-mail address.");
        $user->password = bcrypt($request->password);
        $user->save();
        $passwordReset->delete();
        $user->notify(new PasswordResetSuccess($passwordReset));
        return redirect('/admin/login')->with('success', "Password Changed successfully");

    }

    public function process_logout()
    {
        Auth::guard('web')->logout();
        return redirect()->route('login');
    }
}
