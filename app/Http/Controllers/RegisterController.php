<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class RegisterController
{
    const REGISTER_ACTION = 'register';

    public function show(Request $request)
    {
        return view('user.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'surname' => ['required'],
            'name' => ['required'],
            'patronymic' => ['nullable'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:6'],
            'password_confirmation' => ['required'],
        ]);

        $user = new User;
        $user->surname = $data['surname'];
        $user->name = $data['name'];
        $user->patronymic = $data['patronymic'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);

        $user->save();

        return redirect()->route(LoginController::LOGIN_ACTION);

    }
}
