<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Task\TaskController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    const AUTHENTICATE_ACTION = 'authenticate';
    const LOGIN_ACTION = 'login';
    const LOGOUT_ACTION = 'logout';

    public function show(Request $request){
       return view('user.login');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->route(TaskController::ACTION_LIST);
        }

        return back()->withErrors([
            'email' => 'Пользователь с такими данными не найден.',
        ]);
    }

    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route(self::LOGIN_ACTION);
    }
}
