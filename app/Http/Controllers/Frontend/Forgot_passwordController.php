<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use App\Models\dbl_users;
use App\Services\SendGridService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class Forgot_passwordController extends Controller
{
    // public function index(){
    //     return view('frontend.forgot-password');
    // }

    protected $sendGridService;

    public function __construct(SendGridService $sendGridService)
    {
        $this->sendGridService = $sendGridService;
    }

    public function forgot_password(Request $request){
        
        if ($request->isMethod('post')) 
        {
            $validatedData = $request->validate([
                'email_address' => ['required', 'string', 'email', 'max:255'],
            ]);   
           
            $email = $request->email_address;

            if($email != ''){
                $otp['otp'] = random_int(1000, 9999);
                $opr = 'opt_send';
                $subject = 'OTP Send Successfully';

                $data_row = dbl_users::where('email_address', $email)->first();

                if($data_row != ''){
                    if($data_row->status === 'Active'){
                        $res = DB::table('dbl_users')
                            ->where('id', $data_row->id)
                            ->update($otp);

                        SendEmail($this->sendGridService, $data_row->email_address, $subject, 'emails.send-otp-forgot-password-email-template', $data_row, NULL, NULL, $opr, $otp['otp']);
                        
                        if (sendSms($data_row,null,null,"forgot_password",$otp['otp'])) {
                            return redirect('/forgot-password-otp'.'/'.Crypt::encrypt($data_row->id))->with('success', 'OTP send successfully!');
                        }

                    } elseif($data_row->status === 'Deleted'){
                        return back()->withErrors([
                            'email_address' => 'Your account is deleted; please contact support for details.',
                        ]);
                    } elseif($data_row->status === 'Inactive'){
                        return back()->withErrors([
                            'email_address' => 'Your account is inactive; please contact support for details.',
                        ]);
                    } 
                    else {
                        return back()->withErrors([
                            'email_address' => 'Your account is inactive; please contact support for details.',
                        ]);
                    }
                    } else {
                    return redirect()->back()->with('danger', 'Email address not found');
                }
            }
        }

        return view('frontend.forgot-password');
    }

    public function forgot_password_otp($user_id = null, Request $request)
    {
        $data = [];

        if($user_id != ''){
            $user_id = Crypt::decrypt($user_id);
        }

        if($user_id != '' && $user_id > 0){
            $data['user_row'] = dbl_users::where('id', $user_id)->first();
        }
        
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
                $check_otp = dbl_users::where('id', $user_id)->where('otp', $otp)->first();
                if($check_otp != ''){
                    return redirect('/create-new-password'.'/'.Crypt::encrypt($user_id))->with('success', 'The OTP you entered is correct.');
                } else {
                    return redirect()->back()->withErrors(['otp' => 'The OTP you entered is incorrect.'])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['otp' => 'The OTP you entered is incorrect.'])->withInput();
            }
        }

        return view('frontend.forgot-password-otp', $data);
    }

    public function create_new_password($user_id = null, Request $request)
    {
        $data = [];

        if($user_id != ''){
            $user_id = Crypt::decrypt($user_id);
        }

        $data['user_id'] = $user_id;
        
        if ($request->isMethod('post')) 
        {
            $validatedData = $request->validate([
                'password' => ['required']
            ]);

            $update_data['password'] = md5($request->password);
            $update_data['view_password'] = $request->password;

            $res = DB::table('dbl_users')
                    ->where('id', $user_id)
                    ->update($update_data);
    
            // Concatenate the OTP parts
            if($res){
                return redirect('/login')->with('success', 'Your password updated successfully.');
            } else {
                return redirect()->back()->withErrors(['otp' => 'Password updation failed.'])->withInput();
            }
        }

        return view('frontend.create-new-password', $data);
    }


}
