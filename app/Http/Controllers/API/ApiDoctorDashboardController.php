<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\dbl_users;
use App\Models\Schedule;
use App\Models\User;
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


    public function getAppointmentDataByStatus(Request $request, $record_type = "todays")
    {
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $data = [];
        if (!empty($record_type)) {
            $data["result"]["record_type"] = $record_type;
        }

        $data['PageName'] = 'Doctor Dashboard';
        $data['icon'] = 'Group(40).png';

        if (!empty($user)) {
            if ($request->isMethod('post')) {
                // print_r($request->all());die();
                if (!empty($request->start)) {
                    $startDate = Carbon::createFromFormat('m-d-Y', $request->start)->format('Y-m-d');
                    $data['start'] = $request->start;
                }
                if (!empty($request->end)) {
                    $endDate = Carbon::createFromFormat('m-d-Y', $request->end)->format('Y-m-d');
                    $data['end'] = $request->end;
                }
                $data['status'] = $request->status;

                $sql = "SELECT * FROM tbl_appointments_booked where doctor_id = $user->id AND `status` != 'Cancelled'";
                $query = '';
                if (isset($startDate) && isset($endDate)) {
                    $query .= ' AND DATE(ab.start) >= "' . $startDate . '" AND DATE(ab.start) <= "' . $endDate . '"';
                }

                if ($request->status && $request->status != '') {
                    $query .= ' AND ab.status = "' . $request->status . '"';
                }

                if ($record_type == "new") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id 
                        where ab.active = 1 AND ab.doctor_id = $user->id 
                        AND ab.start > NOW() " . $query . " order by start desc";
                } elseif ($record_type == "past") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
                        ab.active = 1 AND ab.status != 'Cancelled' AND ab.status != 'Rejected' 
                        AND ab.doctor_id = $user->id " . $query . " AND ab.end <' " . date('Y-m-d H:i:s') . "' order by start desc";
                } elseif ($record_type == "todays") {
                    // Added this query by darshan 18-09-2024
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
                        ab.active = 1 AND ab.status = 'Confirmed'  
                        AND ab.doctor_id = $user->id " . $query . " 
                        AND DATE(ab.start) ='" . date('Y-m-d') . "' AND ab.start >'" . date('Y-m-d H:i:s') . "' order by start desc";
                } elseif ($record_type == "rejected") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.doctor_id = $user->id " . $query . " AND (ab.status ='Rejected' OR ab.status ='Cancelled') order by start desc";
                } elseif ($record_type == "upcoming") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.doctor_id = $user->id " . $query . " AND ab.status = 'Confirmed' AND ab.start > '" . date('Y-m-d H:i:s') . "' order by start desc";
                }
            } else {
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
                    // dd($sql);
                }
            }


            $appointments_booked = DB::select($sql);
            $appointments_booked = collect($appointments_booked);

            $data["result"]["appointments"] =  $appointments_booked;



            $RedFlagSql = "SELECT ab.id FROM tbl_appointments_booked ab
            LEFT JOIN dbl_users u ON ab.doctor_id = u.id 
            where ab.active = 1 AND ab.status = 'In-Process' AND ab.doctor_id = $user->id 
            AND ab.start > NOW() order by start desc";

            $CheckRedFlag = DB::select($RedFlagSql);
            $CheckRedFlag = collect($CheckRedFlag);
            $data['CheckRedFlag'] = $CheckRedFlag;

            // echo'<pre>';print_r($data);die;
        }

        return response()->json([
            'record_type' => $record_type,
            'result' => $data,
            'debug_user' => $user
        ]);
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
        $patientDataList =  User::where('user_type', 'patient')->get();

        return response()->json([
            'result' => $patientDataList,
            'debug_user' => $user
        ]);
    }


    public function not_confirmed_appintments(Request $request)
    {

        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        $data = [];
        if (!empty($record_type)) {
            $data["result"]["record_type"] = $record_type;
        }

        $data['PageName'] = 'Not Confirmed Appointments';
        $data['icon'] = 'Group 9956.png';

        if (!empty($user)) {
            $data['user'] = $user;

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



            $data["result"]["Not_confirmed_appointments"] =  $appointments_booked;

            $sql2 = "SELECT * from dbl_users where user_type='doctor'";

            $doctors = DB::select($sql2);
            $doctors = collect($doctors);

            $data["result"]["doctors"] =  $doctors;
        }

        return response()->json([
            'result' => $data,
            'debug_user' => $user
        ]);
    }

    public function getNewMessages(Request $request)
    {
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }
        $data['PageName'] = 'Message';
        $data['icon'] = 'patient_icon_02.png';

        $data['user_type'] = $user['user_type'];

        if ($user['user_type'] == 'patient') {
            $extData = DB::table('tbl_appointments_booked')
                ->where('patient_id', $user['id'])
                ->where('start', '<', now()) // using now() for current timestamp
                ->whereIn('status', ['Completed', 'Confirmed']) // Add status condition
                ->orderBy('start', 'desc');

            // Get the count of the records before pagination
            $data['record_count'] = $extData->count();
            $data['appointment_list'] = $extData->get();  // You can change this logic if needed



        } elseif ($user['user_type'] == 'doctor') {

            $extData = DB::table('tbl_appointments_booked')
                ->where('doctor_id', $user['id'])
                ->where('start', '<', now()) // using now() for current timestamp
                ->whereIn('status', ['Completed', 'Confirmed']) // Add status condition
                ->orderBy('start', 'desc'); // paginate method

            // Get the count of the records before pagination
            $data['record_count'] = $extData->count();

            $data['appointment_list'] = $extData->get();  // You can change this logic if needed

        }
        return response()->json([
            'result' => $data,
            'debug_user' => $user
        ]);
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

    public function confirmAppointment(Request $request)
    {
        // dd($request->all());
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }



        $AppData = null;
        try {
            $not_confirm_appointment = '';
            $validatedData = $request->validate([
                'phone_meeting_link'  => ['required'],
                'confirm_appointment_id' => ['required'],
                
            ]);
            if ($request->isMethod('post')) {
                $phone_meeting_link = $request->phone_meeting_link;
                $confirm_appointment_id = $request->confirm_appointment_id;

                $Appsql = "SELECT * FROM tbl_appointments_booked where id = $confirm_appointment_id";
                $app_details = DB::select($Appsql);
                $AppData = collect($app_details);

                if ($confirm_appointment_id != null && $phone_meeting_link != null) {
                    if ($request->not_confirm_appointment && $request->not_confirm_appointment != '') {
                        $not_confirm_appointment = $request->not_confirm_appointment;
                    } else {
                        $not_confirm_appointment = '';
                    }

                    if ($not_confirm_appointment == '1') {

                        $loggedin_doctor_id = $user;

                            $res = DB::table('tbl_available_schedule_slots')
                            ->where('id', $AppData[0]->slot_id)
                            ->update(["is_available" => "1", "booked" => "0"]);

                       //get old doctor slot data
                        $old_doctor_slot = "SELECT * FROM tbl_available_schedule_slots 
                            where id = " . $AppData[0]->slot_id;

                        $old_doctor_slot = DB::select($old_doctor_slot);
                        $old_doctor_slot = collect($old_doctor_slot);

                        if ($old_doctor_slot->isEmpty()) {
                            return response()->json(['error' => 'Old slot not found in database.appointment cannot be swaped'], 401);
                        }

                        
                        if (!empty($old_doctor_slot[0]->start) &&  !empty($old_doctor_slot[0]->end) && !empty($loggedin_doctor_id->id)) {

                            $new_doctors_slot_id = "SELECT * FROM tbl_available_schedule_slots 
                                    where booked = 0 AND is_available = 1 AND `start` = '" . $old_doctor_slot[0]->start . "' AND end = '" . $old_doctor_slot[0]->end . "' AND doctor_id =" . $loggedin_doctor_id->id;

                            $new_doctors_slot_id = DB::select($new_doctors_slot_id);
                            $new_doctors_slot_id = collect($new_doctors_slot_id);

                            if ($new_doctors_slot_id->isNotEmpty()) {
                                //update app record with slot id

                                if (!empty($new_doctors_slot_id[0]->id)) {
                                    $Update_data['slot_id'] = $new_doctors_slot_id[0]->id;
                                    $Update_data['status'] = 'Confirmed';
                                    $Update_data['doctor_id'] = $loggedin_doctor_id->id;
                                    $Update_data['phone_meeting_link'] = $phone_meeting_link;
                                    $res = DB::table('tbl_appointments_booked')
                                        ->where('id', $confirm_appointment_id)
                                        ->update($Update_data);

                                    // also update timeslot of new doctor booked = 1 is_available = 0
                                    $res = DB::table('tbl_available_schedule_slots')
                                        ->where('id', $new_doctors_slot_id[0]->id)
                                        ->update(["is_available" => 0, "booked" => 1]);

                                    // print_r($Update_data);     
                                    // dd($res );   

                                } else {
                                    $dtime = new DateTime($old_doctor_slot[0]->start);
                                    $dtime = $dtime->format("m/d/y H:i");
                                    return response()->json(['error' => 'Your slot at DateTime:' . $dtime . ' is not found, Please add and try again.'], 401);
                                }
                            } else {
                                $dtime = new DateTime($old_doctor_slot[0]->start);
                                $dtime = $dtime->format("m-d-y H:i");
                                return response()->json(['error' => 'Your slot at DateTime:' . $dtime . ' is not found, Please add and try again.'], 401);

                            }
                        } else {
                            return response()->json(['error' => 'Cannot confirm, Please try again.'], 401);
                          }
                    } elseif ($not_confirm_appointment == '') {

                        $Update_data['status'] = 'Confirmed';
                        $Update_data['phone_meeting_link'] = $phone_meeting_link;
                        $res = DB::table('tbl_appointments_booked')
                            ->where('id', $confirm_appointment_id)
                            ->update($Update_data);
                    }

                    $patient = dbl_users::where('id', $AppData[0]->patient_id)->first();
                    $doctor_details = dbl_users::where('id', $AppData[0]->doctor_id)->first();
                    $app_details = Schedule::findOrFail($confirm_appointment_id);
                    $subject = 'Appointment Confirmed Successfully';
                    $opr = 'doctor_confirm';

                    SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-confirm-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $phone_meeting_link, $rec_by = 'patient');
                    SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-confirm-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $phone_meeting_link, $rec_by = 'doctor');
                    sendSms($patient, $doctor_details, $app_details, "Confirm");

                    if (!empty($not_confirm_appointment) && $not_confirm_appointment == '1') {
                        $data['messages'] ='Appointment confirmed successfully!';
                    }

                    if (empty($not_confirm_appointment)) {
                        $dateFromAppData = date('Y-m-d', strtotime($AppData[0]->start));
                        $today = date('Y-m-d');

                        if ($dateFromAppData == $today) {
                            $data['messages']='Appointment ' . $Update_data['status'] . ' successfully!';
                        }

                        if ($dateFromAppData > $today) {
                            $data['messages'] = 'Appointment ' . $Update_data['status'] . ' successfully!';
                        }

                        if ($dateFromAppData < $today) {
                         $data['messages'] = 'Appointment ' . $Update_data['status'] . ' successfully!';
                        }
                    }
                } else {

                    if ($not_confirm_appointment == '')
                    return response()->json(['error' => 'appointment id OR phone/link cannot be blank.'], 401);
                    if ($not_confirm_appointment === '1')
                    return response()->json(['error' => 'appointment id OR phone/link cannot be blank.'], 401);
                }

                return response()->json([
                    'result' => $data,
                    'debug_user' => $user
                ]);
            } else {

                // if($not_confirm_appointment == '')
                // return redirect('/doctor-dashboard/new')->with('error', 'Invalid method, Form not posted');
            }
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->errors(),  // This will return the validation errors
            ], 400); // You can adjust the error code here if needed


            $controllerName = class_basename(get_class($this));
            Log::error('Date::----------------' . date("Y-m-d H:i:s") . "-------------------------");
            Log::error('Class::---------------' . $controllerName . '');
            Log::error('Function::---------------' . __METHOD__ . '');
            Log::error('Line::---------------' . __LINE__); // Logs the line number of this statement
            Log::error('Exception: ' . $e->getMessage());
            Log::error('Exception Line: ' . $e->getLine()); // Logs the line number where the exception was thrown

        }
    }


    //mark as completed appointment
    public function save_doctor_prescription(Request $request)
    {
         // dd($request->all());
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
// dd($request->all());
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
            $data['message'] = "Appointment completed successfully!";
            return response()->json([
                'result' => $data,
                'debug_user' => $user
            ]);
            // dd($item);

        }
    }catch (ValidationException $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->errors(),  // This will return the validation errors
        ], 400); // You can adjust the error code here if needed

    } 
    }
}
