<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Models\AdminUser;

class AdminLoginController extends Controller
{
    /**
     * Display login page.
     *
     * @return Renderable
     */
    public function show()
    {

        return redirect('/admin_login');

        // $admin = $this->getSessionData('admin');
        //  Auth::login($user);

        // dd($admin);
        
        // if (!empty($admin)) {
        //     return redirect('/admin_dashboard');
        // } 
        
        // if (empty($admin)) {
        // return view('auth.admin_login');
        // }
    }

    public function admin_login2()
    {

        $admin = $this->getSessionData('admin');
        //  Auth::login($user);

        // dd($admin);
        
        if (!empty($admin)) {
            return redirect('/admin_dashboard2');
        } 
        
        if (empty($admin)) {
        return view('admin_login2');
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

            if ($request->isMethod('post')) 
            {    
                // dd($request->password);

                $admin = AdminUser::where('email', $request->email)->first();
                
                // dd($user);

                if ($admin && ( md5($request->password) == ($admin->password) ) ) 
                {
                        
                            $this->setSessionData('admin', $admin);
                            $admin = $this->getSessionData('admin');

                            return redirect('/admin_dashboard');  
                            
                } 
                else 
                {
                    return back()->withErrors([
                        'email_address' => 'The provided credentials do not match admin records.',
                    ]);
                }
                
            }
            else
            {
                return back()->withErrors([
                    'email_address' => 'Invalid Method.',
                ]);
            }
            // $request->session()->regenerate();

         
    }
    

    public function logout(Request $request)
    {
        // Auth::logout();

        // $request->session()->invalidate();
        // $request->session()->regenerateToken();

        session()->forget('admin');    
        
        // Optionally, you can also invalidate the session entirely
        // session()->flush(); // This clears all session data
    
        return redirect('/admin_login')->with('success', 'Logged out successfully!');
    
    }
}
