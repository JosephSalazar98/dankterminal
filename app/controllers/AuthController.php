<?php

namespace App\Controllers;

class AuthController extends Controller
{
    public function login()
    {
        $success = auth()->login([
            'email' => request()->get('email'),
            'password' => request()->get('password')
        ]);

        if ($success) {
            response()->redirect('/dashboard');
        } else {
            echo "Incorrect: " . json_encode(auth()->errors());
        }
    }

    public function logout()
    {
        auth()->logout('/login');
    }

    public function showLoginForm()
    {
        return render('login');
    }
}
