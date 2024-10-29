<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\dbl_users;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    public function index(){

        $user = $this->getSessionData('user');
        //  Auth::login($user);
        
        if (!empty($user) && $user['user_type'] === 'patient') {
            return redirect('/patient-dashboard');
        } elseif (!empty($user) && $user['user_type'] === 'doctor') 
        {
            return redirect('/doctor-dashboard');
        }   

        return view('frontend.login');
    }

    public function login(Request $request)
    {
        /*
        $request->validate([
            'email_address' => 'required|email',
            'password' => 'required',
        ]);
        */
        if ($request->isMethod('post')) 
        {    
                // dd($request->password);

                $user = dbl_users::where('email_address', $request->email_address)->first();
                
                // dd($user);

                if ($user && ( md5($request->password) == ($user->password) ) ) 
                {
                    if($user->user_type === 'patient'){
                        if($user->status === 'Active'){
                            $this->setSessionData('user', $user);
                            $user = $this->getSessionData('user');                          
                            
                            return redirect('/patient-dashboard');  
                            return redirect('/');
                        } elseif($user->status === 'Deleted'){
                            return back()->withErrors([
                                'email_address' => 'Your account is deleted; please contact support for details.',
                            ]);
                        } else {
                            return back()->withErrors([
                                'email_address' => 'Your account is inactive; please contact support for details.',
                            ]);
                        }
                    } else {
                        return back()->withErrors([
                            'email_address' => 'Please log in using the doctor login screen.',
                        ]);
                    }
                } else {
                    return back()->withErrors([
                        'email_address' => 'The provided credentials do not match our records.',
                    ]);
                }
                
        }

    }

    public function login_doctor(Request $request)
    {
        if ($request->isMethod('post')) 
        {    
                // dd($request->password);

                $user = dbl_users::where('email_address', $request->email_address)->first();
                
                // dd($user);

                if ($user && ( md5($request->password) == ($user->password) ) ) 
                {
                    if($user->user_type === 'doctor'){
                        if($user->status === 'Active'){
                            // $this->setSessionData('user', $user);
                            // $user = $this->getSessionData('user');

                            session(['user' => $user]);

                            // Or using the request instance
                            $request->session()->put('user', $user);
                            //  Auth::login($user);

                            
                            
                            // dd($next24hrs_appointment);
                          
                            // Add a listener for the queries
                            

                            return redirect('/doctor-dashboard');  
                            return redirect('/');
                        } elseif($user->status === 'Deleted'){
                            return back()->withErrors([
                                'email_address' => 'Your account is deleted; please contact support for details.',
                            ]);
                        } else {
                            return back()->withErrors([
                                'email_address' => 'Your account is inactive; please contact support for details.',
                            ]);
                        }
                    } else {
                        return back()->withErrors([
                            'email_address' => 'Please log in using the patient login screen.',
                        ]);
                    }
                
                } else {
                    return back()->withErrors([
                        'email_address' => 'The provided credentials do not match our records.',
                    ]);
                }
                
        }

    }

    public function logout(Request $request)
    {
    //   Auth::logout();
    //   unset($_SESSION["user"]);
    
    session()->forget('user');    
    // Optionally, you can also invalidate the session entirely
    // session()->flush(); // This clears all session data

    // return redirect('/login')->with('success', 'Logged out successfully!');
    
        return redirect('/login');
    }

    public function doctor_logout(Request $request)
    {
        session()->forget('user');    
        // return redirect('/login/doctor')->with('success', 'Logged out successfully!');
        return redirect('/login/doctor');
    }

}
