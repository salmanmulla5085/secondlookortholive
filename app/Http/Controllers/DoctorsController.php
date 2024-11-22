<?php

namespace App\Http\Controllers;


use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\dbl_users;
use App\Models\UsState;
use App\Models\UsCity;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule as SchedulingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Services\SendGridService;


class DoctorsController extends Controller
{
    protected $sendGridService;

    public function __construct(SendGridService $sendGridService)
    {
        $this->sendGridService = $sendGridService;
    }


    /**
     * 
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view("admin_doctors");
    }

 

    public function admin_patient()
    {
        return view("admin_patients");
    }

    public function create(Request $request)
    {   
        if ($request->isMethod('post')) 
        {   
            if($request->ExtDoctorId && !empty($request->ExtDoctorId)){
                $ExtDoctorId = $request->ExtDoctorId;
                $validatedData = $request->validate([
                    'first_name' => ['required'],
                    'last_name' => ['required'],
                    'email_address' => ['required', 'string', 'email','max:255',
                            function ($attribute, $value, $fail) use ($ExtDoctorId) {
                                if (\App\Models\dbl_users::where('email_address', $value)->where('id', '!=', $ExtDoctorId)->exists()) {
                                    $fail('This email already exists.');
                                }
                            },
                        ],
                    'phone_number' => ['required'],
                    'state' => ['required'],
                    'city' => ['required']
                ]); 
            } else {
                $validatedData = $request->validate([
                    'first_name' => ['required'],
                    'last_name' => ['required'],
                    'email_address' => ['required', 'string', 'email','max:255',
                            function ($attribute, $value, $fail) {
                                if (\App\Models\dbl_users::where('email_address', $value)->exists()) {
                                    $fail('This email already exists.');
                                }
                            },
                        ],
                    'phone_number' => ['required'],
                    'state' => ['required'],
                    'city' => ['required']
                ]);
            }

            $NewData['user_type'] = 'doctor';
            $NewData['first_name'] = $request->first_name;
            $NewData['last_name'] = $request->last_name;
            $NewData['email_address'] = $request->email_address;
            $NewData['status'] = 'Active';
            $NewData['phone_number'] = $request->phone_number;  

            if($request->password && !empty($request->password)){
                $NewData['password'] = md5($request->password);
                $NewData['view_password'] = $request->password;        
                // $NewData['view_password'] = $request->password;
            }     

            $NewData['experience'] = $request->experience;
            $NewData['degree'] = $request->degree;
            $NewData['speciality'] = $request->speciality;
            $NewData['about'] = $request->about;

            if(!empty($request->admin)){
            $NewData['admin'] = 1;
            }else{
                $NewData['admin'] = 0;
            }


            

            $stateId = $request->state;
            $cityId =  $request->city;
            $state = UsState::find($stateId);
            $city = UsCity::find($cityId);                   
            $stateName = $state ? $state->ID : null;
            $cityName = $city ? $city->ID : null;           

            // Calculate timezone based on state (dummy implementation, customize as needed)
            $NewData['timezone'] = $this->getTimezoneByState($stateName);           
            $NewData['state'] = $stateName;
            $NewData['city'] = $cityName;        
            
             if (isset($_FILES['profile_photo']) && !empty($_FILES['profile_photo']['name']))
                    {
                        $fileNames = "";
                        $errors = [];
            
                            $name = $_FILES['profile_photo']['name'];
                            
                                $tmpName = $_FILES['profile_photo']['tmp_name'];
                                $size = $_FILES['profile_photo']['size'];
                                $error = $_FILES['profile_photo']['error'];
                                
                                // Validate file
                                if ($error === UPLOAD_ERR_OK) {
                                    // Specify allowed file types and size limit (e.g., 2MB)
                                    $allowedTypes = ['jpg', 'png', 'jpeg'];
                                    $maxSize = 2 * 1024 * 1024; // 2MB
                                    $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                    
                                    $new_name = uniqid().".".$fileExt;
                                    
                                    if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                                        // Move uploaded file to the 'uploads' directory
                                        $filePath = 'public/doctor_photos/' . $new_name;
                                        if (move_uploaded_file($tmpName, $filePath)) {
                                            // Save file information to the database
                                            
                                            $fileNames = $new_name;
                                            $NewData['profile_photo'] = $fileNames;
                                            
                                        } else {
                                            $errors[] = "Failed to move file $name.";
                                        }
                                    } else {
                                        
                                        $errors[] = "Invalid file type or size for file $name.";
                                    }
                                }
                                
                                
                        }
                            
                            
                            
                            
                            
                            if (!empty($errors)) {
                                echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                                return redirect()->back()->withErrors($errors)->withInput();
                            } else {
                                // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                                // die;
                            }
            
            
            // dd($item->profile_photo);        
            // echo $item->profile_photo;

            if($request->ExtDoctorId && !empty($request->ExtDoctorId)){
                $ExtDoctorId = $request->ExtDoctorId;
                $res = DB::table('dbl_users')
                    ->where('id', $ExtDoctorId)
                    ->update($NewData);
            } else {
                $user = dbl_users::create($NewData);

                SendEmail($this->sendGridService, $user->email_address, 
                'Welcome to Secondlook Ortho', 'emails.doctoradd-email-template', null,$user);
                // dd($dbl_users);
            }
        }

        return redirect('/admin_doctors');
    }

    // public function create(Request $request)
    // {
    //     $item = new dbl_users();
    //     $item->user_type = 'doctor';
    //     $item->first_name = $request->first_name;
    //     $item->last_name = $request->last_name;
    //     $item->email_address = $request->email_address;
    //     $item->phone_number = $request->phone_number;
    //     $item->status = 'Active';
    //     $item->phone_number = $request->phone_number;
    //     $item->password = md5($request->password);

    //     // print_r($_FILES['profile_photo']);

    //     if (isset($_FILES['profile_photo']) && !empty($_FILES['profile_photo']['name'])) {
    //         $fileNames = "";
    //         $errors = [];

    //         $name = $_FILES['profile_photo']['name'];

    //         $tmpName = $_FILES['profile_photo']['tmp_name'];
    //         $size = $_FILES['profile_photo']['size'];
    //         $error = $_FILES['profile_photo']['error'];

    //         // Validate file
    //         if ($error === UPLOAD_ERR_OK) {
    //             // Specify allowed file types and size limit (e.g., 2MB)
    //             $allowedTypes = ['jpg', 'png'];
    //             $maxSize = 2 * 1024 * 1024; // 2MB
    //             $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));

    //             $new_name = uniqid() . "." . $fileExt;

    //             if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
    //                 // Move uploaded file to the 'uploads' directory
    //                 $filePath = 'public/doctor_photos/' . $new_name;
    //                 if (move_uploaded_file($tmpName, $filePath)) {
    //                     // Save file information to the database

    //                     $fileNames = $new_name;
    //                     $item->profile_photo = $fileNames;
    //                 } else {
    //                     $errors[] = "Failed to move file $name.";
    //                 }
    //             } else {

    //                 $errors[] = "Invalid file type or size for file $name.";
    //             }
    //         }
    //     }





    //     if (!empty($errors)) {
    //         echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
    //         die;
    //     } else {
    //         // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
    //         // die;
    //     }


    //     // dd($item->profile_photo);

    //     // echo $item->profile_photo;

    //     $item->save();

    //     return redirect('/admin_doctors');
    // }

    public function create_patient(Request $request)
    {   
        if ($request->isMethod('post')) 
        {   
            if($request->ExtPatientId && !empty($request->ExtPatientId)){
                $ExtPatientId = $request->ExtPatientId;
                $validatedData = $request->validate([
                    'first_name' => ['required'],
                    'last_name' => ['required'],
                    'email_address' => ['required', 'string', 'email','max:255',
                            function ($attribute, $value, $fail) use ($ExtPatientId) {
                                if (\App\Models\dbl_users::where('email_address', $value)->where('id', '!=', $ExtPatientId)->exists()) {
                                    $fail('This email already exists.');
                                }
                            },
                        ],
                    'phone_number' => ['required'],
                    'state' => ['required'],
                    'city' => ['required']
                ]); 
            }

            $NewData['user_type'] = 'patient';
            $NewData['first_name'] = $request->first_name;
            $NewData['last_name'] = $request->last_name;
            $NewData['email_address'] = $request->email_address;
            $NewData['status'] = 'Active';
            $NewData['phone_number'] = $request->phone_number;  

            if($request->password && !empty($request->password)){
                $NewData['password'] = md5($request->password);
                $NewData['view_password'] = $request->password;
            } 

            $stateId = $request->state;
            $cityId =  $request->city;
            $state = UsState::find($stateId);
            $city = UsCity::find($cityId);                   
            $stateName = $state ? $state->ID : null;
            $cityName = $city ? $city->ID : null;           

            // Calculate timezone based on state (dummy implementation, customize as needed)
            $NewData['timezone'] = $this->getTimezoneByState($stateName);           
            $NewData['state'] = $stateName;
            $NewData['city'] = $cityName;        
            
            if (isset($_FILES['profile_photo']) && !empty($_FILES['profile_photo']['name'])){
                $fileNames = "";
                $errors = [];
    
                $name = $_FILES['profile_photo']['name'];
                    
                $tmpName = $_FILES['profile_photo']['tmp_name'];
                $size = $_FILES['profile_photo']['size'];
                $error = $_FILES['profile_photo']['error'];
                            
                // Validate file
                if ($error === UPLOAD_ERR_OK) {
                    // Specify allowed file types and size limit (e.g., 2MB)
                    $allowedTypes = ['jpg', 'png', 'jpeg'];
                    $maxSize = 2 * 1024 * 1024; // 2MB
                    $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    
                    $new_name = uniqid().".".$fileExt;
                    
                    if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                        // Move uploaded file to the 'uploads' directory
                        $filePath = 'public/patient_photos/' . $new_name;
                        if (move_uploaded_file($tmpName, $filePath)) {
                            // Save file information to the database
                            
                            $fileNames = $new_name;
                            $NewData['profile_photo'] = $fileNames;
                            
                        } else {
                            $errors[] = "Failed to move file $name.";
                        }
                    } else {
                        
                        $errors[] = "Invalid file type or size for file $name.";
                    }
                } 
            }
            if (!empty($errors)) {
                echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                return redirect()->back()->withErrors($errors)->withInput();
            } else {
            }

            if($request->ExtPatientId && !empty($request->ExtPatientId)){
                $ExtPatientId = $request->ExtPatientId;
                $res = DB::table('dbl_users')
                    ->where('id', $ExtPatientId)
                    ->update($NewData);
            }
        }

        return redirect('/admin_patient');
    }

    public function doctor_status($doctor_id = null, $status = null){
        if($doctor_id != null && $doctor_id > 0 ){
            if($status == 0){
                $UpdatedData['status'] = 'Inactive';
                $msg = 'Doctor is inactive';
            } else {
                $UpdatedData['status'] = 'Active';
                $msg = 'Doctor is active.';
            }

            $res = DB::table('dbl_users')
                                ->where('id', $doctor_id)
                                ->update($UpdatedData);       
      
        return redirect('/admin_doctors')->with('success', $msg);
        }
    }

    public function patient_status($patient_id = null, $status = null){
        if($patient_id != null && $patient_id > 0 ){
            if($status == 0){
                $UpdatedData['status'] = 'Inactive';
                $msg = 'Patient is inactive';
            } else {
                $UpdatedData['status'] = 'Active';
                $msg = 'Patient is active.';
            }

            $res = DB::table('dbl_users')
                                ->where('id', $patient_id)
                                ->update($UpdatedData);       
      
        return redirect('/admin_patient')->with('success', $msg);
        }
    }

    public function view_doctor($doctor_id = null, $operation = null){

        $data['title'] = $operation;

        if($doctor_id != null && $doctor_id > 0){
            $user_sql = "SELECT * FROM dbl_users where id = $doctor_id";
            $UserSQL = DB::select($user_sql);
            $data['UserData'] = collect($UserSQL);
        }

        return view("doctor.view", $data);
    }

    public function view_patient($patient_id = null, $operation = null){

        $data['title'] = $operation;

        if($patient_id != null && $patient_id > 0){
            $user_sql = "SELECT * FROM dbl_users where id = $patient_id";
            $UserSQL = DB::select($user_sql);
            $data['UserData'] = collect($UserSQL);
        }

        return view("patient.view", $data);
    }

    public function delete_doctor($doctor_id = null){
        if($doctor_id != null && $doctor_id > 0 ){

            $UpdatedData['status'] = 'Deleted';

            $user = DB::table('dbl_users')
                                ->where('id', $doctor_id)
                                ->update($UpdatedData); 
      
        return redirect('/admin_doctors')->with('success', 'Doctor deleted successfully');
        }
    }

    public function delete_patient($patient_id = null){
        if($patient_id != null && $patient_id > 0 ){
            
            $UpdatedData['status'] = 'Deleted';

            $user = DB::table('dbl_users')
                                ->where('id', $patient_id)
                                ->update($UpdatedData); 
      
        return redirect('/admin_patients')->with('success', 'Patient deleted successfully');
        }
    }
}
