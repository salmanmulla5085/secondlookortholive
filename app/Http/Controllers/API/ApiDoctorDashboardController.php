<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\dbl_users;
use App\Models\Schedule;
use App\Models\User;
use App\Models\ReportReviewsReplies;
use App\Models\AvailableScheduleSlots;
use Carbon\Carbon;
use App\Services\AppointmentService;
use App\Services\SendGridService;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class ApiDoctorDashboardController extends Controller
{
    protected $appointmentService;
    protected $sendGridService;
    public function __construct(AppointmentService $appointmentService, SendGridService $sendGridService)
    {
        $this->appointmentService = $appointmentService;
        $this->sendGridService = $sendGridService;
    }


    public function doctor_dashboard(Request $request)
    {
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $data = [];
        $data['PageName'] = 'Doctor Dashboard';
        $data['icon'] = 'Group(40).png';

        try {
            if (!empty($user)) {

                $validatedData = $request->validate([
                    'record_type' => ['required']
                ]);

                if($request->record_type == ''){
                    $record_type = 'todays';
                } else {
                    $record_type = $request->record_type;
                }

                $sql = "SELECT * FROM tbl_appointments_booked where doctor_id = $user->id AND `status` != 'Cancelled'";

                if ($record_type == "new") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                    ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                    LEFT JOIN dbl_users u ON ab.doctor_id = u.id 
                    where ab.active = 1 AND ab.status = 'In-Process' AND ab.doctor_id = $user->id 
                    AND ab.start > NOW() order by start desc";
                } elseif ($record_type == "past") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                    ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                    LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
                    ab.active = 1 AND ab.status != 'Cancelled' AND ab.status != 'Rejected' 
                    AND ab.doctor_id = $user->id AND ab.start <' " . date('Y-m-d H:i:s') . "' order by start desc";
                    // echo'<pre>';print_r($sql);die;
                } elseif ($record_type == "todays") {

                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                    ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                    LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
                    ab.active = 1 AND ab.status = 'Confirmed'  
                    AND ab.doctor_id = $user->id 
                    AND DATE(ab.start) ='" . date('Y-m-d') . "' AND ab.start >'" . date('Y-m-d H:i:s') . "' order by start desc";
                } elseif ($record_type == "rejected") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                    ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                    LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.doctor_id = $user->id AND (ab.status ='Rejected' OR ab.status ='Cancelled') order by start desc";
                } elseif ($record_type == "upcoming") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                    ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                    LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.doctor_id = $user->id AND ab.status = 'Confirmed' AND DATE(ab.start) > '" . date('Y-m-d') . "' order by start desc";
                }

                $appointments_booked = DB::select($sql);
                $appointments_booked = collect($appointments_booked);

                $data["appointments"] =  $appointments_booked;

                $RedFlagSql = "SELECT ab.id FROM tbl_appointments_booked ab
                LEFT JOIN dbl_users u ON ab.doctor_id = u.id 
                where ab.active = 1 AND ab.status = 'In-Process' AND ab.doctor_id = $user->id 
                AND ab.start > NOW() order by start desc";

                $CheckRedFlag = DB::select($RedFlagSql);
                $CheckRedFlag = collect($CheckRedFlag);
                $data['CheckRedFlag'] = $CheckRedFlag;
            }

            return response()->json([
                'record_type' => $record_type,
                'result' => $data,
                'debug_user' => $user
            ]);
        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    //Get Patient History
    public function patient_history(Request $request)
    {
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $data = [];
        $data["AppBooked"] = $data["TotalAppBooked"] = $data['TotalSymtoms'] = $data['TotalAllergies'] = $data['TotalMedicalHis'] = '';
        $data['PageName'] = 'Medical History';

        try {
            if (!empty($user)) {

                $validatedData = $request->validate([
                    'app_id' => ['required'],
                    'patient_id' => ['required'],
                    'record_type' => ['required']
                ]);

                $record_type = $request->record_type;
                $app_id = $request->app_id;
                $patient_id = $request->patient_id;

                if($app_id && !empty($app_id)){
                    $app_id = Crypt::decrypt($app_id);
                    $AppSql = "SELECT ab.id,ab.patient_id, ab.status, ab.start,ab.end, ab.doctor_id, ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments, ab.upload_file1,ab.notes,
                    u.first_name AS patient_first_name, u.last_name AS patient_last_name, u.phone_number as patient_phone_number, u.profile_photo FROM tbl_appointments_booked ab LEFT JOIN dbl_users u ON ab.patient_id = u.id where ab.id = $app_id";
                    $AppBooked = DB::select($AppSql);
                    $data["AppBooked"] = collect($AppBooked);
                }

                if($patient_id && !empty($patient_id)){
                    $patient_id = Crypt::decrypt($patient_id);
                    $TotalAppSql = "SELECT ab.id,ab.patient_id, ab.status, ab.start,ab.end, ab.doctor_id , ab.symptoms, u.allergies, u.MedicalHistory, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments, ab.upload_file1,ab.notes,
                    u.first_name AS doctor_first_name, u.last_name AS doctor_last_name, u.phone_number as doctor_phone_number FROM tbl_appointments_booked ab
                    LEFT JOIN dbl_users u ON ab.patient_id = u.id where ab.active = 1 AND ab.patient_id = $patient_id AND ab.status='Completed' AND ab.start <' ".date('Y-m-d H:i:s')."' order by ab.start DESC";
                    $TotalAppBooked = DB::select($TotalAppSql);
                    $data["TotalAppBooked"] = collect($TotalAppBooked);

                    $PatientSql = "SELECT * FROM dbl_users where id = $patient_id";
                    $PatientUserSql = DB::select($PatientSql);
                    $PatientUserData = collect($PatientUserSql);

                    if(count($PatientUserData) > 0 && !empty($PatientUserData[0]->allergies)){
                        $data['TotalAllergies'] = $PatientUserData[0]->allergies;
                    }

                    if(count($PatientUserData) > 0 && !empty($PatientUserData[0]->MedicalHistory)){
                        $data['TotalMedicalHis'] = $PatientUserData[0]->MedicalHistory;
                    }
                }

                if($TotalAppBooked && $TotalAppBooked > 0){
                    $TotalSymtoms = [];
                    $TotalAllergies = [];
                    $TotalMedicalHis = [];
                    foreach ($TotalAppBooked as $key => $AppVal) {
                        $symtoms = $AppVal->symptoms;
                        $allergies = $AppVal->allergies;
                        $MedicalHistory = $AppVal->MedicalHistory;

                        if(!empty($symtoms)){
                            if(!in_array($symtoms, $TotalSymtoms)){
                                array_push($TotalSymtoms, $symtoms);
                            }
                        }

                        if(!empty($allergies)){
                            if(!in_array($allergies, $TotalAllergies)){
                                array_push($TotalAllergies, $allergies);
                            }
                        }

                        if(!empty($MedicalHistory)){
                            if(!in_array($MedicalHistory, $TotalMedicalHis)){
                                array_push($TotalMedicalHis, $MedicalHistory);
                            }
                        }
                    }

                    if($TotalSymtoms){
                        $data['TotalSymtoms'] = implode(',', $TotalSymtoms);
                        // Remove unnecessary spaces around commas
                        $data['TotalSymtoms'] = preg_replace('/\s*,\s*/', ', ', $data['TotalSymtoms']);
                    }
                }
                return response()->json([
                    'record_type' => $record_type,
                    'result' => $data,
                    'debug_user' => $user
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

    //start date, end date and status filter on aapointment in dashboard
    public function FilterOnDoctorDashboard(Request $request)
    {
        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $AppData = null;
        $data = [];
        $data['PageName'] = 'Doctor Dashboard';
        $data['icon'] = 'Group(40).png'; 

        try{
            if ($request->isMethod('post')) {
                $request->validate([
                    'record_type' => 'required',
                    'start' => 'required',
                    'end' => 'required'
                ]);

                $data["record_type"] = $request->record_type;

                $startDate = Carbon::createFromFormat('m-d-Y', $request->start)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('m-d-Y', $request->end)->format('Y-m-d');
                
                $data['start'] = $request->start ;
                $data['end'] = $request->end;
                $data['status'] = $request->status;

                $sql = "SELECT * FROM tbl_appointments_booked where doctor_id = $user->id AND `status` != 'Cancelled'";
                $query = '';

                $query = 'AND DATE(ab.start) >= "'. $startDate.'" AND DATE(ab.start) <= "'. $endDate.'"';

                if($request->status && $request->status != ''){
                    $query .= ' AND ab.status = "'. $request->status.'"';
                }

                if ($request->record_type == "new") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id 
                        where ab.active = 1 AND ab.doctor_id = $user->id 
                        AND ab.start > NOW() ".$query." order by start desc";
                } elseif ($request->record_type == "past") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
                        ab.active = 1 AND ab.status != 'Cancelled' AND ab.status != 'Rejected' 
                        AND ab.doctor_id = $user->id ".$query." AND ab.end <' " . date('Y-m-d H:i:s') . "' order by start desc";
                } elseif ($request->record_type == "todays") {
                // Added this query by darshan 18-09-2024
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
                        ab.active = 1 AND ab.status = 'Confirmed'  
                        AND ab.doctor_id = $user->id ".$query." 
                        AND DATE(ab.start) ='".date('Y-m-d')."' AND ab.start >'" . date('Y-m-d H:i:s') . "' order by start desc";
                } elseif ($request->record_type == "rejected") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.doctor_id = $user->id ".$query." AND (ab.status ='Rejected' OR ab.status ='Cancelled') order by start desc";
                        } elseif ($request->record_type == "upcoming") {
                            $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.doctor_id = $user->id ".$query." AND ab.status = 'Confirmed' AND ab.start > '".date('Y-m-d H:i:s')."' order by start desc";
                }

                $appointments_booked = DB::select($sql);
                $appointments_booked = collect($appointments_booked);
                $data["appointments_booked"] =  $appointments_booked;

                $RedFlagSql = "SELECT ab.id FROM tbl_appointments_booked ab
                    LEFT JOIN dbl_users u ON ab.doctor_id = u.id 
                    where ab.active = 1 AND ab.status = 'In-Process' AND ab.doctor_id = $user->id 
                    AND ab.start > NOW() order by start desc";
            
                $CheckRedFlag = DB::select($RedFlagSql);
                $CheckRedFlag = collect($CheckRedFlag);
                $data['CheckRedFlag'] = $CheckRedFlag;

                return response()->json([
                    'result' => $data,
                    'debug_user' => $user
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This is post method',
                ], 400);
            }
            
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function not_confirmed_appintments(Request $request)
    {

        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $data = [];

        $data['PageName'] = 'Not Confirmed Appointments';
        $data['icon'] = 'Group 9956.png';

        if (!empty($user)) {
            try {
                $sql = "SELECT ab.completed_at,ab.upload_file1,ab.id,ab.doctor_id, ab.patient_id, ab.start,ab.end, 
                    ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                    ab.name AS patient_name, ab.NotConfirmed, u.phone_number as patient_phone_number,
                    ab.city,ab.state
                    FROM tbl_appointments_booked ab
                    LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 
                    AND ab.status = 'In-Process' AND ab.NotConfirmed = '1' AND ab.start >= '" . date('Y-m-d H:i:s') . "' order by start ASC";

                $appointments_booked = DB::select($sql);
                $appointments_booked = collect($appointments_booked);

                $data["Not_confirmed_appointments"] =  $appointments_booked;

                $sql2 = "SELECT * from dbl_users where user_type='doctor'";
                $doctors = DB::select($sql2);
                $doctors = collect($doctors);

                $data["doctors"] =  $doctors;

                return response()->json([
                    'result' => $data,
                    'debug_user' => $user
                ]);
            } catch (ValidationException $e) {
                // Return custom error response with error code 400
                return response()->json([
                    'status' => 'error',
                    'message' => $e->errors(),  // This will return the validation errors
                ], 400); // You can adjust the error code here if needed
            }
        }
    }

    public function confirm_appointment_v3_post(Request $request)
    {
        $AppData = null;

        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try{
            $not_confirm_appointment = '';
            
            if ($request->isMethod('post')) {

                $validatedData = $request->validate([
                    'confirm_appointment_id' => ['required'],
                    'phone_meeting_link' => ['required'],
                ]);

                $phone_meeting_link = $request->phone_meeting_link;
                $confirm_appointment_id = $request->confirm_appointment_id;

                $Appsql = "SELECT * FROM tbl_appointments_booked where id = $confirm_appointment_id";
                $app_details = DB::select($Appsql);
                $AppData = collect($app_details);

                if ($confirm_appointment_id != null && $phone_meeting_link != null) 
                {   
                    if($request->not_confirm_appointment && $request->not_confirm_appointment != ''){
                        $not_confirm_appointment = $request->not_confirm_appointment;
                    } else {
                        $not_confirm_appointment = '';
                    }
                    
                    if($not_confirm_appointment == '1')
                    {
                        if($AppData && count($AppData) > 0){
                            $res = DB::table('tbl_available_schedule_slots')
                                ->where('id', $AppData[0]->slot_id)
                                ->update(["is_available"=>"1","booked"=>"0"]);

                            $old_doctor_slot = "SELECT * FROM tbl_available_schedule_slots where id = ".$AppData[0]->slot_id;
                            
                            $old_doctor_slot = DB::select($old_doctor_slot);
                            $old_doctor_slot = collect($old_doctor_slot);
                        }
    
                        if($old_doctor_slot->isEmpty()){
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Old slot not found in database.appointment cannot be swaped.',
                            ], 400);
                        }
                                
                        if(!empty($old_doctor_slot[0]->start) &&  !empty($old_doctor_slot[0]->end) && !empty($user->id))
                        {                             
                            $new_doctors_slot_id = "SELECT * FROM tbl_available_schedule_slots 
                            where booked = 0 AND is_available = 1 AND `start` = '".$old_doctor_slot[0]->start."' AND end = '".$old_doctor_slot[0]->end."' AND doctor_id =".$user->id;

                            $new_doctors_slot_id = DB::select($new_doctors_slot_id);
                            $new_doctors_slot_id = collect($new_doctors_slot_id);
                        
                                if($new_doctors_slot_id->isNotEmpty()){
                                    
                                    if(!empty($new_doctors_slot_id[0]->id)){
                                        $Update_data['slot_id'] = $new_doctors_slot_id[0]->id;
                                        $Update_data['status'] = 'Confirmed';                            
                                        $Update_data['doctor_id'] = $user->id;
                                        $Update_data['phone_meeting_link'] = $phone_meeting_link;
                                        $res = DB::table('tbl_appointments_booked')
                                            ->where('id', $confirm_appointment_id)
                                            ->update($Update_data);

                                        // also update timeslot of new doctor booked = 1 is_available = 0
                                        $res = DB::table('tbl_available_schedule_slots')
                                            ->where('id', $new_doctors_slot_id[0]->id)
                                            ->update(["is_available"=>0,"booked"=>1]); 
                                    } else {
                                        $dtime = new DateTime($old_doctor_slot[0]->start);                        
                                        $dtime = $dtime->format("m/d/y H:i");
                                        
                                        return response()->json([
                                            'status' => 'error',
                                            'message' => 'Your slot at DateTime:'.$dtime.' is not found, Please add and try again.',
                                        ], 400);
                                    }
                                } else {                        
                                    $dtime = new DateTime($old_doctor_slot[0]->start);                        
                                    $dtime = $dtime->format("m-d-y H:i");
                                    
                                    return response()->json([
                                        'status' => 'error',
                                        'message' => 'Your slot at DateTime:'.$dtime.' is not found, Please add and try again.',
                                    ], 400);
                                }
                        } else {
                            
                            return response()->json([
                                'status' => 'error',
                                'message' => 'Cannot confirm, Please try again.',
                            ], 400);
                        }
                    } elseif($not_confirm_appointment == ''){
            
                        $Update_data['status'] = 'Confirmed';                            
                        $Update_data['phone_meeting_link'] = $phone_meeting_link;
                        $res = DB::table('tbl_appointments_booked')
                            ->where('id', $confirm_appointment_id)
                            ->update($Update_data);
                    }
                    
                    if($AppData && count($AppData) > 0){
                        $patient = dbl_users::where('id', $AppData[0]->patient_id)->first();
                        $doctor_details = dbl_users::where('id', $AppData[0]->doctor_id)->first();
                    }
            
                    $app_details = Schedule::findOrFail($confirm_appointment_id);

                    $subject = 'Appointment Confirmed Successfully';
                    $opr = 'doctor_confirm';

                    SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-confirm-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $phone_meeting_link, $rec_by='patient');
                    SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-confirm-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $phone_meeting_link, $rec_by='doctor');                
                    sendSms($patient,$doctor_details,$app_details,"Confirm");                

                    if(!empty($not_confirm_appointment) && $not_confirm_appointment == '1'){
                        return response()->json([
                            'status' => 'success',
                            'message' => 'Appointment confirmed successfully!',
                        ], 200);
                    } 

                    if(empty($not_confirm_appointment))
                    {
                        $dateFromAppData = date('Y-m-d', strtotime($AppData[0]->start));
                        $today = date('Y-m-d');

                        if($dateFromAppData == $today)
                        {                        
                            return response()->json([
                                'status' => 'success',
                                'message' => 'Appointment ' . $Update_data['status'] . ' successfully!',
                            ], 200);
                        }

                        if($dateFromAppData > $today)
                        {     
                            return response()->json([
                                'status' => 'success',
                                'message' => 'Appointment ' . $Update_data['status'] . ' successfully!',
                            ], 200);                   
                        }

                        if($dateFromAppData < $today)
                        {   
                            return response()->json([
                                'status' => 'success',
                                'message' => 'Appointment ' . $Update_data['status'] . ' successfully!',
                            ], 200);                      
                        }
                    }
                } else {

                    if($not_confirm_appointment == '') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'appointment id OR phone/link cannot be blank',
                        ], 400);
                    }
                    
                    if($not_confirm_appointment === '1') {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'appointment id OR phone/link cannot be blank',
                        ], 400);
                    }
                }
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Should post method',
                ], 400);
            }
        } catch(\Exception $e){
            echo'<pre>';print_r($e);die;
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    public function changeAppointmentStatus(Request $request)
    {
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }


        try {
            //for form validation
            $validatedData = $request->validate([
                'status'  => ['required'],
                'appointment_id' => ['required'],
                'reason'    => ['required'],
            ]);

            if ($request->isMethod('post')) {

                $status = $request->status;
                $appointment_id = $request->appointment_id;


                $Update_data = [];

                $change_timeslot = 0;

                $AppData = null;

                if ($status == 'confirm') {
                    $Update_data['status'] = 'Confirmed';
                    $type = '';
                } elseif ($status == 'reject') {
                    $Update_data['status'] = 'Rejected';
                    $Update_data['CancelPatientOrDoctor'] = 2;
                    $change_timeslot = 1;
                    $type = 'rejected';
                } elseif ($status == 'cancel') {
                    $Update_data['status'] = 'Cancelled';
                    $Update_data['CancelPatientOrDoctor'] = 2;
                    $change_timeslot = 1;
                    $type = 'rejected';
                } elseif ($status == 'completed') {
                    $Update_data['status'] = 'Completed';
                }



                if ($appointment_id != null) {
                    $Appsql = "SELECT * FROM tbl_appointments_booked where id = $appointment_id";
                    $app_details = DB::select($Appsql);
                    $AppData = collect($app_details);
                }


                //Logic for appointment timeslot become free.
                if ($change_timeslot == 1) {

                    if (!empty($AppData) && count($AppData) > 0) {

                        $slotId = $AppData[0]->slot_id;

                        if ($slotId > 0) {
                            $UpdateSlot['is_available'] = 1;
                            $res = DB::table('tbl_available_schedule_slots')
                                ->where('id', $slotId)
                                ->update($UpdateSlot);
                        }
                    }
                }

                if ($appointment_id != null && !empty($Update_data)) {

                    $res = DB::table('tbl_appointments_booked')
                        ->where('id', $appointment_id)
                        ->update($Update_data);

                    $patient = dbl_users::where('id', $AppData[0]->patient_id)->first();
                    $doctor_details = dbl_users::where('id', $AppData[0]->doctor_id)->first();
                    $app_details = Schedule::findOrFail($appointment_id);

                    if ($status == 'confirm') {
                        $subject = 'Appointment Confirmed Successfully';
                        $opr = 'doctor_confirm';
                        $reason = $request->reason;
                        SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-confirm-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by = 'patient');
                        SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-confirm-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by = 'doctor');
                        sendSms($patient, $doctor_details, $app_details, "Confirm");
                    } else if ($status == 'reject') {
                        $subject = 'Appointment Rejected Successfully';
                        $opr = 'doctor_reject';
                        $reason = $request->reason;
                        SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-reject-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by = 'patient');
                        SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-reject-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by = 'doctor');
                        sendSms($patient, $doctor_details, $app_details, "Rejected");
                    } else if ($status == 'cancel') {
                        $subject = 'Appointment Cancelled Successfully';
                        $opr = 'doctor_cancel';
                        $reason = $request->reason;
                        SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-cancel-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by = 'patient');
                        SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-cancel-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by = 'doctor');
                        sendSms($patient, $doctor_details, $app_details, "Cancelled");
                    }
                    $data['message'] = 'Appointment ' . $Update_data['status'] . ' successfully!';
                } else {
                    return response()->json(['error' => 'Appointment id not found'], 401);
                }
            }
            return response()->json([
                'result' => $data,
                'debug_user' => $user
            ]);
        } catch (ValidationException $e) {
            // Return custom error response with error code 400
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }


    //mark as completed appointment
    public function save_doctor_prescription(Request $request)
    {
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }
       
        $AppData = null;

        try{
            if ($request->isMethod('post')) {
                $validatedData =  $request->validate([
                    'completed_appointment_id' => 'required|exists:tbl_appointments_booked,id',
                    'doctor_prescription' => 'required|string',
                    'upload_file1.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096'
                ]);

                $doctor_prescription = $request->doctor_prescription;
                $completed_appointment_id = $request->completed_appointment_id;

                $item = Schedule::findOrFail($completed_appointment_id);
                $item->notes = Crypt::encrypt($doctor_prescription);
                $item->status = 'Completed';
                $item->completed_at = date("Y-m-d H:i:s");            

                $uploadedFiles = [];
                if (isset($_FILES['upload_file1']) && !empty($_FILES['upload_file1']['name'][0])) {

                    $fileNames = [];
                    $errors = [];

                    foreach ($_FILES['upload_file1']['name'] as $key => $name) {
                        $tmpName = $_FILES['upload_file1']['tmp_name'][$key];
                        $size = $_FILES['upload_file1']['size'][$key];
                        $error = $_FILES['upload_file1']['error'][$key];

                        // Validate file
                        if ($error === UPLOAD_ERR_OK) {
                            // Specify allowed file types and size limit (e.g., 2MB)
                            $allowedTypes = ['pdf', 'jpeg', 'jpg', 'png'];
                            $maxSize = 4 * 1024 * 1024; // 2MB
                            $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                            $new_name = uniqid() . "." . $fileExt;

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

                    $report_file_names = implode(",", $fileNames);

                    $item->upload_file1 = $report_file_names;


                    if (!empty($errors)) {
                        return response()->json(['error' => implode('<br>', $errors) ], 401);
                    } else {
                        // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                        // die;
                    }
                } else {
                }

                $item->save();

                if ($completed_appointment_id != null) {
                    $Appsql = "SELECT * FROM tbl_appointments_booked where id = $completed_appointment_id";
                    $app_details = DB::select($Appsql);
                    $AppData = collect($app_details);
                }

                $patient = dbl_users::where('id', $AppData[0]->patient_id)->first();
                $doctor_details = dbl_users::where('id', $AppData[0]->doctor_id)->first();
                $app_details = Schedule::findOrFail($completed_appointment_id);
                
                $subject = 'Appointment Completed Successfully';
                $opr = 'doctor_completed';
                $reason = $doctor_prescription;
                SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-completed-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='patient');
                SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-completed-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='doctor');
                sendSms($patient,$doctor_details,$app_details,"Completed");                

                // dd($item);\
                $data['message'] = "Appointment completed successfully!";
                return response()->json([
                    'result' => $data,
                    'debug_user' => $user
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        } 
    }

    public function modifyDoctorPrescription(Request $request)
    {
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try{
            if ($request->isMethod('post')) {
                $request->validate([
                    'modify_appointment_id' => 'required|exists:tbl_appointments_booked,id',
                    'modify_doctor_prescription' => 'required|string',
                    'modify_upload_file1.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096'
                ]);

                $doctor_prescription = $request->modify_doctor_prescription;
                $modify_appointment_id = $request->modify_appointment_id;

                $item = Schedule::findOrFail($modify_appointment_id);
                
                if(!empty($doctor_prescription))
                $item->notes = Crypt::encrypt($doctor_prescription);

                $item->status = 'Completed';
                $item->completed_at = date("Y-m-d H:i:s");            

                $uploadedFiles = [];
                if (isset($_FILES['modify_upload_file1']) && !empty($_FILES['modify_upload_file1']['name'][0])) {

                    $fileNames = [];
                    $errors = [];

                    foreach ($_FILES['modify_upload_file1']['name'] as $key => $name) {
                        $tmpName = $_FILES['modify_upload_file1']['tmp_name'][$key];
                        $size = $_FILES['modify_upload_file1']['size'][$key];
                        $error = $_FILES['modify_upload_file1']['error'][$key];

                        // Validate file
                        if ($error === UPLOAD_ERR_OK) {
                            // Specify allowed file types and size limit (e.g., 2MB)
                            $allowedTypes = ['pdf', 'jpeg', 'jpg', 'png'];
                            $maxSize = 4 * 1024 * 1024; // 2MB
                            $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                            $new_name = uniqid() . "." . $fileExt;

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

                    $existingFileNames = $item->upload_file1 ? explode(',', $item->upload_file1) : [];

                    if(!empty($fileNames))
                    {
                    $newFileNames = array_merge($existingFileNames, $fileNames);
                    $report_file_names = implode(",", $newFileNames);
                    $item->upload_file1 = $report_file_names;
                    }

                    if (!empty($errors)) {
                        return response()->json(['error' => implode('<br>', $errors) ], 401);
                    } else {
                        // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                        // die;
                    }
                } else {
                }

                $item->save();            
                $data['message'] = "Appointment prescription modified successfully!";
                return response()->json([
                    'result' => $data,
                    'debug_user' => $user
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed

        } 
    }

    //reject/cancel appointment by doctor in not confirmed appointment 
    public function confirm_appointment_v2(Request $request)
    {
        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $AppData = null;

        try{
            if ($request->isMethod('post')) {
                $request->validate([
                    'slot_name' => 'required',
                    'appointment_id' => 'required|string',
                    'reason' => 'required'
                ]);

                if ($request->slot_name == 'confirm') {
                    $Update_data['status'] = 'Confirmed';
                    $type = '';
                    $opr = 'doctor_confirm';
                    $reason = $request->reason;
                } else if ($request->slot_name == 'reject') {
                    $Update_data['status'] = 'Rejected';
                    $Update_data['CancelPatientOrDoctor'] = 2;
                    $type = 'rejected';
                    $reason = $request->reason;
                } else if ($request->slot_name == 'cancel') {
                    $Update_data['status'] = 'Cancelled';
                    $Update_data['CancelPatientOrDoctor'] = 2;
                    $reason = $request->reason;
                }

                if ($request->appointment_id != null) {
                    $Appsql = "SELECT * FROM tbl_appointments_booked where id = $request->appointment_id";
                    $app_details = DB::select($Appsql);
                    $AppData = collect($app_details);
                    
                    $res = DB::table('tbl_appointments_booked')
                        ->where('id', $request->appointment_id)
                        ->update($Update_data);

                        $patient = dbl_users::where('id', $AppData[0]->patient_id)->first();
                        $doctor_details = dbl_users::where('id', $AppData[0]->doctor_id)->first();
                        $app_details = Schedule::findOrFail($request->appointment_id);
                        
                        if($request->slot_name == 'reject'){
                            $subject = 'Appointment Rejected Successfully';
                            $opr = 'doctor_reject';
                            $reason = $request->reason;
                            SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-reject-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='patient');
                            SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-reject-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='doctor');
                            sendSms($patient,$doctor_details,$app_details,"Rejected");                

                        } else if($request->slot_name == 'cancel'){
                            $subject = 'Appointment Cancelled Successfully';
                            $opr = 'doctor_cancel';
                            $reason = $request->reason;
                            SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-cancel-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='patient');
                            SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-cancel-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='doctor');
                            sendSms($patient,$doctor_details,$app_details,"Cancelled");                
                        } else {
                            $subject = 'Appointment '.$Update_data['status'].' Successfully';
                            SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-book-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason);
                            SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-book-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason);
                        }

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Appointment ' . $Update_data['status'] . ' successfully!',
                    ], 200);
                    
                } else {

                    return response()->json([
                        'status' => 'error',
                        'message' => 'Appointment id not found',
                    ], 400);
                }
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed

        } 
    }

    //Get report review appointment by doctor
    public function GetReportReviewByDoctor(Request $request)
    {
        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $AppData = null;
        $data = [];
        $data['PageName'] = 'Report Reviews';
        $data['icon'] = 'patient_icon_01.png';

        try{

            $data["record_type"] = "report_review";

            if (!empty($user)) {
                $appointments_booked = Schedule::with(['doctor', 'patient']) // Eager load doctor (User model)
                    ->where('active', 1)
                    ->where('doctor_id', $user->id)
                    ->where('appointmentType', 'Report Review')
                    ->orderBy('created_at','DESC')
                    ->get([
                        'id',
                        'patient_id',
                        'start',
                        'end',
                        'doctor_id',
                        'symptoms',
                        'reports',
                        'description',
                        'appointmentType',
                        'status as appointment_status',
                        'category',
                        'amount',
                        'interests',
                        'report_file_names',
                        'medicalDocuments',
                        'notes',
                        'name as patient_name',
                        'contactNumber as patient_phone_number',
                        'city',
                        'state',
                        'created_at'
                    ]);

                // Optionally load report reviews replies
                $appointments_booked->load('reportReviewsReplies');
                $data["appointments_booked"] =  $appointments_booked;
                return response()->json([
                    'result' => $data,
                    'debug_user' => $user
                ]);
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed

        }
    }

    //start date, end date and status filter on appointment in report review
    public function FilterOnReviewByDoctor(Request $request)
    {
        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $AppData = null;
        $data = [];
        $data['PageName'] = 'Report Reviews';
        $data['icon'] = 'patient_icon_01.png';

        try{
            if ($request->isMethod('post')) {
                $request->validate([
                    'start' => 'required',
                    'end' => 'required'
                ]);

                $startDate = Carbon::createFromFormat('m-d-Y', $request->start)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('m-d-Y', $request->end)->format('Y-m-d');
                
                $data['start'] = $request->start ;
                $data['end'] = $request->end;
                $data['status'] = $request->status;

                $startDate = Carbon::createFromFormat('m-d-Y', $request->start)->format('Y-m-d');
                $endDate = Carbon::createFromFormat('m-d-Y', $request->end)->format('Y-m-d');
                
                $appointments_booked = Schedule::with(['doctor', 'patient']) // Eager load doctor (User model)
                    ->where('active', 1)
                    ->where('doctor_id', $user->id)
                    ->where('appointmentType', 'Report Review')
                    ->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=',  $endDate);

                if ($request->status && $request->status != '') {
                    $appointments_booked->where('status', $request->status);
                }

                $appointments_booked = $appointments_booked->orderBy('created_at', 'DESC')
                    ->get([
                        'id',
                        'patient_id',
                        'start',
                        'end',
                        'doctor_id',
                        'symptoms',
                        'reports',
                        'description',
                        'appointmentType',
                        'status as appointment_status',
                        'category',
                        'amount',
                        'interests',
                        'report_file_names',
                        'medicalDocuments',
                        'notes',
                        'name as patient_name',
                        'contactNumber as patient_phone_number',
                        'city',
                        'state',
                        'created_at'
                    ]);

                // Optionally load report reviews replies
                $appointments_booked->load('reportReviewsReplies');
                $data["appointments_booked"] =  $appointments_booked;

                return response()->json([
                    'result' => $data,
                    'debug_user' => $user
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'This is post method',
                ], 400);
            }
            
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    //doctor reply on report review
    public function doctorReplyOnReportReview(Request $request)
    {
        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $AppData = null;

        try{ 
            if ($request->isMethod('post')) {
                $request->validate([
                    'appointment_id' => 'required|exists:tbl_appointments_booked,id',
                    'reply' => 'required|string',
                    'upload_file1.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
                ]);

                $reply = $request->reply;
                $appointment_id = $request->appointment_id;

                $item = new ReportReviewsReplies();
                $item->doctor_reply = Crypt::encrypt($reply);
                $item->appointment_id = $appointment_id;

                $uploadedFiles = [];
                if (isset($_FILES['upload_file1']) && !empty($_FILES['upload_file1']['name'][0])) {

                    $fileNames = [];
                    $errors = [];

                    foreach ($_FILES['upload_file1']['name'] as $key => $name) {
                        $tmpName = $_FILES['upload_file1']['tmp_name'][$key];
                        $size = $_FILES['upload_file1']['size'][$key];
                        $error = $_FILES['upload_file1']['error'][$key];

                        // Validate file
                        if ($error === UPLOAD_ERR_OK) {
                            // Specify allowed file types and size limit (e.g., 2MB)
                            $allowedTypes = ['pdf', 'jpeg', 'jpg', 'png'];
                            $maxSize = 4 * 1024 * 1024; // 2MB
                            $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                            $new_name = uniqid() . "." . $fileExt;

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

                    $report_file_names = implode(",", $fileNames);

                    $item->upload_file1 = $report_file_names;


                    if (!empty($errors)) {
                        return response()->json(['error' => implode('<br>', $errors) ], 401);
                    } else {
                        // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                        // die;
                    }
                } else {
                }

                $item->save();

                if (!empty($item->appointment_id)) {
                    $appointment = Schedule::findOrFail($item->appointment_id);
                    $appointment->status = "Replied";
                    $appointment->save();
                }

                if ($appointment_id != null) {
                    $Appsql = "SELECT * FROM tbl_appointments_booked where id = $appointment_id";
                    $app_details = DB::select($Appsql);
                    $AppData = collect($app_details);
                }

                $patient = dbl_users::where('id', $AppData[0]->patient_id)->first();
                $doctor_details = dbl_users::where('id', $AppData[0]->doctor_id)->first();
                $app_details = Schedule::findOrFail($appointment_id);
                
                $subject = 'On Request Review Replied Submitted Successfully';
                $opr = 'doctor_reply_report_review';
                $reason = $request->reply;
                SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-doctor-reply-on-report-review-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='patient');
                SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-doctor-reply-on-report-review-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='doctor');
                sendSms($patient,$doctor_details,$app_details,"Replied_To_Patient");

                return response()->json([
                    'status' => 'success',
                    'message' => 'Reply added successfully!',
                ], 200);
            }
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    //doctor modify reply on report review
    public function doctorModifyReplyOnReportReview(Request $request)
    {
        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $AppData = null;

        try{ 
            if($request->isMethod('post'))
            {
                $request->validate([
                    'modify_appointment_id' => 'required',
                    'modify_reply_id' => 'required',                
                    'modify_reply_text' => 'required|string',   
                    'modify_upload_file1.*' => 'file|mimes:pdf,jpg,jpeg,png|max:4096',                             
                ]);

                $modify_reply_text = $request->modify_reply_text;
                $modify_appointment_id = $request->modify_appointment_id;
                $modify_reply_id =$request->modify_reply_id; 
                $fileNames = [];
                
                $uploadedFiles = [];
                if (isset($_FILES['modify_upload_file1']) && !empty($_FILES['modify_upload_file1']['name'][0])) {

                    $fileNames = [];
                    $errors = [];

                    foreach ($_FILES['modify_upload_file1']['name'] as $key => $name) {
                        $tmpName = $_FILES['modify_upload_file1']['tmp_name'][$key];
                        $size = $_FILES['modify_upload_file1']['size'][$key];
                        $error = $_FILES['modify_upload_file1']['error'][$key];

                        // Validate file
                        if ($error === UPLOAD_ERR_OK) {
                            // Specify allowed file types and size limit (e.g., 2MB)
                            $allowedTypes = ['pdf', 'jpeg', 'jpg', 'png'];
                            $maxSize = 4 * 1024 * 1024; // 2MB
                            $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));

                            $new_name = uniqid() . "." . $fileExt;

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

                    $existingFileNames = $item->upload_file1 ? explode(',', $item->upload_file1) : [];

                    if(!empty($fileNames))
                    {
                    $newFileNames = array_merge($existingFileNames, $fileNames);
                    $report_file_names = implode(",", $newFileNames);
                    $item->upload_file1 = $report_file_names;
                    }

                    if (!empty($errors)) {
                        return response()->json(['error' => implode('<br>', $errors) ], 401);
                    } else {
                        // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                        // die;
                    }
                } else {
                }

                if(!empty($modify_reply_id))
                {
                $reply = ReportReviewsReplies::findOrFail($modify_reply_id);

                // get existing csv files 
                $existingFileNames = $reply->upload_file1 ? explode(',', $reply->upload_file1) : [];

                $reply->doctor_reply = Crypt::encrypt($modify_reply_text);

                // Append new filenames
                if(!empty($fileNames))
                {
                $newFileNames = array_merge($existingFileNames, $fileNames);
                $reply->upload_file1 = implode(',', $newFileNames);
                }

                $reply->save();
                }

                return response()->json([
                    'status' => 'success',
                    'message' => 'Reply modified successfully!',
                ], 200);
            }
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }


    public function getTimeSlotsEvents(Request $request)
    {
        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try{ 
            $data['schedules'] = AvailableScheduleSlots::where('doctor_id',$user["id"] )->get();
            return response()->json([
                'result' => $data,
                'debug_user' => $user
            ]);
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    //Create or add timeslot by doctor
    public function CreateTimeslotByDoctor(Request $request)
    {
        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $AppData = null;

        try{ 
            if($request->isMethod('post'))
            {
                $request->validate([          
                    'start' => 'required',
                    'end' => 'required',
                    'slots' => 'required',
                ]);
                
                // Start:: Code for two date range and all timeslots of a day 
                $startDate = Carbon::createFromFormat('m-d-Y', $request->start);
                $startDate = $startDate->format('Y-m-d');
                $startDate = Carbon::parse($startDate);

                $endDate = Carbon::createFromFormat('m-d-Y', $request->end);
                $endDate = $endDate->format('Y-m-d');
                $endDate = Carbon::parse($endDate);

                $doctor_id = $user["id"];
                
                while ($startDate <= $endDate) 
                {
                    $sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND id=".$doctor_id;
                    $doctor = DB::select($sql);
                    $doctor = collect($doctor);
                    $doctor = $doctor->first();
                    
                    $slots = $request->slots;
                    
                    foreach($slots as $k=>$slot)
                    {
                        list($start_time,$end_time) = explode("-",$slot);
                        $start_time = $start_time.":00";
                        $end_time = $end_time.":00";
                        
                        $item = new AvailableScheduleSlots();
                        
                        $item->title = $doctor->first_name.":".$start_time." To ".$end_time;
                        
                        $start_datetime = Carbon::parse($startDate->toDateString() . ' ' . $start_time);
                        $end_datetime = Carbon::parse($startDate->toDateString() . ' ' . $end_time);
                        
                        $item->start = $start_datetime->toDateTimeString();
                        $item->end = $end_datetime->toDateTimeString();
                        
                        $item->doctor_id = $doctor_id;
                        
                        $SlotSql = "SELECT id FROM tbl_available_schedule_slots where 
                        doctor_id = ".$item->doctor_id." AND start='".$item->start."' AND end='".$item->end."'";
                        $SlotData = DB::select($SlotSql);
                        $SlotData = collect($SlotData);

                        if ($SlotData->isNotEmpty()) 
                        {
                            if(!empty($SlotData) && $SlotData->count() > 0)
                            {     
                                continue;
                            }
                        } else {
                            $item->save();
                        }
                    }
                    $startDate->addDay();
                }
                
                return response()->json([
                    'status' => 'success',
                    'message' => 'Timeslots added successfully!',
                ], 200);
            }
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    //Delete timeslot by doctor
    public function deleteEvent(Request $request)
    {
        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try{ 
            $request->validate([
                'id' => 'required'                           
            ]);

            $schedule = AvailableScheduleSlots::findOrFail($request->id);
            $schedule->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Time-slot deleted successfully',  // This will return the validation errors
            ], 200); // You can adjust the error code here if needed
        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }

    //Get event on my calender
    public function getMyCalenderEvents(Request $request)
    {
        $token = $request->bearerToken();

        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        try{
            $doctor_id = $user->id;
            $schedules = Schedule::with(['state','city'])
            ->where(['doctor_id'=>$doctor_id,'active'=>1, 'status' => 'Confirmed'])
            ->get();

            $data['schedulesArray'] = $schedules->map(function($schedule) {
                return array_merge(
                    $schedule->toArray(), // Include all attributes
                    [
                        'appointment_start' => $schedule->appointment_start,
                        'appointment_end' => $schedule->appointment_end,                    
                    ]
                );
            });

            return response()->json([
                'result' => $data,
                'debug_user' => $user
            ]);

        } catch (ValidationException $e) {

            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed
        }
    }
}
