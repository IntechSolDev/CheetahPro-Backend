<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Notifications\EmailVerificationRequest;
use App\Notifications\PasswordResetRequest;
use App\Notifications\PasswordResetSuccess;
use App\Models\PasswordReset;
use App\Models\Admin;
use Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $guard = 'admin';
    use AuthenticatesUsers;
    protected $redirectTo = '/admin';
    protected $loginPath = '/admin/login';
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('guest:admin')->except('logout');
    }


    public function show_login_form()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin');
        }
        return view('admin/auth/login');
    }

    public function process_login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect($this->loginPath)->with('error',$validator->errors());
        }
        $admin = Admin::where('email', $request->email)->first();

        if (!$admin) {
            return redirect($this->loginPath)->with('error','Your email or password is invalid!');
        }
        if (Auth::guard('admin')->attempt(['email' => $admin->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect('/admin');
        }
        return redirect($this->loginPath)->with('error','Your email or password is invalid!');
    }

    public function show_signup_form()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin');
        }
        return view('admin/auth/register');
    }

    public function process_signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:admins',
            'password' => 'required|min:6',
        ]);
        if ($validator->fails()) {
            return redirect('admin/register')->with('error',$validator->errors());
        }
        Admin::create([
            'name' => trim($request->input('name')),
            'email' => strtolower($request->input('email')),
            'password' => bcrypt($request->input('password')),
        ]);
        return redirect('/admin')->with('success','Your account is created');
    }

    public function passwordResetForm()
    {
        if (Auth::guard('admin')->check()) {
            return redirect('/admin');
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
        $user = Admin::where('email', $request->email)->first();
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

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('admin.storelogin');
    }
}
