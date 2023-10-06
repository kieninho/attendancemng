<?php

namespace App\Http\Controllers\Auth;

use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;

use Illuminate\Validation\ValidationException;


use Illuminate\Http\Request;

class CustomLoginController extends AuthenticatedSessionController
{    
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function store(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('password');
        $login = $request->input('login');

        // Kiểm tra xem giá trị nhập vào có phải là email hay không
        $isEmail = filter_var($login, FILTER_VALIDATE_EMAIL);

        // Nếu giá trị nhập vào là email, sử dụng email để xác thực
        if ($isEmail) {
            $credentials['email'] = $login;
        } else {
            $credentials['username'] = $login;
        }

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();

            return redirect()->intended(config('fortify.home'));
        }

        return back()->withErrors([
            'login' => __('auth.failed'),
        ]);
    }
}
