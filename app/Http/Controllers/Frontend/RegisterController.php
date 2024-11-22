<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Validation\Rules;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
//use App\Http\Requests\RegisterRequest;
use App\Models\dbl_users;
use App\Models\UsState;
use App\Models\UsCity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use App\Services\SendGridService;

class RegisterController extends Controller
{
    protected $sendGridService;

    public function __construct(SendGridService $sendGridService)
    {
        $this->sendGridService = $sendGridService;
    }

    public function index(Request $request){
        
        if ($request->isMethod('post')) 
        {
            
            $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email_address' => ['required', 'string', 'email', 'max:255', 'unique:dbl_users'],
                'phone_number' => ['required'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'password_confirmation' => ['required'],
                'state' => ['required'],
                'city' => ['required']
            ]); 

           // If validation passes, redirect to /send_otp                   
           //    $this->setSessionData('phone_number',$request->phone_number); 

           $stateId = $request->state;
           $cityId =  $request->city;

           $state = UsState::find($stateId);
           $city = UsCity::find($cityId);   
           $stateName = $state ? $state->STATE_NAME : null;
           //   $cityName = $city ? $city->CITY : null; 
           // Calculate timezone based on state (dummy implementation, customize as needed)
           $timezone = $this->getTimezoneByState($stateName);
           
            // $this->setSessionData('user_registeration_data', [
            //     'first_name' => $request->first_name,
            //     'last_name' => $request->last_name,
            //     'email_address' => $request->email_address,
            //     'phone_number' => $request->phone_number,
            //     'password' => md5($request->password),
            //     'view_password' => $request->password,
            //     'user_type' => 'patient',            
            //     'state' => $stateId,          
            //     'city' => $cityId,
            //     'timezone' => $timezone                      
            // ]);

            $UserData = ([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email_address' => $request->email_address,
                'phone_number' => $request->phone_number,
                'password' => md5($request->password),
                'view_password' => $request->password,
                'user_type' => 'patient',            
                'state' => $stateId,          
                'city' => $cityId,
                'timezone' => $timezone                      
            ]);
            
            // $user_registeration_data = $this->getSessionData('user_registeration_data');

            $dbl_users = dbl_users::create($UserData);

            // dd($user_registeration_data);    

            $otp['otp'] = random_int(1000, 9999);
            $data_row = dbl_users::where('email_address', $validatedData['email_address'])->first();

            $opr = 'opt_send';
            $subject = 'OTP Send Successfully';

            $res = DB::table('dbl_users')
                    ->where('id', $data_row->id)
                    ->update($otp);

            if (sendSms($data_row,null,null,"Register",$otp['otp'])) {
                SendEmail($this->sendGridService, $data_row->email_address, $subject, 'emails.send-otp-email-template', $data_row, NULL, NULL, $opr, $otp['otp']);
                return redirect('/send-otp'.'/'. base64_encode($data_row->id));
            } else {
                //otp failed , delete user   
                $dbl_users = dbl_users::findOrFail($data_row->id);
                $dbl_users->delete();                         
                // return redirect('/register')->with('warning', 'Unable to Send OTP, Please try after sometime!');
                return redirect()->back()->withInput()->with('warning', 'Unable to send OTP, please try again!');
            }                       
            
        }
        
        
        return view('frontend.register');
    }
    

    public function send_otp(Request $request, $user_id = null)
    {
        $data = [];

        if($user_id && $user_id != null){
            $user_id = base64_decode($user_id);
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
                $check_otp = dbl_users::where('email_address', $data['user_row']['email_address'])->where('otp', $otp)->first();
                if($check_otp != null){
                    //update otp_validated
                    $res = null;
                    try
                    {
                    $res = DB::table("dbl_users")
                    ->where('otp',$otp)
                    ->where('email_address',$data['user_row']['email_address'])
                    ->update(["otp_validated"=>"1","status"=>"Active"]);
                    }
                    catch(\Exception $e)
                    {
                        Log::error('error',["error"=>$e->getMessage()]);
                    }
                    if($res)
                    return redirect('/create-patient'.'/'. base64_encode($data['user_row']['id']));
                    else
                    return redirect()->back()->withErrors(['otp' => 'OTP validation update failed'])->withInput();
                
                } else {
                    return redirect()->back()->withErrors(['otp' => 'The OTP you entered is incorrect.'])->withInput();
                }
            } else {
                return redirect()->back()->withErrors(['otp' => 'The OTP you entered is incorrect.'])->withInput();
            }
        
        }
        
        return view('frontend.send-otp',$data);
        
        // return redirect(url('/send_otp'))->with('success', 'Verify Phone Number.');
       
    }

    public function resend_otp($user_id = null)
    {
        $data = [];

        if($user_id && $user_id != null){
            $user_id = base64_decode($user_id);
            $data['user_row'] = dbl_users::where('id', $user_id)->first();
            
            $otp['otp'] = random_int(1000, 9999);

            $opr = 'opt_send';
            $subject = 'OTP Send Successfully';

            $res = DB::table('dbl_users')
                    ->where('id', $user_id)
                    ->update($otp);

            SendEmail($this->sendGridService, $data['user_row']->email_address, $subject, 'emails.send-otp-email-template', $data['user_row'], NULL, NULL, $opr, $otp['otp']);

            if (sendSms($data['user_row'],null,null,"forgot_password",$otp['otp'])) {
                return redirect()->back()->with('success', 'OTP resend on you registerd mail id!');
            } else {
                //otp failed , delete user   
                $dbl_users = dbl_users::findOrFail($user_id);
                $dbl_users->delete();                         
                return redirect()->back()->with('error', 'Unable to Resend OTP, Please try after sometime!');
            } 

        }
        
        return view('frontend.send-otp',$data);
        
        // return redirect(url('/send_otp'))->with('success', 'Verify Phone Number.');
       
    }
    
    
    public function create_patient(Request $request, $user_id = null)
    {
        if($user_id && $user_id != null){
            $user_id = base64_decode($user_id);
            $data['user_row'] = dbl_users::where('id', $user_id)->first();
        }

       $formattedPhoneNumber = formatPhoneNumber($data['user_row']['phone_number']);

       $user = dbl_users::where('email_address', $data['user_row']['email_address'])->first();
       
       $this->setSessionData('user', $user);
       $user = $this->getSessionData('user');
       //  Auth::login($user);

    //    session(['user' => $user]);

        // Alternatively, you can use the request's session method
    //    $request->session()->put('user', $user);

       // Send the welcome email

        SendEmail($this->sendGridService, $user->email_address, 'Registration successfull', 'emails.register-email-template', $user);

       if ($user['user_type'] === 'patient') {
            return redirect(url('/patient-dashboard'))->with('success', 'Registration successful.');
        } elseif ($user['user_type'] === 'doctor') {
            return redirect('/doctor-dashboard');
            return redirect(url('/doctor-dashboard'))->with('success', 'Registration successful.');
        }
       
    //   return redirect(url('/login'))->with('success', 'Registration successful. Please login.');
       
    }

    public function getStates()
    {
        $states = UsState::all();
        return response()->json($states);
    }

    public function getCities($stateId)
    {
        $cities = UsCity::where('ID_STATE', $stateId)->get();
        return response()->json($cities);
    }
}
