<?php
namespace App\Http\Controllers\API;
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
use Laravel\Sanctum\PersonalAccessToken;
use App\Rules\CheckPatientEmail;
use App\Rules\CheckDoctorEmail;
use App\Services\SendGridService;
use Illuminate\Validation\ValidationException;

class SignupController extends Controller
{
    protected $sendGridService;

    public function __construct(SendGridService $sendGridService)
    {
        $this->sendGridService = $sendGridService;
    }
    
    public function save_user(Request $request)
    {
            
         $validatedData = $request->validate([
                'first_name' => ['required', 'string', 'max:255'],
                'last_name' => ['required', 'string', 'max:255'],
                'email_address' => ['required', 'string', 'email', 'max:255', 'unique:dbl_users'],
                'phone_number' => ['required', 'regex:/^[0-9]{10}$/'],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
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
           
           $validatedData["state"] = $stateId;
           $validatedData["city"] = $cityId;
           $validatedData["timezone"] = $timezone;          
           $validatedData["status"] = 'Inactive'; 
           $validatedData["password"] = md5($request->password);
           
           $generated_otp = random_int(1000, 9999);        

           $validatedData["otp"] = $generated_otp;          
                    
                    // Create a new user and save to the database
            $user = new dbl_users(); // Assuming your model is named DblUser
            $user->fill($validatedData);
            $user->save();

            $otp['otp'] = $generated_otp;

            $data_row = dbl_users::where('email_address', $validatedData['email_address'])->first();

            $opr = 'opt_send';
            $subject = 'OTP Send Successfully';

            $res = DB::table('dbl_users')
                    ->where('id', $data_row->id)
                    ->update($otp);

            if (sendSms($data_row,null,null,"Register",$otp['otp'])) {
                SendEmail($this->sendGridService, $data_row->email_address, $subject, 'emails.send-otp-email-template', $data_row, NULL, NULL, $opr, $otp['otp']);
            }

            // Return a success response
            return response()->json([
                'message' => 'OTP sent on given phone number',
                'otp' => $generated_otp,
                'user_id' => $user->id,
                'status' => 'success'
            ], 201); // 201 status code for created resource
        
    }

    public function validate_otp(Request $request)
    {
            
         $validatedData = $request->validate([
                'otp' => ['required'],
                'user_id' => ['required'],                
            ]);           
           
            $user = dbl_users::where('id', $request->user_id)->first();            

            if($user->otp == $request->otp)
            {
                $user->status = 'Active';

                $user->save();

                // Return a success response
                return response()->json([
                    'message' => 'OTP validated successfully',
                    'generated_otp' => $user->otp,
                    'posted_otp' => $request->otp,
                    'user_id' => $user->id,
                    'status' => 'success'
                ], 201); // 201 status code for created resource
            
            }
            else
            {
               
                return response()->json(['error' => 'invalid OTP'], 400);              
                
            }

        
    }

    public function login(Request $request)
    {

        try {
                $validatedData = $request->validate([
                    'email_address' => ['required', 'string', 'email', new CheckPatientEmail],
                    'password' => ['required', 'string'],
                ]);

        
                // Retrieve the user based on the provided email
                $user = null;
                $user = dbl_users::where(['email_address'=>$request->email_address,'user_type'=>'patient'])->first();

                
                // Check if the user exists and the md5 hashed password matches
                if ($user && $this->checkMd5Password($request->password, $user->password)) 
                {
                        // Generate an API token (you might need to set up Laravel Passport or Sanctum for this)
                        
                        PersonalAccessToken::where('tokenable_id', $user->id)->delete();
                        // $token = $user->createToken('SecondLook_API_Tokens')->plainTextToken;
                        $token = $user->createToken('SecondLook_API_Tokens', ['*'], now()->addDays(7))->plainTextToken;

                        // Return a success response with the token
                        return response()->json([
                            'message' => 'Login successful',
                            'user' => $user,
                            'token' => $token,
                            'status' => 'success'
                        ], 200);
                }
                else
                {

                        // Return an error response if authentication fails
                        return response()->json(['error' => 'Invalid credentials',
                        'user'=>$user,
                        'request password'=>$request->password,
                        
                        ], 401);
                }

        
        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }
    
    public function login_doctor(Request $request)
    {
            try {

                    $validatedData = $request->validate([
                    'email_address' => ['required', 'string', 'email', new CheckDoctorEmail],
                    'password' => ['required', 'string'],
                    ]);


                    // Retrieve the user based on the provided email
                    $user = null;
                    $user = dbl_users::where(['email_address'=>$request->email_address,'user_type'=>'doctor'])->first();


                    // Check if the user exists and the md5 hashed password matches
                    if ($user && $this->checkMd5Password($request->password, $user->password)) 
                    {
                        // Generate an API token (you might need to set up Laravel Passport or Sanctum for this)
                        
                        PersonalAccessToken::where('tokenable_id', $user->id)->delete();
                        // $token = $user->createToken('SecondLook_API_Tokens')->plainTextToken;
                        $token = $user->createToken('SecondLook_API_Tokens', ['*'], now()->addDays(7))->plainTextToken;

                        // Return a success response with the token
                        return response()->json([
                            'message' => 'Login successful',
                            'user' => $user,
                            'token' => $token,
                            'status' => 'success'
                        ], 200);
                    }
                    else
                    {

                        // Return an error response if authentication fails
                        return response()->json(['error' => 'Invalid credentials',
                        'user'=>$user,
                        'request password'=>$request->password,
                        
                        ], 401);
                    }


            } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
            'status' => 'error',
            'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
            
            }

        
    }

    /**
     * Handle the logout request.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        
      
        //if token is sent as post variable then use followogin code
            /*
            $request->validate([
                'token' => 'required',
            ]);
            $token = $request->token;
            $user = dbl_users::whereHas('tokens', function($query) use ($token) {
                $query->where('id', $token);
            })->first();

                    if (!$user) {
                        return response()->json([
                            'message' => 'Invalid token',
                        ], 401);
                    }

                    // Revoke the token
                    $user->tokens()->where('id', $token)->delete();

            */
        // end


        // if token is sent in Authorization header
        $token = $request->bearerToken();
        // Check if the token exists and is valid
        if (!$token) {
            return response()->json([
                'message' => 'Token not provided',
            ], 401);
        }

        // Find the token record
        // $tokenRecord = PersonalAccessToken::where('token', hash('sha256', $token))->first();
        // $tokenRecord = PersonalAccessToken::where('token', $token)->first();

        // list($userId, $plainTextToken) = explode('|', $token, 2);

        // $hashedToken = hash('sha256', $plainTextToken);

        // Find the token record in the database
        $tokenRecord = PersonalAccessToken::findToken($token);

                                     

        if (!$tokenRecord) {
            return response()->json([
                'message' => 'Invalid token',
                'token' => $token,
                
            ], 401);
        }

        // Find the user associated with the token
        $user = $tokenRecord->tokenable;

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // Revoke the token
        $tokenRecord->delete();

        // Return a success response
        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }

    public function logout_old(Request $request)
    {
        
      
        //if token is sent as post variable then use followogin code
            
            // $request->validate([
            //     'token' => 'required',
            // ]);

            $token = $request->header('token');
            
            if (empty($token)) {
                return response()->json([
                    'error' => 'The token is required.'
                ], 400);
            }

            $user = dbl_users::whereHas('tokens', function($query) use ($token) {
                $query->where('id', $token);
            })->first();

                    if (!$user) {
                        return response()->json([
                            'message' => 'Invalid token',
                        ], 401);
                    }

                    // Revoke the token
                    $user->tokens()->where('id', $token)->delete();

                    return response()->json([
                        'message' => 'Logged out successfully',
                    ], 200);

            
        // end


        // if token is sent in Authorization header
        $token = $request->bearerToken();
        // Check if the token exists and is valid
        if (!$token) {
            return response()->json([
                'message' => 'Token not provided',
            ], 401);
        }

        // Find the token record
        $tokenRecord = PersonalAccessToken::where('token', hash('sha256', $token))->first();

        if (!$tokenRecord) {
            return response()->json([
                'message' => 'Invalid token',
            ], 401);
        }

        // Find the user associated with the token
        $user = $tokenRecord->tokenable;

        if (!$user) {
            return response()->json([
                'message' => 'User not found',
            ], 404);
        }

        // Revoke the token
        $tokenRecord->delete();

        // Return a success response
        return response()->json([
            'message' => 'Logged out successfully',
        ], 200);
    }
    
        /**
     * Check if the provided password matches the md5 hashed password.
     *
     * @param  string  $password
     * @param  string  $hashedPassword
     * @return bool
     */
    private function checkMd5Password($password, $hashedPassword)
    {
    return md5($password) === $hashedPassword;
    }

   
}
