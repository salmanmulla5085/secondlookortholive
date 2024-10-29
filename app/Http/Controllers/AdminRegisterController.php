<?php

namespace App\Http\Controllers;

// use App\Http\Requests\RegisterRequest;
use App\Models\AdminUser;

class AdminRegisterController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store()
    {
        $attributes = request()->validate([
            'username' => 'required|max:255|min:2',
            'email' => 'required|email|max:255|unique:tbl_admins,email',
            'password' => 'required|min:5|max:255',
            'terms' => 'required'
        ]);
        
        $user = AdminUser::create($attributes);
        
        // auth()->login($user);

        // return redirect('/admin_dashboard');
    }
}
