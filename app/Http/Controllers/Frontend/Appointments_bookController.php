<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller; 
//Schedule model is using tbl_appointments_booked
use App\Models\Schedule;
use App\Models\Card;
use App\Models\UsState;
use App\Models\UsCity;
use App\Models\dbl_users;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Services\SendGridService;
use Illuminate\Console\Scheduling\Schedule as SchedulingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Appointments_bookController extends Controller
{
    
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */

    protected $sendGridService;

    public function __construct(SendGridService $sendGridService)
    {
        $this->sendGridService = $sendGridService;
    }
     
    public function show_booking()
    {
        // Fetch the appointment details from the Schedule model
        $data["appointment_id"] = $appointment_id = $this->getSessionData('appointment_id');
        $patient_rescheduled_appointment_id = $this->getSessionData('patient_rescheduled_appointment_id');
        
        $data['appointment'] = Schedule::findOrFail($appointment_id);

        $cat_type = $this->getSessionData('cat_type');

        if($cat_type && $cat_type != null){
            $data['cat_type'] = $cat_type;
        } else {
            $data['cat_type'] = '';
        }
            
            // Fetch the payment details if the appointment has a payment_id
            if (!empty($data['appointment']->payment_id)) {
                $data['payment'] = DB::table('tbl_payments')
                    ->where('id', $data['appointment']->payment_id)
                    ->select('txn_id', 'txn_status', 'receipt_url')
                    ->first();
            }

        $appointmentType = $this->getSessionData('appointmentType');
        
        if ($appointmentType == 'Report Review')
            $data['PageName'] = 'Book Report Review';
        else
            $data['PageName'] = 'Book Appointment';
        
        $data['icon'] = 'Group(40).png';

        session()->forget('appointment_id');    
        session()->forget('patient_rescheduled_appointment_id');  
        
        if (!empty($data['appointment'])) {
            $app_details = $data['appointment'];

            $sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND id=" . $data['appointment']->doctor_id;
            $doctor = DB::select($sql);
            $doctor = collect($doctor);
            $doctor = $doctor->first();
            $data['doctor_name'] = $doctor;

            $data['interests'] = $data['appointment']->interests;
        }
        
        // Return the view with the appointment and payment data
        return view('frontend.appointments.show_booking', $data);
    }

    
    // public function book_step3(Request $request)
    // {
        
    //     $data = [];
    //     $appointmentType = $this->getSessionData("appointmentType");        
        
    //     if($appointmentType == "Report Review")
    //     $data['PageName'] = 'Book Report Review';
    //     else
    //     $data['PageName'] = 'Book Appointment';
    //     $data['icon'] = 'Group(40).png';

    //     $data["appointment_id"] = $appointment_id = $this->getSessionData('appointment_id'); 
        
    //     if(!empty($data["appointment_id"])){
    //         $data['appointment'] = Schedule::findOrFail($data["appointment_id"]);
    //         if(!empty($data['appointment'])){
    //             $start_datetime = Carbon::parse($data['appointment']->start);
    //             $end_datetime = Carbon::parse($data['appointment']->end);
    //             $data['app_start_date'] = $start_datetime->format('M d, Y');
    //             $data['app_start_time'] = $start_datetime->format('G:i');
    //             $data['app_end_date'] = $end_datetime->format('M d, Y');
                
    //             $sql = "SELECT first_name, last_name FROM dbl_users where user_type = 'doctor' AND id=".$data['appointment']->doctor_id;
    //             $doctor = DB::select($sql);
    //             $doctor = collect($doctor);
    //             $data['doctor_name'] = $doctor->first();
                
    //             // $interests = json_decode($data['appointment']->interests);
    //             // $data['interests'] = implode(", ", $interests);
    //             $data['interests'] = $data['appointment']->interests;
    //         }
    //     }

    //     $patient_rescheduled_appointment_id = $this->getSessionData('patient_rescheduled_appointment_id');

    //     if (!empty($patient_rescheduled_appointment_id)) 
    //     $data["appointment_id"] = $appointment_id = $patient_rescheduled_appointment_id;       

    //     if ($request->isMethod('post')) 
    //     {
            
    //             // $validator = Validator::make($request->all(), [
    //             // 'card_number' => 'required|numeric|digits:16',
    //             // 'card_name' => 'required|string|max:255',
    //             // 'expiry_date' => 'required|date_format:m/Y|after_or_equal:today',
    //             // 'cvv' => 'required|numeric|digits:3',
    //             // ]);
                
    //             $validator = Validator::make($request->all(), [
    //                 'card_number' => 'required|numeric|digits:16',
    //                 'card_name' => 'required|string|max:255',
    //                 'expiry_date' => [
    //                     'required',
    //                     'regex:/^(0[1-9]|1[0-2])\/([0-9]{2})$/', // MM/YY format
    //                     function ($attribute, $value, $fail) {
    //                         // Split the value into month and year
    //                         [$month, $year] = explode('/', $value);
    //                         $year = '20' . $year; // Convert YY to YYYY
                
    //                         // Check if the date is in the future
    //                         $expiryDate = \Carbon\Carbon::createFromFormat('Y-m', $year . '-' . $month);
    //                         if (!$expiryDate->isFuture()) {
    //                             $fail('The expiry date must be a future date.');
    //                         }
    //                     }
    //                 ],
    //                 'cvv' => 'required|numeric|digits:3',
    //             ]);

                
    //             if ($validator->fails()) {
    //             return response()->json([
    //             'errors' => $validator->errors()->toArray()
    //             ], 422); // Unprocessable Entity status code
    //             }
        
    //             // If validation passes, process the data
    //             $validatedData = $validator->validated();
        
    //             // For example, save the card data to the database
    //             $card = new Card($validatedData);
                
    //             // dd($card);
                
    //             if ($card->save()) {
                    
    //                 //update booked_appointment active=1                    
    //                 $item = Schedule::find($data["appointment_id"]);                    
    //                 $item->active = 1;
    //                 $item->save();

    //                 $sql = "SELECT * FROM tbl_appointments_booked where id=".$data["appointment_id"];
    //                 $ext_app_sql = DB::select($sql);
    //                 $extData = collect($ext_app_sql);
    //                 $ExtAppData = $extData->first();

    //                 $nowDateTime = null;

    //                 $res = DB::table('tbl_available_schedule_slots')
    //                     ->where('id', $ExtAppData->slot_id)
    //                     ->update(["lock_time"=>$nowDateTime,"booked"=>"1"]);

    //                 $patient = dbl_users::where('id', $ExtAppData->patient_id)->first();
    //                 $doctor_details = dbl_users::where('id', $ExtAppData->doctor_id)->first();

    //                 $subject = 'Appointment Booked Successfully';
    //                 $opr = 'app_booked';

    //                 SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.patient-book-app-email-template', $patient, $doctor_details, $ExtAppData, $opr, null, null, $rec_by='patient');
    //                 SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.patient-book-app-email-template', $patient, $doctor_details, $ExtAppData, $opr, null, null, $rec_by='doctor');

                    
    //                 //Add plan id when add you appointment
    //                 $PlanData['plan_id'] = '';
    //                 if($appointmentType == "Report Review"){
    //                     $PlanData['plan_id'] = 1;
    //                 } elseif($appointmentType == "Phone Consultation"){
    //                     $PlanData['plan_id'] = 2;
    //                 } elseif($appointmentType == "Video Consultation"){
    //                     $PlanData['plan_id'] = 3;
    //                 }

    //                 if(!empty($PlanData)){
    //                     $appointmentData = Schedule::findOrFail($data["appointment_id"]);
    //                     if($appointmentData && $appointmentData != ''){
    //                         $patient_id = $appointmentData['patient_id'];
    //                         DB::table('dbl_users')
    //                         ->where('id', $patient_id)
    //                         ->update($PlanData);
    //                     }
    //                 }

    //                 // Save was successful                    
    //                 return response()->json([
    //                     'status' => true,
    //                     'message' => 'Payment processed successfully!'
    //                 ]);

    //             } else {               

    //                 return response()->json([
    //                     'status' => false,
    //                     'error' => 1,
    //                     'message' => 'Problem procesing card!'
    //                 ]);
                
    //             }
                
    //             // return redirect('/patient-dashboard')->with('success', 'Appointment booked successfully!');
                
    //             // $res = DB::table('tbl_appointments_booked')
    //             //         ->where('id', $appointment_id)
    //             //         ->update($validatedData);
                
    //     }
        
    //     return view('frontend.appointments.book_step3',$data);
    // }

    public function book_step3(Request $request)
    {
                $data = [];
                $appointmentType = $this->getSessionData("appointmentType");
                $this->setSessionData('display_payment', '1');

                if ($appointmentType == "Report Review") {
                    $data['PageName'] = 'Book Report Review';
                } else {
                    $data['PageName'] = 'Book Appointment';
                }

                $data['icon'] = 'Group(40).png';
                $data["appointment_id"] = $appointment_id = $this->getSessionData('appointment_id');

                if (!empty($data["appointment_id"])) {
                    $data['appointment'] = Schedule::findOrFail($data["appointment_id"]);
                    if (!empty($data['appointment'])) {
                        $start_datetime = Carbon::parse($data['appointment']->start);
                        $end_datetime = Carbon::parse($data['appointment']->end);
                        $data['app_start_date'] = $start_datetime->format('M d, Y');
                        $data['app_start_time'] = $start_datetime->format('G:i');
                        $data['app_end_date'] = $end_datetime->format('M d, Y');

                        $sql = "SELECT first_name, last_name FROM dbl_users WHERE user_type = 'doctor' AND id=" . $data['appointment']->doctor_id;
                        $doctor = DB::select($sql);
                        $doctor = collect($doctor);
                        $data['doctor_name'] = $doctor->first();

                        $data['interests'] = $data['appointment']->interests;
                    }
                }

                $patient_rescheduled_appointment_id = $this->getSessionData('patient_rescheduled_appointment_id');

                if (!empty($patient_rescheduled_appointment_id)) {
                    $data["appointment_id"] = $appointment_id = $patient_rescheduled_appointment_id;
                }

            if ($request->isMethod('post')) 
            {
                try
                {    
                    // Validation for card details
                    $validator = Validator::make($request->all(), [
                        'stripeToken' => 'required', // Validate that a Stripe token is present
                    ]);

                    if ($validator->fails()) {
                        return redirect('/book_appointment_step3')
                            ->withErrors($validator)
                            ->withInput();
                    }

                    // Retrieve the Stripe token from the request
                    $stripeToken = $request->input('stripeToken');
                    $user = $this->getSessionData('user');            
                    $patient = dbl_users::find($user->id); // Assuming logged-in user is the patient
                    // dd($patient->id);

                    // Check if the Stripe customer already exists
                    $stripeCustomerId = '';

                    if($patient != '' && $patient['id'] > 0)
                    {
                        try {
                            $stripeCustomerId = get_or_create_stripe_customer($patient);        
                            
                            // dd("stripeCustomerId = ".$stripeCustomerId);
                            // Use $stripeCustomerId for further processing, e.g., charging the customer
                        } catch (\Exception $e) {
                            // Log the error
                            Log::error('Stripe Exception-1 caught in booking step_3', ['error' => $e->getMessage()]);                
                            // Handle any exceptions, possibly return an error response
                            // return response()->json(['error' => $e->getMessage()], 500);
                            return redirect('/book_appointment_step3')
                                    ->with('error', $e->getMessage());
                        }   
                    
                    } 
                    $cardResponse = null;
                    $stripeCardId = null;
                    // Attach the card to the customer
                    if(!empty($stripeCustomerId))
                    {
                        try{
                            $is_valid_stripe_customer = is_valid_stripe_customer($stripeCustomerId);
                        
                            // echo $is_valid_stripe_customer;

                            if($is_valid_stripe_customer)
                            {
                                $cardResponse = stripe_create_card($stripeCustomerId, $stripeToken);
                                
                                // echo "<PRE>";
                                // print_r($cardResponse); 
                                // echo "</PRE>";
                                // dd();
                                if (isset($cardResponse['error']))
                                {
                                   

                                    return redirect('/book_appointment_step3')
                                    ->with('error', $cardResponse['error']);  
                                    
                        
                                }
                                
                            }
                            else
                            {
                                return redirect('/book_appointment_step3')
                                ->with('error', 'Invalid Stripe Customer ID :: '.$stripeCustomerId);        
                            }
                            
                        }
                        catch(\Exception $e)
                        {
                            Log::error('Stripe Exception-2 caught in booking step_3', ['error' => $e->getMessage()]);                
                            
                            return redirect('/book_appointment_step3')
                                    ->with('error', $e->getMessage());
                        }
                    }
                    else
                    {
                        Log::error('Stripe Error booking step_3', ['error' => "stripeCustomerId is blank"]);                
                            
                        return redirect('/book_appointment_step3')
                                ->with('error', 'Stripe Customer ID not found');
                    }

                    if (isset($cardResponse['card']['id'])) {                        
                        // on stripe                        
                        $stripeCardId = $cardResponse['card']['id'];

                    } else {
                        // Handle card creation error                        
                        Log::error('Stripe Error adding card to Stripe customer: step_3');                
                        Log::info('Stripe response :: '.json_encode($cardResponse));                                        
                        
                        if (isset($cardResponse['card_already_exists'])) {                         
                            return redirect('/book_appointment_step3')
                                ->with('error',$cardResponse['encoded_res']);
                            }

                        if (isset($cardResponse['Exception'])) {                         
                        return redirect('/book_appointment_step3')
                            ->with('error',$cardResponse['Exception'].$cardResponse['encoded_res']);
                        }
                    }

                    // Charge the card using the cURL-based helper function
                    $amount = $data['appointment']->amount; // Convert to cents
                    $currency = 'usd'; // Replace with your currency
                    
                    Log::info("stripe_charge_card :: "."Patient_id=".$user->id." appointment_id=".$data["appointment_id"]." amount=".$amount."currency=".$currency." stripeCustomerId=".$stripeCustomerId." stripeCardId=".$stripeCardId);
                    
                    $charge = null;

                    if(!empty($stripeCardId) && !empty($stripeCustomerId) && !empty($amount) && $amount > 0)
                    {
                    $charge = stripe_charge_card($amount, $currency, $stripeCustomerId, $stripeCardId, 'Appointment payment for Patient:'.$user->first_name." ".$user->last_name);
                    }
                    else
                    {
                        return redirect('/book_appointment_step3')
                            ->with('error', 'Stripe error:: '.json_encode($cardResponse));
                    }

                    if (isset($charge['id'])) 
                    {
                        // Original logic for updating appointment status
                        $item = Schedule::find($data["appointment_id"]);
                        $item->active = 1;
                        
                        if($item->appointmentType == "Report Review")
                        $item->status = "Not-Replied";
                        else
                        $item->status = 'In-Process';                        

                        $item->save();

                        $sql = "SELECT * FROM tbl_appointments_booked WHERE id=" . $data["appointment_id"];
                        $ext_app_sql = DB::select($sql);
                        $extData = collect($ext_app_sql);
                        $ExtAppData = $extData->first();

                        $nowDateTime = null;

                        $res = DB::table('tbl_available_schedule_slots')
                            ->where('id', $ExtAppData->slot_id)
                            ->update(["lock_time" => $nowDateTime, "booked" => "1"]);

                        $patient = dbl_users::where('id', $ExtAppData->patient_id)->first();
                        $doctor_details = dbl_users::where('id', $ExtAppData->doctor_id)->first();

                        if($ExtAppData->appointmentType == 'Report Review'){
                            $subject = 'Report Review Request Submitted Successfully';
                            $opr = 'app_booked';
                            SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.patient-book-report-review-app-email-template', $patient, $doctor_details, $ExtAppData, $opr, null, null, $rec_by = 'patient');
                            SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.patient-book-report-review-app-email-template', $patient, $doctor_details, $ExtAppData, $opr, null, null, $rec_by = 'doctor');
                                                        
                            sendSms($patient,$doctor_details,$ExtAppData,"Report_Book");                
                            sendSms($patient,$doctor_details,$ExtAppData,"Report_Book_To_Doctor");                
                            
                            
                        } else {
                            $subject = 'Appointment Booked Successfully';
                            $opr = 'app_booked';
                            SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.patient-book-app-email-template', $patient, $doctor_details, $ExtAppData, $opr, null, null, $rec_by = 'patient');
                            SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.patient-book-app-email-template', $patient, $doctor_details, $ExtAppData, $opr, null, null, $rec_by = 'doctor');

                            sendSms($patient,$doctor_details,$ExtAppData,"Book");                
                            sendSms($patient,$doctor_details,$ExtAppData,"Book_To_Doctor");                
                        }

                        

                        // Add plan ID based on appointment type
                        $PlanData['plan_id'] = '';
                        if ($appointmentType == "Report Review") {
                            $PlanData['plan_id'] = 1;
                        } elseif ($appointmentType == "Phone Consultation") {
                            $PlanData['plan_id'] = 2;
                        } elseif ($appointmentType == "Video Consultation") {
                            $PlanData['plan_id'] = 3;
                        }

                        if (!empty($PlanData)) {
                            $appointmentData = Schedule::findOrFail($data["appointment_id"]);
                            if ($appointmentData && $appointmentData != '') {
                                $patient_id = $appointmentData['patient_id'];
                                DB::table('dbl_users')
                                    ->where('id', $patient_id)
                                    ->update($PlanData);
                            }
                        }
                        // dd($patient->id);
                        // Log the payment in tbl_payments
                        
                        try
                        {

                            Log::info("got Amount charge['amount']: " . $charge['amount']);
                            Log::info('Amount after division: ' . ($charge['amount'] / 100));

                            $amt = ($charge['amount'] / 100);

                            $insertedId = DB::table('tbl_payments')->insertGetId([
                            'subscription_id' => $PlanData['plan_id'],
                            'patient_id' => $patient->id,                    
                            'plan_id' => $PlanData['plan_id'], // Set your plan_id accordingly
                            'txn_id' => $charge['id'],
                            'txn_amount' => $amt,
                            'txn_status' => $charge['status'],
                            'receipt_url' => $charge['receipt_url'],
                            'txn_payment_method_id' => $charge['payment_method'],
                            'txn_currency' => $charge['currency'],
                            'txn_time' => now(),
                            'txn_gateway' => 'Stripe',
                            'txn_method' => $charge['payment_method_details']['type'],
                            'txn_desc' => 'Appointment payment',
                            'txn_response_object' => json_encode($charge),
                            'created_on' => now(),
                            ]);
    
                            if($insertedId > 0)
                            {
                                $res = DB::table('tbl_appointments_booked')
                                ->where('id', $data["appointment_id"])
                                ->where('patient_id', $patient->id)
                                ->update(['payment_id'=>$insertedId]);
                                
                                Log::info("inserted tbl_appointments_booked id :: " . $insertedId." res:".$res);
                                session()->forget('display_payment');
                                $this->setSessionData('display_payment', '1');
                                return redirect('show_booking'); 
                            }

                        }
                        catch(\Exception $e)
                        {
                            return redirect('/book_appointment_step3')
                            ->with('error', $e->getMessage());                    
                        }                

                        

                    } else {
                        // Log payment failure in tbl_payments
                        DB::table('tbl_payments')->insert([
                            'patient_id' => $patient->id,
                            'txn_amount' => $amount / 100,
                            'txn_status' => 'failed',
                            'txn_desc' => 'Failed appointment payment',
                            'txn_error_object' => json_encode($charge),
                            'created_on' => now(),
                        ]);

                        return redirect('/book_appointment_step3')
                            ->with('error', 'Payment failed. Please try again.');
                    }
                }    
                catch(\Exception $e)
                {
                    Log::error("Exception : processing payments ::".$e->getMessage());
                    
                    return redirect('/book_appointment_step3')
                    ->with('error', 'Exception processing payments ::'.$e->getMessage());
                }
            }
        
        

        return view('frontend.appointments.book_step3', $data);
    }
    
    
     
    public function book_step2(Request $request, $app_id = null)
    {
        $data = [];
        $appointmentType = $this->getSessionData('appointmentType');
        
        $this->setSessionData('display_payment', '0');

        $cat_type = $this->getSessionData('cat_type');

        if($cat_type && $cat_type != null){
            $data['cat_type'] = $cat_type;
        } else {
            $data['cat_type'] = '';
        }
        
        if($appointmentType == 'Report Review')
        $data['PageName'] = 'Book Report Review';
        else
        $data['PageName'] = 'Book Appointment';
        $data['icon'] = 'Group(40).png';

        $data["appointment_id"] = $appointment_id = $this->getSessionData('appointment_id');
        $data["user"] = $user = $this->getSessionData('user');
        $data["user"] = $user = dbl_users::find($user->id);
        $data['selected_plan'] =  $this->getSessionData("selected_plan"); 
        
        $ExtAppSql = "SELECT * FROM tbl_appointments_booked where id=".$appointment_id;
        $ext_app_sql = DB::select($ExtAppSql);
        $extAppData = collect($ext_app_sql);
        $Existing_Data = $extAppData->first();

        if($data["appointment_id"] != ''){
            $data['LastAppointment'] = $Existing_Data;
        }
    
        if(!empty($app_id)){
            $app_id = Crypt::decrypt($app_id);
            $patient_rescheduled_appointment_id = $app_id;
        } else {
            $patient_rescheduled_appointment_id = '';
        }

        if (!empty($patient_rescheduled_appointment_id)) {
            $data["app_id"] = $appointment_id = $patient_rescheduled_appointment_id;
            
            $sql = "SELECT * FROM tbl_appointments_booked where id=".$patient_rescheduled_appointment_id;
            $ext_app_sql = DB::select($sql);
            $extData = collect($ext_app_sql);
            $data['ExtAppData'] = $extData->first();
            
        } else {
            $data["app_id"] = '';
        }
        
        if(empty($data["user"]))
        return redirect('/login')->with('error', 'Please login!');
        
        if(empty($appointment_id))
        return redirect('/book_appointment')->with('error', 'Invalid Appointment,Please select date');
        
        if ($request->isMethod('post') && $appointment_id > 0) 
        {
            
                // Validate the incoming request data
                $validatedData = $request->validate([
                    'name' => 'required|string|max:255',
                    'gender' => 'required|in:male,female,other',
                    'age' => 'required|integer|min:0|max:99',
                    'email' => 'required|email|max:255',
                    'contactNumber' => 'required',
                    'alternateContactNumber' => 'nullable',
                    'state' => 'required|string|max:2',
                    'city' => 'required|string|max:255',
                    'interests' => 'required|array',
                    'interests.*' => 'string|in:Shoulder,Knee,Ankle,Hand,Elbow,Back,Foot,Wrist,Hip,Neck',
                    'symptoms' => '',
                    'category' => 'required',                    
                    'medicalDocuments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
                    'agree' => 'accepted',
                ], [
                    'name.required' => 'Name is required',
                    'gender.required' => 'Gender is required',
                    'age.required' => 'Age is required',
                    'email.required' => 'Email is required',
                    'contactNumber.required' => 'Contact number is required',
                    'state.required' => 'State is required',
                    'city.required' => 'City is required',
                    'interests.required' => 'At least two joints is required',
                    'symptoms' => '',
                    'category.required' => 'Category is required',                    
                    'agree.accepted' => 'You must agree to the terms',
                ]);
                
                // 'appointmentType' => 'required|in:phone,video',
                
                // Handle file uploads
                // if ($request->hasFile('medicalDocuments')) {
                //     $medicalDocuments = [];
                //     foreach ($request->file('medicalDocuments') as $file) {
                //         $path = $file->store('medical_documents');
                //         $medicalDocuments[] = $path;
                //     }
                //     $validatedData['medicalDocuments'] = json_encode($medicalDocuments);
                // }

                $report_file_names = "";
                if (isset($_FILES['medicalDocuments']) && !empty($_FILES['medicalDocuments']['name'][0]))
                {
                    
                    $fileNames = [];
                    $errors = [];
        
                        foreach ($_FILES['medicalDocuments']['name'] as $key => $name) {
                            $tmpName = $_FILES['medicalDocuments']['tmp_name'][$key];
                            $size = $_FILES['medicalDocuments']['size'][$key];
                            $error = $_FILES['medicalDocuments']['error'][$key];
                            
                            // Validate file
                            if ($error === UPLOAD_ERR_OK) {
                                // Specify allowed file types and size limit (e.g., 2MB)
                                $allowedTypes = ['pdf','jpeg','jpg','png'];
                                $maxSize = 4 * 1024 * 1024; // 2MB
                                $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                
                                $new_name = uniqid().".".$fileExt;
                                
                                if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                                    // Move uploaded file to the 'uploads' directory
                                    $filePath = 'public/patient_reports/' . $new_name;
                                    if (move_uploaded_file($tmpName, $filePath)) {
                                        // Save file information to the database
                                        
                                        $fileNames[] = $new_name;
                                    } else {
                                        $errors[] = "Failed to move file $name.";
                                    }
                                } else {
                                    
                                    $errors[] = "Invalid file type or size for file $name.";
                                }
                            } else {
                                $errors[] = "error uploading file $name. error code: $error.";
                            }
                        }

                        // Add existing file login
                        if($fileNames && count($fileNames) > 0){

                            if($Existing_Data && $Existing_Data->medicalDocuments != ''){
                                $report_file_names = implode(",",$fileNames).','.$Existing_Data->medicalDocuments;
                            } elseif($data["user"] && $data["user"]->medicalDocuments != ''){
                                $report_file_names = implode(",",$fileNames).','.$data["user"]->medicalDocuments;
                            } else {
                                $report_file_names = implode(",",$fileNames);
                            }
                        } else {
                            if($Existing_Data && $Existing_Data->medicalDocuments != ''){
                                $report_file_names = $Existing_Data->medicalDocuments;
                            }

                            if($data["user"] && $data["user"]->medicalDocuments != ''){
                                $report_file_names = $data["user"]->medicalDocuments;
                            }
                        }
                        
                        
                        if (!empty($errors)) {
                            // echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                            // die;
                            $errors = implode('<br>', $errors);
                            return redirect('/book_appointment_step2')
                                    ->with('error', $errors);
                        } else {
                            // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                            // die;
                        }
                } else {

                    if($Existing_Data && $Existing_Data->medicalDocuments != ''){
                        $report_file_names = $Existing_Data->medicalDocuments;
                    }

                    if($data["user"] && $data["user"]->medicalDocuments != ''){
                        $report_file_names = $data["user"]->medicalDocuments;
                    }
                }

                $validatedData['medicalDocuments'] = $report_file_names;
        
                unset($validatedData['agree']);
                
                $stateId = $request->state;
                // $cityId =  $request->city;

                $state = UsState::find($stateId);
                // $city = UsCity::find($cityId);           

                // $cityName = $city ? $city->CITY : null;  
                // $validatedData['city'] = $cityName;

                $stateName = $state ? $state->STATE_NAME : null;                                
                // $validatedData['state'] = $stateName;

                // Calculate timezone based on state (dummy implementation, customize as needed)
                $timezone = $this->getTimezoneByState($stateName);   
                
                $validatedData['timezone'] = $timezone;

                if(!empty($request->app_id))
                {
                    $patient_rescheduled_appointment_id = $appointment_id = $request->app_id;
                    $sql = "SELECT * FROM tbl_appointments_booked where id=".$patient_rescheduled_appointment_id;
                    $ext_app_sql = DB::select($sql);
                    $extData = collect($ext_app_sql);
                    $ExtAppData = $extData->first();

                    $patient = dbl_users::where('id', $ExtAppData->patient_id)->first();
                    $doctor_details = dbl_users::where('id', $ExtAppData->doctor_id)->first();

                    $subject = 'Appointment Rescheduled Successfully';
                    $opr = 'app_resch';

                    
                    if($ExtAppData->status == 'Cancelled' || $ExtAppData->status == 'Rejected'){
                        $validatedData['status'] = 'In-process';
                    }
                    
                    if($cat_type == ''){
                        SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-reschedule-email-template', $patient, $doctor_details, $ExtAppData, $opr, null, null, $rec_by = 'patient');
                        SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-reschedule-email-template', $patient, $doctor_details, $ExtAppData, $opr, null, null, $rec_by = 'doctor');
                        sendSms($patient,$doctor_details,$ExtAppData,"Rescheduled_To_Patient");
                        sendSms($patient,$doctor_details,$ExtAppData,"Rescheduled_To_Doctor");
                    }

                } else {
                    $patient_rescheduled_appointment_id = '';
                }
                
                $validatedData['interests'] = implode(", ",$validatedData['interests']);
                
                // dd($validatedData->interest
                
                $validatedData['contactNumber'] = formatPhoneNumber($validatedData['contactNumber']);
                $validatedData['alternateContactNumber'] = formatPhoneNumber($validatedData['alternateContactNumber']);

                // dd($validatedData);
                $res = DB::table('tbl_appointments_booked')
                ->where('id', $appointment_id)
                ->update($validatedData);
                
                if($res || !empty($patient_rescheduled_appointment_id))
                {
                    $PatientIdSql = "SELECT * FROM tbl_appointments_booked where id=".$appointment_id;
                    $PatientIdSql = DB::select($PatientIdSql);

                    $patient = dbl_users::where('id', $PatientIdSql[0]->patient_id)->first();
                    $doctor_details = dbl_users::where('id', $PatientIdSql[0]->doctor_id)->first();

                    // $PatientAppSql = "SELECT id FROM tbl_appointments_booked where patient_id=".$PatientIdSql[0]->patient_id;
                    // $PatientAppSql = DB::select($PatientAppSql);

                    $PatientAppSql = "SELECT ab.id FROM tbl_appointments_booked ab
                        LEFT JOIN tbl_payments p ON ab.patient_id = p.patient_id where ab.active = 1 AND 
                        ab.patient_id = ".$PatientIdSql[0]->patient_id." AND ab.id != ".$appointment_id." AND ab.appointmentType != 'Report Review' GROUP BY ab.id";
                    $PatientAppSql = DB::select($PatientAppSql);


                    $extData = "SELECT * FROM tbl_appointments_booked ab
                        where ab.id = ".$appointment_id." AND ab.appointmentType != 'Report Review'";
                    $extData = DB::select($extData);
                    $extData = collect($extData);
                    $ExtAppData = $extData->first();

                    // $end_datetime_plus_1day = date('Y-m-d H:i:s',strtotime($PatientIdSql[0]->start.' +30 Day')); 
                    // $end_datetime_plus_1day = date('Y-m-d H:i:s', strtotime('+30 days'));
                    $totalAppDays = '';

                    if($PatientAppSql != '' && count($PatientAppSql) > 0 && $PatientIdSql[0]->appointmentType != 'Report Review'){
                        $AppStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $PatientIdSql[0]->start);
                        // Get the current date
                        $nowDate = Carbon::now();
                        // Calculate the difference in days
                        $totalAppDays = $nowDate->diffInDays($AppStartTime);
                    }

                    $this->setSessionData('patient_rescheduled_appointment_id', $appointment_id);
                    $this->setSessionData('update_appointment_id', $patient_rescheduled_appointment_id);
                    
                    if((count($PatientAppSql) <= 1 && $totalAppDays != '' && $totalAppDays <= 30 && $cat_type != '')){
                        $PaidAmt['status'] = 'In-Process';
                        $PaidAmt['follow_up'] = 1;
                        $PaidAmt['active'] = 1;
                        $res = DB::table('tbl_appointments_booked')
                            ->where('id', $appointment_id)
                            ->update($PaidAmt);

                        $subject = 'Appointment Booked Successfully';
                        $opr = 'app_booked';
                        SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.patient-book-app-email-template', $patient, $doctor_details, $ExtAppData, $opr, null, null, $rec_by = 'patient');
                        SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.patient-book-app-email-template', $patient, $doctor_details, $ExtAppData, $opr, null, null, $rec_by = 'doctor');

                        sendSms($patient,$patient,$ExtAppData,"Book");                
                        sendSms($patient,$doctor_details,$ExtAppData,"Book_To_Doctor"); 
                        
                        $this->setSessionData('display_payment', '0');
                        
                        return redirect('show_booking');
                    } else {
                        if (!empty($patient_rescheduled_appointment_id) && $cat_type == '') 
                        {   
                            $this->setSessionData('display_payment', '0');
                            // return redirect('show_booking')->with('success', 'Appointment rescheduled successfully!'); 
                            return redirect('show_booking'); 
                        } else {
                            return redirect('/book_appointment_step3');
                        }
                    }
                }
                
                
                
                // return redirect('/patient-dashboard');
        }
        
        $data["appointmentType"] = $this->getSessionData('appointmentType');

        return view('frontend.appointments.book_step2', $data);
    }
    public function book(Request $request, $id = null, $app_id = null, $type = null)
    {  
        $data= [];
        $data['ExtAppData'] = null;
        $patient_rescheduled_appointment_id = '';

        $cat_type = $this->getSessionData('cat_type');

        if($cat_type && $cat_type != null){
            $data['cat_type'] = $cat_type;
        } else {
            $data['cat_type'] = '';
        }
        
        if($type && $type != null){
            $data['type'] = $type;
        } else {
            $data['type'] = '';
        }

        if($request->doctor_id && $request->doctor_id != ''){
            $data['doctor_id'] = $request->doctor_id;
        } else {
            $data['doctor_id'] = '';
        }

        // $doctor_sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND id IN(select doctor_id from tbl_available_schedule_slots where date(start) >= '".date("Y-m-d")."')";
        // $doctor_sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND status = 'Active'";
        // $doctor_Data = DB::select($doctor_sql);
        // $data['doctors'] = collect($doctor_Data);        

        // echo'<pre>';print_r($data);die;

        // Get all doctors without pagination
        // $data['doctors'] = $doctor_query->get();

        $sql = "SELECT * FROM tbl_plans where status= 'Active'";
            $tbl_plans = DB::select($sql);
            $tbl_plans = collect($tbl_plans);					                
        
        $data['tbl_plans'] = $tbl_plans;

        $data['PageName'] = 'Search';
        $data['icon'] = 'Vector.png';
        
        if($id !=null && $id > 0)
        {
            $sql = "SELECT * FROM tbl_plans where id= $id AND status= 'Active'";
            $tbl_plans = DB::select($sql);
            $tbl_plans = collect($tbl_plans);					                
            $tbl_plan =  $tbl_plans->first();
            
            $this->setSessionData("selected_plan",$tbl_plan);            
            
            // dd($selected_plan->plan_type);
        
        }
        
        if (!empty($app_id) && $app_id > 0) {
            $app_id = Crypt::decrypt($app_id);
            $patient_rescheduled_appointment_id = $app_id;
            
            $data["appointment_id"] = $patient_rescheduled_appointment_id;
            
            $sql = "SELECT * FROM tbl_appointments_booked where id=".$patient_rescheduled_appointment_id;
            $ext_app_sql = DB::select($sql);
            $extData = collect($ext_app_sql);
            $data['ExtAppData'] = $extData->first();
            
        } else {
            $data["appointment_id"] = '';
            $data['cat_type'] = '';
        }
        
        if ($request->isMethod('post')) 
        {  
            if($request->search && !empty($request->search))
            {
                $SearchString = $request->search;
                $data['SearchString'] = $SearchString;
                
                $stateId = UsState::getStateIdByName($SearchString);
                $cityId = UsCity::getCityIdByName($SearchString);
                
                $doctor_query = DB::table('dbl_users')
                    ->where('user_type', 'doctor')
                    ->where('status', 'Active');

                // Apply filters based on stateId, cityId, and SearchString
                if (!empty($stateId)) {
                    $doctor_query->where('state', $stateId);
                } elseif($SearchString && $SearchString != '') {
                    $doctor_query->where(function ($q) use ($SearchString) {
                        $q->where('first_name', 'LIKE', '%' . $SearchString . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $SearchString . '%')
                        ->orWhere('speciality', 'LIKE', '%' . $SearchString . '%');
                    });
                } elseif (!empty($cityId) && $SearchString != 'Alex' && $SearchString != 'alex') {
                    $doctor_query->where('city', $cityId);
                }

                // Get the count of the records before pagination
                $record_count = $doctor_query->count();

                // Now apply the condition based on the record count
                if ($record_count > 10) {
                    $data['pagination'] = 1;
                    $data['doctors'] = $doctor_query->paginate(10);
                } else {
                    $data['pagination'] = 0;
                    $data['doctors'] = $doctor_query->get();  // You can change this logic if needed
                }
            } 
            else 
            {
                
                if(!empty($request->appointmentType) && $request->appointmentType == "Report Review")
                {   
                    $request->validate([
                        'doctor_id' => 'required'                        
                    ]);
                }
                
                if(!empty($request->appointmentType) && $request->appointmentType != "Report Review")
                {
                    $request->validate([
                        'doctor_id' => 'required',
                        'selected_timeslot_id' => 'required',
                        'selected_date_id' => 'required',                        
                    ]);
                }

                if(empty($request->appointmentType))
                {
                    $request->validate([                        
                        'appointmentType' => 'required'                    
                    ]);
                }
                
                $user = $this->getSessionData('user');
                if(empty($user))
                {
                 return redirect('/login');   
                }

                $sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND id=".$request->doctor_id;
                $doctor = DB::select($sql);
                $doctor = collect($doctor);
                $doctor = $doctor->first();

                $sql = "SELECT * FROM dbl_users where user_type = 'patient' AND id=".$user->id;
                $patient = DB::select($sql);
                $patient = collect($patient);
                $patient = $patient->first();            

                $item = null;
                
                $patient_rescheduled_appointment_id = "";

                if($request->app_id && !empty($request->selected_timeslot_id)){
                    $patient_rescheduled_appointment_id = $request->app_id;
                } else {
                    $patient_rescheduled_appointment_id = '';
                    $this->setSessionData("cat_type",'');
                }
                
                if(!empty($patient_rescheduled_appointment_id) && $cat_type == '')
                {
                    $item = Schedule::find($patient_rescheduled_appointment_id);                    
                }
                else
                {    
                $item = new Schedule();                
                }                              
                
                
                /*
                Date: 24 Aug 2024
                Added by: zahoor      
                Purpose: moved appointment type selection to first booking page
                */
                
                $this->setSessionData('appointmentType',"");

                if(!empty($request->appointmentType))
                {
                    $item->appointmentType = $request->appointmentType;                    
                    
                    if(!empty($item->appointmentType))
                    $this->setSessionData('appointmentType',$item->appointmentType);

                    $sql = "SELECT * FROM tbl_plans where plan_type= '".$request->appointmentType."' AND status= 'Active'";
                    $tbl_plans = DB::select($sql);
                    $tbl_plans = collect($tbl_plans);					                
                    $tbl_plan =  $tbl_plans->first();

                    $plan_amount = str_replace("$","",$tbl_plan->plan_amount);                    
                    $item->amount = $plan_amount; 
                    
                }

                if(!empty($request->selected_timeslot_id) && $request->selected_timeslot_id > 0 && $request->doctor_id > 0 && $user->id > 0)
                {
                    
                        $sql = "SELECT * FROM tbl_available_schedule_slots where id = ".$request->selected_timeslot_id;
                        $slots = DB::select($sql);
                        $slots = collect($slots);
                        $slots = $slots->first();

                        $item->start = $slots->start;
                        $item->end = $slots->end;
                        $item->slot_id = $slots->id;                    
                        $start_datetime = Carbon::parse($slots->start);
                        $end_datetime = Carbon::parse($slots->end);

                        $start_time = $start_datetime->format('H:i');
                        $end_time = $end_datetime->format('H:i');

                        

                        // $item->description = $request->description;
                        // $item->color = $request->color;                    
                        // $item->notes = $request->notes;
                }
                

                // $data['ExtAppData'] = null;
                
                
                    if($request->appointmentType == "Report Review")
                    {

                        $item->title = "Dr.".$doctor->first_name.":"."Report Review";

                        if(empty($patient_rescheduled_appointment_id && $data['ExtAppData'] == null))
                        {
                        $item->status = "Un-Paid";
                        }
                        
                    }
                    else
                    {
                        $item->title = "Dr.".$doctor->first_name.":".$start_time." To ".$end_time;

                        if(empty($patient_rescheduled_appointment_id)  && $data['ExtAppData'] == null)
                        {
                        $item->status = "Un-Paid";
                        }
                    }
                              

                    

                    $item->doctor_id = $doctor->id;
                    $item->patient_id = $patient->id;                                       

                    $item->save();

                    $appointment_id = $insertedId = $item->id;

                    if(!empty($request->selected_timeslot_id))
                    {
                        $nowDateTime = Carbon::now()->toDateTimeString();

                        $res = DB::table('tbl_available_schedule_slots')
                            ->where('id', $request->selected_timeslot_id)
                            ->update(["lock_time"=>$nowDateTime, "is_available"=>"0", "booked"=>"0"]);
                    }
                    
                    // on step3 after payement "booked"=>"1" lock_time = null  
                    // cron  : select tbl_available_schedule_slots where "is_available"=>"0"
                    // and lock_time < now-1hour
                    // update tbl_available_schedule_slots set "is_available"=>"1"

                    // print_r($item);       
                    // die;

                    $this->setSessionData('appointment_id',$appointment_id);                    

                    if(!empty($patient_rescheduled_appointment_id))
                    return redirect('/book_appointment_step2/'.Crypt::encrypt($appointment_id));
                    else
                    return redirect('/book_appointment_step2/');
                    // return redirect('/patient-dashboard')->with('success', 'Reschedule appointment successfully!');

                } 
            
        }
        
        if(!$request->search){
            if($data['cat_type'] && $data['cat_type'] != null && $app_id != ''){
                $sql = "SELECT * FROM tbl_appointments_booked where id=".$app_id;
                $extData = DB::select($sql);

                $doctor_query = DB::table('dbl_users')
                    ->where('id', $extData[0]->doctor_id)
                    ->where('user_type', 'doctor')
                    ->where('status', 'Active');
            } else {
            
                $doctor_query = \App\Models\dbl_users::where('user_type', 'doctor')
                    ->where('status', 'Active')
                    ->withCount('availableScheduleSlots')
                    ->orderBy('available_schedule_slots_count', 'desc')
                    ;    
            }

            // Get the count of the records before pagination
            $record_count = $doctor_query->count();

            // Now apply the condition based on the record count
            if ($record_count > 10) {
                $data['pagination'] = 1;
                $data['doctors'] = $doctor_query->paginate(10);
            } else {
                $data['pagination'] = 0;
                $data['doctors'] = $doctor_query->get();  // You can change this logic if needed
            }
        }
        
        // dd($data['doctors']);
        // Handle GET request (displaying the form)
        return view('frontend.appointments.book', $data);
    }

    public function getEvents()
    {
        $schedules = Schedule::all();
        return response()->json($schedules);
    }


        // Retrieve a user's saved cards
    public function getUserCards($userId) {
        $cards = DB::table('tbl_stripe_cards')
                ->where('user_id', $userId)
                ->get();

        return view('user.cards', ['cards' => $cards]);
    }

    // Process a payment using a saved card
    public function processPayment(Request $request) {
        $cardId = $request->card_id;  // Assume the user selects a card ID from their saved cards
        $stripeCustomerId = DB::table('tbl_stripe_customers')
                            ->where('user_id', $request->user()->id)
                            ->value('stripe_customer_id');

        $amount = 1000; // e.g., $10.00
        $charge = stripe_charge_card($amount, 'usd', $stripeCustomerId, $cardId, "Payment Description");
        
        if ($charge['status'] == 'succeeded') {
            // Handle successful charge
            return back()->with('success', 'Payment successful!');
        } else {
            // Handle failed charge
            return back()->with('error', 'Payment failed!');
        }
    }

    // ReportReviewsRepliesController.php
    public function deleteFile(Request $request)
    {
        $AppId = $request->input('AppId');
        $filename = $request->input('filename');
        
        $Appointment = Schedule::find($AppId);
        if ($Appointment) {
            // Get existing filenames
            $files = explode(',', $Appointment->medicalDocuments);
            
            // Remove the file to be deleted
            $files = array_filter($files, function($file) use ($filename) {
                return trim($file) !== $filename;
            });

            // Update the column with the new comma-separated values
            $Appointment->medicalDocuments = implode(',', $files);
            $Appointment->save();
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
}
