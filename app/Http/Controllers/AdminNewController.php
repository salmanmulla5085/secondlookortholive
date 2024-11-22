<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\StaticPageContent;
use App\Models\AdminUser;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
class AdminNewController extends Controller
{
    public function login2()
    {

        // $admin = $this->getSessionData('admin');
        //  Auth::login($user);

        // dd($admin);
        
        // if (!empty($admin)) {
        //     return redirect('/admin_dashboard2');
        // } 
        
        // if (empty($admin)) {
        return view('admin_login2');
        // }
    }

    public function login2_perform(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

            if ($request->isMethod('post')) 
            {    
                // dd($request->password);

                $admin = AdminUser::where('email', $request->email)->first();
                
                // echo md5($request->password);
                // dd($admin);

                if ($admin && ( md5($request->password) == ($admin->password) ) ) 
                {
                        
                            $this->setSessionData('admin', $admin);
                            $admin = $this->getSessionData('admin');

                            // echo'<pre>';print_r($admin['id']);die;

                            // return redirect('/admin_dashboard'); 

                            // $otp['otp'] = random_int(1000, 9999);

                            $otp['otp'] = '1111';
                            
                            $admin = AdminUser::findOrFail($admin['id']);
                            $admin->update($otp);

                            $data_row = AdminUser::where('id', $admin['id'])->first();

                            if(sendSms($data_row,null,null,"admin_otp",$otp['otp'])){
                                return redirect('/get_admin_otp'.'/'.base64_encode($admin['id']))->with('success', 'OTP send on your mobile number.');
                            }
                } 
                else 
                {
                    // return back()->withErrors([
                    //     'email_address' => 'The provided credentials do not match admin records.',
                    // ]);

                    return redirect('/admin_login')->with('error', 'The provided credentials do not match admin records.');
                }
                
            }
            else
            {
                // return back()->withErrors([
                //     'email_address' => 'Invalid Method.',
                // ]);

                return redirect('/admin_login')->with('error', 'Invalid Method');
            }
            // $request->session()->regenerate();

         
    }

    public function get_admin_otp(Request $request, $user_id = null){
        $data = [];
        $data['user_id'] = $user_id;

        if ($request->isMethod('post')) 
        {
            $validatedData = $request->validate([
            'otp1' => ['required', 'digits:1'],
            'otp2' => ['required', 'digits:1'],
            'otp3' => ['required', 'digits:1'],
            'otp4' => ['required', 'digits:1'],
            ]);
    
            // Concatenate the OTP parts
            $otp = $validatedData['otp1'] . $validatedData['otp2'] . $validatedData['otp3'] . $validatedData['otp4'];

            if($otp != ''){
                $check_otp = AdminUser::where('id', base64_decode($user_id))->where('otp', $otp)->first();
                if($check_otp != null){
                    return redirect('/admin_dashboard');
                } else {
                    return redirect()->back()->withErrors(['otp' => 'You entered invalid OTP'])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['otp' => 'The OTP you entered is incorrect.'])->withInput();
            }
        }

        return view('admin_otp', $data);
    }
        

    public function admin_logout(Request $request)
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


