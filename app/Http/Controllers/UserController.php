<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function loginPage()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }

        return view('login');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'age' => 'required|numeric',
            'role' => ['required', 'in:Admin,Author'],
        ]);

        $user = User::create($data);

        if ($user) {
            return redirect()->route('login')->with('success', 'Registration Successful!...');
        }

        return back()->with('error', 'Registration Failed! Please try again!...');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            return redirect()->route('dashboard')->with('success', 'Login Successful!...');
        }

        return back()->with('error', 'Login Failed! Please Check Your Credentials!...');
    }

    public function dashboardPage(){
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please Login First!...');
        }

        $userPostCount = Post::where('user_id', Auth::id())->count();
        $PostCount = Post::count();

        return view('dashboard', compact('userPostCount', 'PostCount'));
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('success', 'You Have Been Logged Out!...');
    }

}
