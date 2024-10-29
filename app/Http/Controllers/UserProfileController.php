<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserProfileController extends Controller
{
    public function show()
    {
        $admin = $this->getSessionData('admin');
        return view('pages.user-profile',compact('admin'));
    }

    public function update(Request $request)
    {
        $attributes = $request->validate([
            'username' => ['required','max:255', 'min:2'],
            'firstname' => ['max:100'],
            'lastname' => ['max:100'],
            'email' => ['required', 'email', 'max:255'],
            'phone_number' => ['required'],
            'address' => ['max:100'],
            'city' => ['max:100'],
            'country' => ['max:100'],
            'postal' => ['max:100'],
            'about' => ['max:255']
        ]);
        $adminData = $this->getSessionData('admin');
        $admin = AdminUser::findOrFail($adminData->id);
        // Update the admin user with the validated data
        $admin->update($attributes);
        // dd($admin);
        session()->put('admin', $admin);


       /* auth()->user()->update([
            'username' => $request->get('username'),
            'firstname' => $request->get('firstname'),
            'lastname' => $request->get('lastname'),
            'email' => $request->get('email') ,
            'address' => $request->get('address'),
            'city' => $request->get('city'),
            'country' => $request->get('country'),
            'postal' => $request->get('postal'),
            'about' => $request->get('about')
        ]);
        */

        return back()->with('succes', 'Profile succesfully updated');
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required',
            'cnf_new_password' => 'required|same:new_password',
        ]);
    
        $adminData = $this->getSessionData('admin');
        $admin = AdminUser::findOrFail($adminData->id);
 
       
        if (md5($request->old_password) !== $admin->password) {
            return redirect()->back()->withErrors(['old_password' => 'The old password is incorrect.']);
        }
    
     
        // if (md5($request->new_password) === $admin->password) {
        //     return redirect()->back()->withErrors(['new_password' => 'This is your existing password.']);
        // }      
        // dd(md5($request->new_password));
        $admin->password = md5($request->new_password);
        $admin->save();
    
       
        session()->flash('status', 'Password changed, login with your new password.');
        session()->flush(); 
    
        return redirect()->route('login2');
        exit();
    }
    



}
