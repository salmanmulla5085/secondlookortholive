<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Schedule;
use App\Models\Chat;
use App\Models\UsState;
use App\Models\UsCity;
use App\Models\dbl_users;
use Carbon\Carbon;
use App\Services\AppointmentService;
use App\Services\SendGridService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;

class ApiDoctorController extends Controller
{
    protected $appointmentService;
    protected $sendGridService;

    public function __construct(AppointmentService $appointmentService, SendGridService $sendGridService)
    {
        $this->appointmentService = $appointmentService;
        $this->sendGridService = $sendGridService;
    }

    public function getDoctorList(Request $request)
    {
        // Extract the token from the request
        
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        // Fetch appointments
        $doctorDataList =  User::where('user_type','doctor')->get();
     
        return response()->json([           
            'result' => $doctorDataList,            
            'debug_user'=> $user
        ]);
    }

    public function SearchDoctor(Request $request)
    {
        // Extract the token from the request
        
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            $validatedData = $request->validate([
                'search' => ['required'],
            ]);

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

            $doctorDataList = $doctor_query->get();  // You can change this logic if needed
        
            return response()->json([           
                'result' => $doctorDataList,            
                'debug_user'=> $user
            ]);
        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function getPatientList(Request $request)
    {
        // Extract the token from the request
        
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        // Fetch appointments
        $patientDataList =  User::where('user_type','patient')->get();
     
        return response()->json([           
            'result' => $patientDataList,            
            'debug_user'=> $user
        ]);
    }

    public function getConsultationPlan(Request $request)
    {
        // Extract the token from the request
        
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        // Fetch appointments
        $sql = "SELECT * FROM tbl_plans where status= 'Active'";
        $tbl_plans = DB::select($sql);
        $tbl_plans = collect($tbl_plans);
     
        return response()->json([           
            'plan' => $tbl_plans,            
            'debug_user'=> $user
        ]);
    }

    public function getAvailableDates(Request $request)
    {
        // Extract the token from the request
        
        $token = $request->bearerToken();
        

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {

            $validatedData = $request->validate([
                'doctor_id' => ['required'],
            ]);

            $sql = "SELECT MIN(id) as id, 
            date(start) as date 
            FROM `tbl_available_schedule_slots` 
            WHERE doctor_id = ".$request->doctor_id." 
            AND date(start) >= '".date("Y-m-d")."' 
            AND date(start) <= '".date("Y-m-d", strtotime("+1 month"))."' 
            GROUP BY date";

            $AvailableDates = DB::select($sql);
            $AvailableDates2 = $AvailableDates = collect($AvailableDates);

            $now = Carbon::now();
            // $now_plus_next_time = $now->addMinutes(30);
            $now_plus_next_time = $now->addHours(8);

            foreach($AvailableDates2 as $k=>$v)
            {
                $date = $v->date;
                //output : "2024-09-09"             

                //get timeslots of that each date if its present then select that date else remve         
                if(!empty($date))
                {
                    $sql = "SELECT id,DATE_FORMAT(start, '%Y-%m-%d') as start_date,DATE_FORMAT(start, '%H:%i') as start, 
                    DATE_FORMAT(end, '%H:%i') as end FROM tbl_available_schedule_slots where 
                    is_available = 1 AND DATE_FORMAT(start, '%Y-%m-%d') = '".$date."' 
                    AND start >= '".$now_plus_next_time."' AND doctor_id = ".$request->doctor_id;

                    // dd($sql);
                    $slots = DB::select($sql);
                    $slots = collect($slots);        
                
                    if(!$slots->isNotEmpty())
                    {
                        // dd($slots);p[]74*                    //elimuinate $date from AvailableDates                                        
                        $AvailableDates->forget($k);
                    }        
                }

            }
            // dd($sql);
            // Return the timeslots as JSON response
            $AvailableDates = $AvailableDates->toArray();
            
            return response()->json([           
                'result' => array_values($AvailableDates),            
                'debug_user'=> $user
            ]);

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function getTimeslots(Request $request)
    {
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {

            $validatedData = $request->validate([
                'doctor_id'  => ['required'],
                'patient_id' => ['required'],
                'date_id'    => ['required'],
            ]);

            // Fetch available schedules for the given doctor_id and start date id         
            $patient =null;
            $doctor =null;

            if(!empty($request->doctor_id))
            {
                $sql = "SELECT * FROM dbl_users where id = ".$request->doctor_id;
                $record = DB::select($sql);
                $collection = collect($record);
                $doctor = $collection->first();
            }

            if(!empty($request->patient_id))
            {
                $sql = "SELECT * FROM dbl_users where id = ".$request->patient_id;
                $record = DB::select($sql);
                $collection = collect($record);
                $patient = $collection->first(); 
                
                $to_tz = $patient->timezone; // Replace with your target time zone
            }

            $firstRecord = null;

            if(!empty($request->date_id))
            {
                $sql = "SELECT * FROM tbl_available_schedule_slots where id = ".$request->date_id;        
                $record = DB::select($sql);
                $collection = collect($record);
                $firstRecord = $collection->first();
            }
            
            $start_datetime = $firstRecord->start;        
            $start_datetime = Carbon::parse($start_datetime);        
            $selected_date = $start_datetime->toDateString();        
            $end_datetime = $start_datetime->copy();        
            $end_datetime->addDay();
            $now_plus_30min = "";        
            
            $from_tz = $doctor->timezone; // Replace with your source time zone

            if($selected_date == date("Y-m-d"))
            {
                $now = Carbon::now();
                $now_plus_6hours = $now->addHours(6);
                
                $sql = "SELECT id, DATE_FORMAT(start, '%H:%i') as start, 
                    DATE_FORMAT(end, '%H:%i') as end FROM tbl_available_schedule_slots where 
                    is_available = 1 AND start >= '".$now_plus_6hours."' 
                    AND DATE_FORMAT(end, '%Y-%m-%d') < '".$end_datetime->toDateString()."' AND doctor_id = ".$request->doctor_id;
            } else {
                $sql = "SELECT id, DATE_FORMAT(start, '%H:%i') as start, 
                    DATE_FORMAT(end, '%H:%i') as end FROM tbl_available_schedule_slots 
                    where is_available = 1 AND DATE_FORMAT(start, '%Y-%m-%d') = '".$start_datetime->toDateString()."' 
                    AND doctor_id = ".$request->doctor_id;
            }
            
            $schedules = DB::select($sql);
            $schedules = collect($schedules);  
            
            return response()->json([           
                'result' => $schedules, 
                'selected_date_with_current_time' => $now_plus_30min,         
                'debug_user'=> $user
            ]);

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function Step1Submit(Request $request)
    {  
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            $data= [];
            $data['ExtAppData'] = null;

            if($request->doctor_id && $request->doctor_id != ''){
                $data['doctor_id'] = $request->doctor_id;
            } else {
                $data['doctor_id'] = '';
            }

            $sql = "SELECT * FROM tbl_plans where status= 'Active'";
            $tbl_plans = DB::select($sql);
            $tbl_plans = collect($tbl_plans);
            
            $data['tbl_plans'] = $tbl_plans;

            $data['PageName'] = 'Search';
            $data['icon'] = 'Vector.png';

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

        if($request->reschedule && $request->reschedule != ''){
            $data['reschedule'] = $request->reschedule;
            $data['appointment_id'] = $request->appointment_id;
        }

        if($request->follow_up && $request->follow_up != ''){
            $data['follow_up'] = $request->follow_up;
            $data['appointment_id'] = $request->appointment_id;
        }

        $sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND id=".$request->doctor_id;
        $doctor = DB::select($sql);
        $doctor = collect($doctor);
        $doctor = $doctor->first();

        $sql = "SELECT * FROM dbl_users where user_type = 'patient' AND id=".$user['id'];
        $patient = DB::select($sql);
        $patient = collect($patient);
        $patient = $patient->first();            

        $item = new Schedule();                 

        if(!empty($request->appointmentType))
        {
            $item->appointmentType = $request->appointmentType;                   
            
            if(!empty($item->appointmentType)) {
                $sql = "SELECT * FROM tbl_plans where plan_type= '".$request->appointmentType."' AND status= 'Active'";
                $tbl_plans = DB::select($sql);
                $tbl_plans = collect($tbl_plans);					                
                $tbl_plan =  $tbl_plans->first();

                $plan_amount = str_replace("$","",$tbl_plan->plan_amount);                    
                $item->amount = $plan_amount; 
            }
        }

        if(!empty($request->selected_timeslot_id) && $request->selected_timeslot_id > 0 && $request->doctor_id > 0 && $user['id'] > 0)
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
        }
        
            if($request->appointmentType == "Report Review")
            {
                $item->title = "Dr.".$doctor->first_name.":"."Report Review";

                if($data['ExtAppData'] == null)
                {
                    $item->status = "Un-Paid";
                }
                
            } else {
                $item->title = "Dr.".$doctor->first_name.":".$start_time." To ".$end_time;

                if($data['ExtAppData'] == null)
                {
                    $item->status = "Un-Paid";
                }
            }
                        
            $item->doctor_id = $doctor->id;
            $item->patient_id = $patient->id;    

            if($request->appointment_id && $request->appointment_id != '' && $request->reschedule != ''){
                $UpdateData = [
                    'appointmentType' => $item->appointmentType,
                    'amount' => $item->amount,
                    'start' => $item->start,
                    'end' => $item->end,
                    'slot_id' => $item->slot_id,
                    'title' => $item->title,
                    'status' => $item->status,
                    'doctor_id' => $item->doctor_id,
                    'patient_id' => $item->patient_id
                ];
                Schedule::where('id', $request->appointment_id)->update(['doctor_id' => $item->doctor_id]);

            } else {
                $item->save();
            }

            $appointment_id = $request->appointment_id;

            if(!empty($request->selected_timeslot_id))
            {
                $nowDateTime = Carbon::now()->toDateTimeString();

                $res = DB::table('tbl_available_schedule_slots')
                    ->where('id', $request->selected_timeslot_id)
                    ->update(["lock_time"=>$nowDateTime, "is_available"=>"0", "booked"=>"0"]);
            }

            return response()->json([           
                'result' => $item, 
                'data'   => $data,       
                'debug_user'=> $user
            ]);

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }

    }

    public function bookStep2(Request $request)
    {
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if ($request->isMethod('post') && $request->appointment_id > 0) 
            {
                $data = [];
                $appointmentType = $request->appointmentType;
                $appointment_id = $request->appointment_id;

                if($request->reschedule && $request->reschedule != ''){
                    $data['reschedule'] = $request->reschedule;
                    $data['appointment_id'] = $request->appointment_id;
                }

                if($request->follow_up && $request->follow_up != ''){
                    $data['follow_up'] = $request->follow_up;
                    $data['appointment_id'] = $request->appointment_id;
                }
                
                if($appointmentType == 'Report Review') {
                    $data['PageName'] = 'Book Report Review';
                } else {
                    $data['PageName'] = 'Book Appointment';
                    $data['icon'] = 'Group(40).png';
                }

                $data["appointment_id"] = $appointment_id;
                
                $ExtAppSql = "SELECT * FROM tbl_appointments_booked where id=".$appointment_id;
                $ext_app_sql = DB::select($ExtAppSql);
                $extAppData = collect($ext_app_sql);
                $Existing_Data = $extAppData->first();

                if($data["appointment_id"] != ''){
                    $data['LastAppointment'] = $Existing_Data;
                }
                $data['user'] = $user;
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

                $state = UsState::find($stateId);

                $data['doctor_details'] = dbl_users::where('id', $Existing_Data->doctor_id)->first();

                $stateName = $state ? $state->STATE_NAME : null;                           

                $timezone = $this->getTimezoneByState($stateName);   
                
                $validatedData['timezone'] = $timezone;
                
                $validatedData['contactNumber'] = formatPhoneNumber($validatedData['contactNumber']);
                $validatedData['alternateContactNumber'] = formatPhoneNumber($validatedData['alternateContactNumber']);

                // dd($validatedData);
                $res = DB::table('tbl_appointments_booked')
                    ->where('id', $appointment_id)
                    ->update($validatedData);
                
                if($request->follow_up && $request->follow_up != '')
                {
                    $PatientIdSql = "SELECT * FROM tbl_appointments_booked where id=".$appointment_id;
                    $PatientIdSql = DB::select($PatientIdSql);
    
                    $patient = dbl_users::where('id', $PatientIdSql[0]->patient_id)->first();
                    $doctor_details = dbl_users::where('id', $PatientIdSql[0]->doctor_id)->first();
    
                    $PatientAppSql = "SELECT ab.id FROM tbl_appointments_booked ab
                        LEFT JOIN tbl_payments p ON ab.patient_id = p.patient_id where ab.active = 1 AND 
                        ab.patient_id = ".$PatientIdSql[0]->patient_id." AND ab.id != ".$appointment_id." AND ab.appointmentType != 'Report Review' GROUP BY ab.id";
                    $PatientAppSql = DB::select($PatientAppSql);
    
                    $extData = "SELECT * FROM tbl_appointments_booked ab
                        where ab.id = ".$appointment_id." AND ab.appointmentType != 'Report Review'";
                    $extData = DB::select($extData);
                    $extData = collect($extData);
                    $ExtAppData = $extData->first();
    
                    $totalAppDays = '';
    
                    if($PatientAppSql != '' && count($PatientAppSql) > 0 && $PatientIdSql[0]->appointmentType != 'Report Review'){
                        $AppStartTime = Carbon::createFromFormat('Y-m-d H:i:s', $PatientIdSql[0]->start);
                        // Get the current date
                        $nowDate = Carbon::now();
                        // Calculate the difference in days
                        $totalAppDays = $nowDate->diffInDays($AppStartTime);
                    }
    
                    if((count($PatientAppSql) <= 1 && $totalAppDays != '' && $totalAppDays <= 30 && $request->follow_up && $request->follow_up != '')){
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
                        $data['send_payment'] = 0;
                    } else {
                        $data['send_payment'] = 1;
                    }
                }
            }

            return response()->json([           
                'result' => $data,        
                'debug_user'=> $user
            ]);

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function bookStep3(Request $request)
    {
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            $data = [];
            $appointmentType = $request->appointmentType;
            $appointment_id = $request->appointment_id;

            if ($appointmentType == "Report Review") {
                $data['PageName'] = 'Book Report Review';
            } else {
                $data['PageName'] = 'Book Appointment';
            }

            $data['icon'] = 'Group(40).png';
            $data["appointment_id"] = $appointment_id;

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

            if ($request->isMethod('post')) 
            {
                try
                {    
                    // Validation for card details
                    $validator = Validator::make($request->all(), [
                        'stripeToken' => 'required', // Validate that a Stripe token is present
                    ]);

                    if ($validator->fails()) {
                        return response()->json([
                            'status' => 'error',
                            'message' => $validator,  // This will return the validation errors
                        ], 400); // You can adjust the error code here if needed
                    }

                    $stripeToken = $request->input('stripeToken');           
                    $patient = dbl_users::find($user['id']);

                    $stripeCustomerId = '';

                    if($patient != '' && $patient['id'] > 0)
                    {
                        try {
                            $stripeCustomerId = get_or_create_stripe_customer($patient); 
                        } catch (\Exception $e) {
                            // Log the error
                            Log::error('Stripe Exception-1 caught in booking step_3', ['error' => $e->getMessage()]);                
                            return response()->json([
                                'status' => 'error',
                                'message' => $e->getMessage(),  // This will return the validation errors
                            ], 400); // You can adjust the error code here if needed
                        }   
                    
                    } 
                    $cardResponse = null;
                    $stripeCardId = null;
                    // Attach the card to the customer
                    if(!empty($stripeCustomerId))
                    {
                        try{
                            $is_valid_stripe_customer = is_valid_stripe_customer($stripeCustomerId);

                            if($is_valid_stripe_customer)
                            {
                                $cardResponse = stripe_create_card($stripeCustomerId, $stripeToken);
                                
                                if (isset($cardResponse['error']))
                                { 
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => $cardResponse['error'],  // This will return the validation errors
                                    ], 400); // You can adjust the error code here if needed
                                }
                                
                            } else {
                                return response()->json([
                                    'status' => 'error',
                                    'message' => 'Invalid Stripe Customer ID :: '.$stripeCustomerId,  // This will return the validation errors
                                ], 400); // You can adjust the error code here if needed

                            }
                            
                        }
                        catch(\Exception $e)
                        {
                            Log::error('Stripe Exception-2 caught in booking step_3', ['error' => $e->getMessage()]);                
                            
                            return response()->json([
                                'status' => 'error',
                                'message' => $e->getMessage(),  // This will return the validation errors
                            ], 400); // You can adjust the error code here if needed
                        }
                    } else {
                        Log::error('Stripe Error booking step_3', ['error' => "stripeCustomerId is blank"]);                
                            
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Stripe Customer ID not found',  // This will return the validation errors
                        ], 400); // You can adjust the error code here if needed
                    }

                    if (isset($cardResponse['card']['id'])) {                        
                        // on stripe                        
                        $stripeCardId = $cardResponse['card']['id'];

                    } else {
                        // Handle card creation error                        
                        Log::error('Stripe Error adding card to Stripe customer: step_3');                
                        Log::info('Stripe response :: '.json_encode($cardResponse));                                        
                        
                        if (isset($cardResponse['card_already_exists'])) {                         
                            return response()->json([
                                'status' => 'error',
                                'message' => $cardResponse['encoded_res'],  // This will return the validation errors
                            ], 400); // You can adjust the error code here if needed
                        }

                        if (isset($cardResponse['Exception'])) {                         
                            return response()->json([
                                'status' => 'error',
                                'message' => $cardResponse['Exception'].$cardResponse['encoded_res'],  // This will return the validation errors
                            ], 400); // You can adjust the error code here if needed
                        }
                    }

                    // Charge the card using the cURL-based helper function
                    $amount = $data['appointment']->amount; // Convert to cents
                    $currency = 'usd'; // Replace with your currency
                    
                    Log::info("stripe_charge_card :: "."Patient_id=".$user['id']." appointment_id=".$data["appointment_id"]." amount=".$amount."currency=".$currency." stripeCustomerId=".$stripeCustomerId." stripeCardId=".$stripeCardId);
                    
                    $charge = null;

                    if(!empty($stripeCardId) && !empty($stripeCustomerId) && !empty($amount) && $amount > 0)
                    {
                        $charge = stripe_charge_card($amount, $currency, $stripeCustomerId, $stripeCardId, 'Appointment payment for Patient:'.$user['first_name']." ".$user['last_name']);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Stripe error:: '.json_encode($cardResponse),  // This will return the validation errors
                        ], 400); // You can adjust the error code here if needed
                    }

                    if (isset($charge['id'])) 
                    {
                        // Original logic for updating appointment status
                        $item = Schedule::find($data["appointment_id"]);
                        $item->active = 1;
                        
                        if($item->appointmentType == "Report Review"){
                            $item->status = "Not-Replied";
                        } else {
                            $item->status = 'In-Process';
                        }                        

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
                        
                        try
                        {
                            Log::info("got Amount charge['amount']: " . $charge['amount']);
                            Log::info('Amount after division: ' . ($charge['amount'] / 100));

                            $amt = ($charge['amount'] / 100);

                            $InsertData = [
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
                                'created_on' => now()
                            ];

                            $data['transactionData'] = $InsertData;

                            $insertedId = DB::table('tbl_payments')->insertGetId($InsertData);

                            if($insertedId > 0)
                            {
                                $res = DB::table('tbl_appointments_booked')
                                ->where('id', $data["appointment_id"])
                                ->where('patient_id', $patient->id)
                                ->update(['payment_id'=>$insertedId]);
                                
                                Log::info("inserted tbl_appointments_booked id :: " . $insertedId." res:".$res);
                            
                                return response()->json([           
                                    'result' => $data,        
                                    'debug_user'=> $user
                                ]);
                            }

                        } catch(\Exception $e){
                            return response()->json([
                                'status' => 'error',
                                'message' => $e->getMessage(),  // This will return the validation errors
                            ], 400); // You can adjust the error code here if needed
                        }                
                    } else {

                        $InsertData = [
                            'patient_id' => $patient->id,
                            'txn_amount' => $amount / 100,
                            'txn_status' => 'failed',
                            'txn_desc' => 'Failed appointment payment',
                            'txn_error_object' => json_encode($charge),
                            'created_on' => now()
                        ];

                        $data['transactionData'] = $InsertData;

                        // Log payment failure in tbl_payments
                        DB::table('tbl_payments')->insert($InsertData);

                        $data['transactionData'] = $InsertData;

                        return response()->json([
                            'status' => 'error',
                            'message' => 'Payment failed. Please try again.',  // This will return the validation errors
                        ], 400); // You can adjust the error code here if needed
                    }
                } catch(\Exception $e){
                    Log::error("Exception : processing payments ::".$e->getMessage());
                    
                    return response()->json([
                        'status' => 'error',
                        'message' => $e->getMessage(),  // This will return the validation errors
                    ], 400); // You can adjust the error code here if needed
                }
            }
        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function getAppOnId(Request $request)
    {
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if ($request->isMethod('post')){

                $validatedData = $request->validate([
                    'appointment_id' => ['required'],
                ]);

                if($request->appointment_id != null){
                    $Appsql = "SELECT * FROM tbl_appointments_booked where id = $request->appointment_id";
                    $app_details = DB::select($Appsql);
                    $AppData = collect($app_details); 

                    if(!empty($AppData) && count($AppData) > 0){

                        $slotId = $AppData[0]->slot_id;

                        if($slotId > 0){
                            $UpdateSlot['is_available'] = 1;
                            $res = DB::table('tbl_available_schedule_slots')
                                ->where('id', $slotId)
                                ->update($UpdateSlot);
                        }
                    }
                }
            }

            return response()->json([           
                'result' => $AppData,        
                'debug_user'=> $user,
                'reschedule'=> 1
            ]);

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function CancelAppointment(Request $request){
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if ($request->isMethod('post')){

                $validatedData = $request->validate([
                    'appointment_id' => ['required'],
                ]);

                if($request->appointment_id != null){
                    //Logic for appointment timeslot become free.
                    $Appsql = "SELECT * FROM tbl_appointments_booked where id = $request->appointment_id";
                    $app_details = DB::select($Appsql);
                    $AppData = collect($app_details); 

                    if(!empty($AppData) && count($AppData) > 0){

                        $slotId = $AppData[0]->slot_id;

                        if($slotId > 0){
                            $UpdateSlot['is_available'] = 1;
                            $res = DB::table('tbl_available_schedule_slots')
                                ->where('id', $slotId)
                                ->update($UpdateSlot);
                        }
                    }
                
                    $Update_data['status'] = 'Cancelled';
                    $Update_data['CancelPatientOrDoctor'] = 1;
                    
                    $res = DB::table('tbl_appointments_booked')
                                        ->where('id', $request->appointment_id)
                                        ->update($Update_data);  
                                        
                    $patient = dbl_users::where('id', $AppData[0]->patient_id)->first();
                    $doctor_details = dbl_users::where('id', $AppData[0]->doctor_id)->first();
                    $app_details = Schedule::findOrFail($request->appointment_id);
                    $subject = 'Appointment Cancelled Successfully';

                    SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-cancel-by-patient-email-template', $patient, $doctor_details, $app_details, NULL, NULL, NULL, $rec_by='patient');
                    SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-cancel-by-patient-email-template', $patient, $doctor_details, $app_details, NULL, NULL, NULL, $rec_by='doctor');
                    
                    // sendSms($patient,$doctor_details,$app_details,"Cancelled");                
                    sendSms($patient,$doctor_details,$app_details,"Cancelled_To_Doctor"); 

                    return response()->json([           
                        'result' => $res,        
                        'debug_user'=> $user
                    ]);
                }
            }

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function downloadPDF(Request $request)
    {
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if ($request->isMethod('post')){

                $validatedData = $request->validate([
                    'appointment_id' => ['required'],
                ]);

                if($request->appointment_id != null){
                    $data['extAppData'] = DB::table('tbl_appointments_booked')
                        ->where('id', $request->appointment_id)
                        ->get();
                    
                    $data['patient'] = DB::table('dbl_users')
                        ->where('id', $data['extAppData'][0]->patient_id)
                        ->where('user_type', 'patient')
                        ->first();

                    $data['doctor'] = DB::table('dbl_users')
                        ->where('id', $data['extAppData'][0]->doctor_id)
                        ->where('user_type', 'doctor')
                        ->first();

                    $pdf = PDF::loadView('emails.patient_pdf', $data);        
                    // echo'<pre>';print_r($pdf);die;  
                    return $pdf->download($data['patient']->first_name.'_history.pdf');
                }

                return response()->json([           
                    'result' => 1,        
                    'debug_user'=> $user
                ]);
            }
        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function getPatientApp(Request $request){   
 
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if ($request->isMethod('post')){

                $validatedData = $request->validate([
                    'record_type' => ['required'],
                ]);
        
                $data = [];

                $record_type = $request->record_type;

                if(!empty($user)){
                    $user_id = $user['id'];
                    $sql = "SELECT * FROM tbl_appointments_booked where patient_id = $user_id 
                    AND `status` != 'Cancelled'";

                    if($record_type == "upcoming"){
                        //upcoming sql
                        $sql = "SELECT ab.phone_meeting_link,ab.id,ab.patient_id, 
                        ab.status as appointment_status, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, 
                        ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments, ab.upload_file1,ab.notes,
                        u.first_name AS doctor_first_name, u.last_name AS doctor_last_name, u.phone_number as doctor_phone_number, 
                        ab.CancelPatientOrDoctor FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1  
                        AND appointmentType != 'Report Review' AND 
                        ab.patient_id = $user_id AND ab.end >= '".date('Y-m-d H:i:s')."' order by start DESC";
                    } else {
                        $sql = "SELECT ab.phone_meeting_link,ab.id,ab.patient_id, 
                        ab.status as appointment_status, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, 
                        ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments, ab.upload_file1, ab.notes,
                        u.first_name AS doctor_first_name, u.last_name AS doctor_last_name, u.phone_number as doctor_phone_number, ab.CancelPatientOrDoctor FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where  ab.active = 1 
                        AND appointmentType != 'Report Review' AND 
                        ab.patient_id = $user_id AND ab.end <'".date('Y-m-d H:i:s')."' order by start DESC";
                    }
        
                    $appointments_booked = DB::select($sql);
                    $appointments_booked = collect($appointments_booked);
                    $data["appointments"] =  $appointments_booked;				                                                          
                
                }
                return response()->json([           
                    'result' => $appointments_booked,        
                    'debug_user'=> $user
                ]);
            }
        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function messages(Request $request){
        $data['PageName'] = 'Message';
        $data['icon'] = 'patient_icon_02.png';

        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if($user['user_type'] == 'patient'){
                    
                $extData = DB::table('tbl_appointments_booked')
                    ->where('patient_id', $user['id'])
                    ->where('start', '<', now()) // using now() for current timestamp
                    ->whereIn('status', ['Completed', 'Confirmed']) // Add status condition
                    ->orderBy('start', 'desc');
                $data['extData'] = $extData->get();

            } elseif($user['user_type'] == 'doctor') {
                    
                $extData = DB::table('tbl_appointments_booked')
                    ->where('doctor_id', $user['id'])
                    ->where('start', '<', now()) // using now() for current timestamp
                    ->whereIn('status', ['Completed', 'Confirmed']) // Add status condition
                    ->orderBy('start', 'desc');
                $data['extData'] = $extData->get();

            }

            return response()->json([           
                'result' => $data,        
                'debug_user'=> $user
            ]);

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function open_message(Request $request)
    {
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if ($request->isMethod('post') && $request->appointment_id > 0) 
            {
                if($request->doctor_id && $request->doctor_id != ''){
                    $validatedData = $request->validate([
                        'doctor_id'  => ['required'],
                        'appointment_id'    => ['required'],
                    ]);
                }elseif($request->patient_id && $request->patient_id != ''){
                    $validatedData = $request->validate([
                        'patient_id'  => ['required'],
                        'appointment_id'    => ['required'],
                    ]);
                } else {
                    $validatedData = $request->validate([
                        'doctor_id'  => ['required'],
                        'patient_id'  => ['required'],
                        'appointment_id'    => ['required'],
                    ]);
                }

                $doctorId = $request->doctor_id;
                $patientId = $request->patient_id;
                $appointmentId = $request->appointment_id;
                
                if($request->from_msg && $request->from_msg != ''){
                    $from_msg = $request->from_msg;
                } else {
                    $from_msg = 0;
                }
                
                $appointment = Schedule::find($appointmentId); 
                
                $res = DB::table('tbl_appointments_booked')
                    ->where('id', $appointmentId)
                    ->update(["msg_flag"=>1]);

                if(!empty($doctorId))
                {
                    $sender_id = $doctorId;

                    $chat = Chat::firstOrCreate([
                        'doctor_id' => $doctorId,
                        'patient_id' => $user["id"]
                    ]);
                }

                if(!empty($patientId))
                {
                    $sender_id = $patientId;

                    $chat = Chat::firstOrCreate([
                        'doctor_id' => $user["id"],
                        'patient_id' => $patientId
                    ]);
                }

                $res = DB::table('messages')
                    ->where('chat_id', $chat->id)
                    ->where('sender_id', $sender_id)
                    ->update(["msg_flag"=>1]);

                $data['chatId'] = Crypt::encrypt($chat->id);
                $data['appointmentId'] = Crypt::encrypt($appointmentId);
                $data['from_msg'] = $from_msg;

                return response()->json([           
                    'result' => $data,        
                    'debug_user'=> $user
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->errors(),  // This will return the validation errors
                ], 400); // You can adjust the error code here if needed
            }

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function ShowChat(Request $request)
    {
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if ($request->isMethod('post') && $request->appointmentId > 0) 
            {
                $validatedData = $request->validate([
                    'chatId'  => ['required'],
                    'appointmentId'    => ['required'],
                ]);

                $chatId = Crypt::decrypt($request->chatId);
                $appointmentId = Crypt::decrypt($request->appointmentId);
                // Fetch the chat session details
                $chat = Chat::with(['doctor', 'patient'])->findOrFail($chatId); 
                $appointment = Schedule::find($appointmentId);        
                $endDateTime = new \DateTime($appointment->end);
                $currentDateTime = new \DateTime();
                $interval = $currentDateTime->diff($endDateTime);        
                $isWithin24Hours = ($interval->days == 0 && $interval->h < 24);

                
                if ($user["id"] !== $chat->doctor_id && $user["id"] !== $chat->patient_id) {
                    abort(403, 'Unauthorized access to this chat.');
                }
                
                if($user["user_type"] == "patient"){
                    $opp_user_data = dbl_users::find($chat->doctor_id);  
                }                

                if($user["user_type"] == "doctor"){
                    $opp_user_data = dbl_users::find($chat->patient_id); 
                }

                if($chatId != '' && $appointmentId != ''){
                    $updateFlag['msg_flag'] = 1;
                    $res = DB::table('messages')
                        ->where('chat_id', $chatId)
                        ->where('app_id', $appointmentId)
                        ->where('sender_id', $opp_user_data['id'])
                        ->update($updateFlag);
                }

                // Pass the chat session data to the view        
                
                $data['chat_id'] = Crypt::encrypt($chatId);
                $data['chat'] = $chat;
                $data['isWithin24Hours'] = $isWithin24Hours;
                $data['user_data'] = $user;
                $data['opp_user_data'] = $opp_user_data;
                $data['user_type'] = $user["user_type"];
                $data['appointment'] = $appointment;
                $data['PageName'] = "Messages";            

                return response()->json([           
                    'result' => $data,        
                    'debug_user'=> $user
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->errors(),  // This will return the validation errors
                ], 400); // You can adjust the error code here if needed
            }

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function notifications(Request $request){
        $data['PageName'] = 'Notifications';
        $data['icon'] = 'Vector(31).png';
        
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if($user != null && $user['id'] != ''){
                $res = DB::table('tbl_notifications')
                        ->where('user_id', $user['id'])
                        ->update(["red_flag"=>1]);

                // Convert raw SQL to query builder
                $query = DB::table('tbl_notifications')
                    ->where('user_id', $user['id'])
                    ->where('received_by', $user['user_type'])
                    ->where('status', 1)
                    ->orderBy('id', 'desc');
                $data['NotificationData'] = $query->get();  // You can change this logic if needed
            }

            return response()->json([           
                'result' => $data,        
                'debug_user'=> $user
            ]);

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function delete_notification(Request $request)
    {
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }
        
        try {
            if ($request->isMethod('post')){

                $validatedData = $request->validate([
                    'id' => ['required'],
                ]);
        
                $data = [];

                $id = $request->id;

                if ($id && $id != '') {
                    $UpdatedData['status'] = 0;
                    $res = DB::table('tbl_notifications')
                        ->where('id', $id)
                        ->update($UpdatedData);
                    if($res){
                        return response()->json([           
                            'result' => $res,        
                            'debug_user'=> $user
                        ]);
                    }
                }
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function patient_report_reviews(Request $request)
    {   
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if(!empty($user)){       
                $appointments_booked = Schedule::with(['doctor', 'patient']) // Eager load doctor (User model)
                    ->where('active', 1)
                    ->where('patient_id', $user['id'])
                    ->where('appointmentType', 'Report Review')
                    ->orderBy('created_at','DESC')
                    ->get([
                        'id', 'patient_id', 'start', 'end', 
                        'doctor_id', 'symptoms', 'reports', 'description', 'appointmentType',
                        'status as appointment_status', 'category', 'amount', 'interests', 
                        'report_file_names', 'medicalDocuments',
                        'notes', 'city', 'state', 'created_at'
                    ]);

                $appointments_booked->load('reportReviewsReplies');
    
                $data["ReportReviewData"] =  $appointments_booked;
            }

            return response()->json([           
                'result' => $data,        
                'debug_user'=> $user
            ]);

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function patient_payments_history(Request $request)
    {  
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if(!empty($user)){  
                $data['payments'] = DB::table('tbl_payments')
                    ->join('dbl_users', 'tbl_payments.patient_id', '=', 'dbl_users.id')
                    ->leftJoin('tbl_plans', 'tbl_payments.plan_id', '=', 'tbl_plans.id')
                    ->select(
                        'tbl_payments.*', 
                        'dbl_users.first_name', 
                        'dbl_users.last_name', 
                        'tbl_plans.plan_type'
                    )
                    ->where('patient_id',$user->id)
                    ->orderBy('id','DESC')
                    ->get();
                }

                return response()->json([           
                    'result' => $data,        
                    'debug_user'=> $user
                ]);
    
            } catch (ValidationException $e) {
                // Return custom error response with error code 400
                return response()->json([
                    'status' => 'error',
                    'message' => $e->errors(),  // This will return the validation errors
                ], 400); // You can adjust the error code here if needed
            }
    }

    public function ShowPayment(Request $request)
    {
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if ($request->isMethod('post')) 
            {
                $validatedData = $request->validate([
                    'id'  => ['required'],
                ]);

                $id = Crypt::decrypt($request->id);
                $data = [];
                        
                $data['PageName'] = 'Payment Details';   
                $data['icon'] = 'Group 9954.png';        

                $payment = DB::table('tbl_payments')
                    ->join('dbl_users', 'tbl_payments.patient_id', '=', 'dbl_users.id')
                    ->leftJoin('tbl_plans', 'tbl_payments.plan_id', '=', 'tbl_plans.id')
                    ->select(
                        'tbl_payments.*', 
                        'dbl_users.first_name', 
                        'dbl_users.last_name', 
                        'tbl_plans.plan_type'
                    )
                    ->where('tbl_payments.id', $id)
                    ->first();
            
                if (!$payment) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Payment not found.',
                    ], 400);
                }

                $data['payment'] = $payment;

                return response()->json([           
                    'result' => $data,        
                    'debug_user'=> $user
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->errors(),  // This will return the validation errors
                ], 400); // You can adjust the error code here if needed
            }

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function ResetPassword(Request $request){
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {  
            if ($request->isMethod('post')) 
            {
                $validatedData = $request->validate([
                    'old_password' => ['required'],
                    'password' => ['required'],
                    'confirm_password' => ['required']
                ]);

                if($request->old_password && $request->old_password != ''){
                    $old_password = md5($request->old_password);
                    $user_id = $user['id'];

                    $check_password = dbl_users::where('id', $user_id)
                        ->where('password', $old_password)  // Add more conditions here
                        ->first();
                    
                    if($check_password){
                        $UserData['password'] = md5($request->password);

                        $res = DB::table('dbl_users')
                            ->where('id', $user_id)
                            ->update($UserData);

                        return response()->json([           
                            'result' => $res, 
                            'msg'    => 'Password Updated Successfully!',      
                            'debug_user'=> $user
                        ]);
                    } else {
                        return response()->json([           
                            'result' => 0,  
                            'msg'    => 'Old password does not match!',     
                            'debug_user'=> $user
                        ]);
                    }
                }
            }

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function UpdaterProfile(Request $request)
    {
        $token = $request->bearerToken();
        
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try {
            if ($request->isMethod('post')) 
            {
                $data = [];
                
                // Validate the incoming request data
                $validatedData = $request->validate([
                    'name' => 'required|string|max:255',
                    'gender' => 'nullable|in:male,female,other',
                    'age' => 'nullable|integer|min:0|max:99',
                    'email_address' => 'required|email|max:255',
                    'phone_number' => 'required',
                    'alternateContactNumber' => 'nullable',
                    'state' => 'required|string|max:2',
                    'city' => 'required|string|max:255',
                    'speciality' => 'nullable',
                    'experience' => 'nullable',
                    'degree' => 'nullable',
                    'about' => 'nullable',
                    'allergies' => 'nullable',
                    'MedicalHistory' => 'nullable',
                    'profile_photo.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
                    'medicalDocuments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
                ], [
                    'name.required' => 'Name is required',
                    'email_address.required' => 'Email is required',
                    'phone_number.required' => 'Contact number is required',
                    'state.required' => 'State is required',
                    'city.required' => 'City is required',
                ]);

                if (isset($_FILES['profile_photo']) && !empty($_FILES['profile_photo']['name'][0]))
                {
                    $fileNames = [];
                    $errors = [];
        
                        foreach ($_FILES['profile_photo']['name'] as $key => $name) {
                            $tmpName = $_FILES['profile_photo']['tmp_name'][$key];
                            $size = $_FILES['profile_photo']['size'][$key];
                            $error = $_FILES['profile_photo']['error'][$key];
                            
                            // Validate file
                            if ($error === UPLOAD_ERR_OK) {
                                // Specify allowed file types and size limit (e.g., 2MB)
                                $allowedTypes = ['pdf','jpeg','jpg','png'];
                                $maxSize = 4 * 1024 * 1024; // 2MB
                                $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                
                                $new_name = uniqid().".".$fileExt;
                                
                                if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                                    // Move uploaded file to the 'uploads' directory
                                    if($user['user_type'] === 'patient'){
                                        $filePath = 'public/patient_photos/' . $new_name;
                                    } else {
                                        $filePath = 'public/doctor_photos/' . $new_name;
                                    }
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
                                $errors[] = "Error uploading file $name. Error code: $error.";
                            }
                        }
                        
                        $profile_file_names = implode(",",$fileNames);
                        
                        $validatedData['profile_photo'] = $profile_file_names;
                        
                        
                        if (!empty($errors)) {
                            echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                            die;
                        } else {
                            // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                            // die;
                        }
                } else {
                    
                }

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
                                $errors[] = "Error uploading file $name. Error code: $error.";
                            }
                        }
                        
                        $report_file_names = implode(",",$fileNames);
                        
                        $validatedData['medicalDocuments'] = $report_file_names;
                        
                        
                        if (!empty($errors)) {
                            echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                            die;
                        } else {
                            // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                            // die;
                        }
                } else {
                    
                }

                $stateId = $request->state;

                $state = UsState::find($stateId);

                $stateName = $state ? $state->STATE_NAME : null; 

                // Calculate timezone based on state (dummy implementation, customize as needed)
                $timezone = $this->getTimezoneByState($stateName); 
                
                $full_name = $request->name;
                
                if(!empty($full_name)){
                    $ImpFullName = explode(' ', $full_name);
                    $validatedData['first_name'] = $ImpFullName[0];
                    $validatedData['last_name'] = $ImpFullName[1];
                }
                
                $validatedData['timezone'] = $timezone;
                
                unset($validatedData['name']);
                
                $res = DB::table('dbl_users')
                    ->where('id', $user['id'])
                    ->update($validatedData);

                $user = dbl_users::where('id', $user['id'])->first();   
                
                return response()->json([           
                    'result' => $res,        
                    'debug_user'=> $user
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $e->errors(),
                ], 400);
            }

        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }
}