<?php

namespace App\Http\Controllers\Frontend;


use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\dbl_users;
use App\Models\ReportReviewsReplies;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule as SchedulingSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\WelcomeMail;
use App\Services\SendGridService;
use DateTime;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;



class DoctorController extends Controller
{
    /**
     * 
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

    public function modify_doctor_report_reply(Request $request)
    {
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

                // $report_file_names = implode(",", $fileNames);

                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                    die;
                } else {
                    // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                    // die;
                }
            } else {
                // $report_file_names = '';
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

            return redirect('/doctor-report-reviews')->with('success', 'Reply modified successfully!');

        }

    }    
    public function doctor_report_reply_old(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'appointment_id' => 'required|exists:tbl_appointments_booked,id',
                'reply' => 'required|string',
                'upload_file1' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,gif,jpeg|max:2048',
                'upload_file2' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,gif,jpeg|max:2048',
                'upload_file3' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,gif,jpeg|max:2048',
            ]);

            $reply = $request->reply;
            $appointment_id = $request->appointment_id;

            $item = new ReportReviewsReplies();
            $item->doctor_reply = $reply;
            $item->appointment_id = $appointment_id;

            $uploadedFiles = [];

            foreach (['upload_file1', 'upload_file2', 'upload_file3'] as $fileInputName) {
                if ($request->hasFile($fileInputName)) {
                    $file = $request->file($fileInputName);
                    $filename = time() . '-' . $file->getClientOriginalName();
                    $file->move(public_path('patient_reports'), $filename);
                    $uploadedFiles[$fileInputName] = $filename;
                }
            }

            // Optionally save file paths if needed
            $item->upload_file1 = $uploadedFiles['upload_file1'] ?? null;
            $item->upload_file2 = $uploadedFiles['upload_file2'] ?? null;
            $item->upload_file3 = $uploadedFiles['upload_file3'] ?? null;

            $item->save();

            if (!empty($item->appointment_id)) {
                $appointment = Schedule::findOrFail($item->appointment_id);
                $appointment->status = "Replied";
                $appointment->save();
            }

            return redirect('/doctor-report-reviews')->with('success', 'Reply added successfully!');
        }
    }

    public function save_doctor_prescription(Request $request)
    {
        $AppData = null;
        if ($request->isMethod('post')) {
            $request->validate([
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
                    echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                    die;
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

            // dd($item);

            return redirect('/doctor-dashboard/past')->with('success', 'Appointment completed successfully!');
        }
    }

    public function modify_doctor_prescription(Request $request)
    {
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
                    echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                    die;
                } else {
                    // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                    // die;
                }
            } else {
            }

            $item->save();            

            // dd($item);

            return redirect('/doctor-dashboard/past')->with('success', 'Appointment completed successfully!');
        }
    }

    public function deletePrescriptionFile(Request $request)
    {
        $appointmentId = $request->input('appointmentId');
        $filename = $request->input('filename');
        
        $appointment = Schedule::find($appointmentId);
        if ($appointment) {
            // Get existing filenames
            $files = explode(',', $appointment->upload_file1);
            
            // Remove the file to be deleted
            $files = array_filter($files, function($file) use ($filename) {
                return trim($file) !== $filename;
            });

            // Update the column with the new comma-separated values
            $appointment->upload_file1 = implode(',', $files);
            $appointment->save();
            
            return response()->json(['success' => true]);
        }
    
    return response()->json(['success' => false], 404);
    }

    public function doctor_report_reply(Request $request)
    {
        $AppData = null;

        if ($request->isMethod('post')) {
            $request->validate([
                'appointment_id' => 'required|exists:tbl_appointments_booked,id',
                'reply' => 'required|string',
                'upload_file1.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
                // 'upload_file2' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,gif,jpeg|max:2048',
                // 'upload_file3' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,gif,jpeg|max:2048',
            ]);

            $reply = $request->reply;
            
            $appointment_id = $request->appointment_id;

            $item = new ReportReviewsReplies();
            $item->doctor_reply = Crypt::encrypt($reply);
            $item->appointment_id = $appointment_id;
            
            // dd($item);

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
                    echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                    die;
                } else {
                    // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                    // die;
                }
            } else {
            }

            // echo "<pre>";
            // print_r($item);die();
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

            return redirect('/doctor-report-reviews')->with('success', 'Reply added successfully!');
        }
    }
    public function doctor_report_reviews(Request $request, $record_type = "report_review")
    {
        // echo'<pre>';print_r($_POST);die;
        session()->forget('selected_plan');

        $user = $this->getSessionData('user');

        $data = [];

        $data['PageName'] = 'Report Reviews';
        $data['icon'] = 'patient_icon_01.png';

        if (!empty($record_type))
            $data["result"]["record_type"] = $record_type;

            if (!empty($user)) {
                // $sql = "SELECT ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                // ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                // ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state
                // ,ab.created_at 
                // FROM tbl_appointments_booked ab
                // LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
                // ab.active = 1 AND ab.doctor_id = $user->id AND appointmentType = 'Report Review'";

                if ($request->isMethod('post')) {
                    $data['start'] = $request->start;
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

                } else {
                    $data['start'] = $data['end'] = '';
                    $data['status'] = '';
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
                }

                // dd($appointments_booked);



                $data["result"]["appointments_booked"] =  $appointments_booked;
            }

        return view('frontend.doctor-report-reviews', $data);
    }

    public function confirmAppointment(Request $request)
    {
        $appointmentId = $request->input('appointment_id');

        // Find the appointment by ID and update its status to "confirmed"
        $appointment = Schedule::find($appointmentId);
        if ($appointment) {
            $appointment->status = 'Confirmed';
            $appointment->save();

            return response()->json(['success' => true]);
        } else {
            return response()->json(['success' => false, 'message' => 'Appointment not found']);
        }
    }


    public function doctor_cancel_appointment($appointment_id = null)
    {

        $AppData = null;
        if ($appointment_id != null) {
            //Logic for appointment timeslot become free.
            $Appsql = "SELECT * FROM tbl_appointments_booked where id = $appointment_id";
            $app_details = DB::select($Appsql);
            $AppData = collect($app_details);

            if (!empty($AppData) && count($AppData) > 0) {

                $slotId = $AppData[0]->slot_id;

                if ($slotId > 0) {
                    $UpdateSlot['is_available'] = 1;
                    $res = DB::table('tbl_available_schedule_slots')
                        ->where('id', $slotId)
                        ->update($UpdateSlot);
                }
            }

            $Update_data['status'] = 'Cancelled';
            $Update_data['CancelPatientOrDoctor'] = 2;

            $res = DB::table('tbl_appointments_booked')
                ->where('id', $appointment_id)
                ->update($Update_data);

            return redirect('/doctor-dashboard')->with('success', 'Appointment cancelled successfully!');
        } else
            return redirect('/doctor-dashboard')->with('error', 'Appointment id not found');
    }


    public function doctor_calendar()
    {
        $user = $this->getSessionData('user');
        $doctor_id = $user["id"];
    }

    public function doctor_dashboard(Request $request, $record_type = "todays")
    {

        $user = $this->getSessionData('user');

        $data = [];
        if (!empty($record_type)) {
            $data["result"]["record_type"] = $record_type;
        }

        $data['PageName'] = 'Doctor Dashboard';
        $data['icon'] = 'Group(40).png';     

        if (!empty($user)) {
            if ($request->isMethod('post')) 
            {
// print_r($request->all());die();
            $startDate = Carbon::createFromFormat('m-d-Y', $request->start)->format('Y-m-d');
             $endDate = Carbon::createFromFormat('m-d-Y', $request->end)->format('Y-m-d');
            // print_r($startDate);die();
                $data['start'] = $request->start ;
                $data['end'] = $request->end;
                $data['status'] = $request->status;

                $sql = "SELECT * FROM tbl_appointments_booked where doctor_id = $user->id AND `status` != 'Cancelled'";
                $query = '';

                $query = 'AND DATE(ab.start) >= "'. $startDate.'" AND DATE(ab.start) <= "'. $endDate.'"';

                if($request->status && $request->status != ''){
                    $query .= ' AND ab.status = "'. $request->status.'"';
                }

                if ($record_type == "new") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id 
                        where ab.active = 1 AND ab.doctor_id = $user->id 
                        AND ab.start > NOW() ".$query." order by start desc";
                } elseif ($record_type == "past") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
                        ab.active = 1 AND ab.status != 'Cancelled' AND ab.status != 'Rejected' 
                        AND ab.doctor_id = $user->id ".$query." AND ab.end <' " . date('Y-m-d H:i:s') . "' order by start desc";
                } elseif ($record_type == "todays") {
                // Added this query by darshan 18-09-2024
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
                        ab.active = 1 AND ab.status = 'Confirmed'  
                        AND ab.doctor_id = $user->id ".$query." 
                        AND DATE(ab.start) ='".date('Y-m-d')."' AND ab.start >'" . date('Y-m-d H:i:s') . "' order by start desc";
                } elseif ($record_type == "rejected") {
                    $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.doctor_id = $user->id ".$query." AND (ab.status ='Rejected' OR ab.status ='Cancelled') order by start desc";
                        } elseif ($record_type == "upcoming") {
                            $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                        ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.doctor_id = $user->id ".$query." AND ab.status = 'Confirmed' AND ab.start > '".date('Y-m-d H:i:s')."' order by start desc";
                }
            } else {
                $sql = "SELECT * FROM tbl_appointments_booked where doctor_id = $user->id AND `status` != 'Cancelled'";
                    //upcoming = new
                    // if($record_type == "new")
                    // {
                    // $sql = "SELECT ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    // ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                    // ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                    // LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.status != 'Cancelled' AND ab.doctor_id = $user->id AND Date(ab.start) >= '".date('Y-m-d', strtotime('+1 day'))."'";
                    // }
                    // elseif($record_type == "past")
                    // {
                    // $sql = "SELECT ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    // ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                    // ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                    // LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.status != 'Cancelled' AND ab.doctor_id = $user->id AND ab.start <' ".date('Y-m-d H:i:s')."'";
                    // }
                    // elseif($record_type == "todays")
                    // {
                    // $sql = "SELECT ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    // ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                    // ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                    // LEFT JOIN dbl_users u ON ab.doctor_id = u.id where  ab.active = 1 AND ab.status != 'Cancelled' AND ab.doctor_id = $user->id AND DATE(ab.start) =' ".date('Y-m-d')."'";
                    // }
                    // elseif($record_type == "rejected")
                    // {
                    // $sql = "SELECT ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    // ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                    // ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                    // LEFT JOIN dbl_users u ON ab.doctor_id = u.id where  ab.active = 1 AND ab.doctor_id = $user->id AND ab.status ='Cancelled'";
                    // }

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
                    //     $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
                    // ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
                    // ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state FROM tbl_appointments_booked ab
                    // LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
                    // ab.active = 1 AND (ab.status = 'Confirmed' || ab.status = 'Completed')  
                    // AND ab.doctor_id = $user->id 
                    // AND ab.start =' " . date('Y-m-d H:i:s') . "' order by start desc";

                    // Added this query by darshan 18-09-2024
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
                    LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.doctor_id = $user->id AND ab.status = 'Confirmed' AND DATE(ab.start) > '".date('Y-m-d')."' order by start desc";
                    // dd($sql);
                }
            }
        
            // echo $sql;

            $appointments_booked = DB::select($sql);
            $appointments_booked = collect($appointments_booked);

            // dd($appointments_booked->count());
            // dd($appointments_booked);
            $data["result"]["appointments_booked"] =  $appointments_booked;

            // dd($appointments_booked);
            // $sql = "SELECT * FROM dbl_users where user_type = 'patient'";
            // $users = DB::select($sql);
            // $patients = collect($users);                                            

            // $RedFlagSql = "SELECT id FROM tbl_appointments_booked
            // where active = 1 AND status = 'In-Process' 
            // AND DATE(start) >= '".date('Y-m-d')."' 
            // AND doctor_id = $user->id";

            $RedFlagSql = "SELECT ab.id FROM tbl_appointments_booked ab
            LEFT JOIN dbl_users u ON ab.doctor_id = u.id 
            where ab.active = 1 AND ab.status = 'In-Process' AND ab.doctor_id = $user->id 
            AND ab.start > NOW() order by start desc";
        
            $CheckRedFlag = DB::select($RedFlagSql);
            $CheckRedFlag = collect($CheckRedFlag);
            $data['CheckRedFlag'] = $CheckRedFlag;
            
            // echo'<pre>';print_r($data['CheckRedFlag']);die;
       }
     
       return view('frontend.doctor-dashboard',$data);       
    }

    public function not_confirmed_appintments()
    {

        $user = $this->getSessionData('user');

        $data = [];
        if (!empty($record_type)) {
            $data["result"]["record_type"] = $record_type;
        }

        $data['PageName'] = 'Not Confirmed Appointments';
        $data['icon'] = 'Group 9956.png';

        if (!empty($user)) {
            $data['user'] = $user;

            // $sql = "SELECT * FROM tbl_appointments_booked where 
            // doctor_id = $user->id AND `status` != 'Cancelled'";

            $sql = "SELECT ab.completed_at,ab.upload_file1,ab.id,ab.doctor_id, ab.patient_id, ab.start,ab.end, 
            ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
        ab.name AS patient_name, ab.NotConfirmed, u.phone_number as patient_phone_number,
        ab.city,ab.state
        FROM tbl_appointments_booked ab
        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 
        AND ab.status = 'In-Process' AND ab.NotConfirmed = '1' AND ab.start >= '".date('Y-m-d H:i:s')."' order by start ASC";

        $appointments_booked = DB::select($sql);
        $appointments_booked = collect($appointments_booked);  

        // dd($appointments_booked);
        
            $data["result"]["appointments_booked"] =  $appointments_booked;        

            // $appointments_booked = DB::select($sql);
            // $appointments_booked = collect($appointments_booked);

            // $data["result"]["appointments_booked"] =  $appointments_booked;

            $sql2 = "SELECT * from dbl_users where user_type='doctor'";

            $doctors = DB::select($sql2);
            $doctors = collect($doctors);
            
            $data["result"]["doctors"] =  $doctors;
        }

        //    dd($data["result"]["appointments_booked"]);die;
        return view('frontend.not-confirmed-appintments', $data);
    }

    public function confirm_appointment(Request $request, $slot_name = null, $appointment_id = null)
    {
        $change_timeslot = 0;

        $AppData = null;

        if ($slot_name == 'confirm') {
            $Update_data['status'] = 'Confirmed';
            $type = '';
        } else if($slot_name == 'reject'){
            $Update_data['status'] = 'Rejected';
            $Update_data['CancelPatientOrDoctor'] = 2;
            $change_timeslot = 1;
            $type = 'rejected';
        } else if($slot_name == 'cancel'){
            $Update_data['status'] = 'Cancelled';
            $Update_data['CancelPatientOrDoctor'] = 2;
            $change_timeslot = 1;
            $type = 'rejected';
        } else if($slot_name == 'completed'){
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
            
            if ($slot_name == 'confirm') {
                $subject = 'Appointment Confirmed Successfully';
                $opr = 'doctor_confirm';
                $reason = $request->reason;
                SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-confirm-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='patient');
                SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-confirm-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='doctor');
                sendSms($patient,$doctor_details,$app_details,"Confirm");                

            } else if($slot_name == 'reject'){
                $subject = 'Appointment Rejected Successfully';
                $opr = 'doctor_reject';
                $reason = $request->reason;
                SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-reject-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='patient');
                SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-reject-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='doctor');
                sendSms($patient,$doctor_details,$app_details,"Rejected");                
            } else if($slot_name == 'cancel'){
                $subject = 'Appointment Cancelled Successfully';
                $opr = 'doctor_cancel';
                $reason = $request->reason;
                SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-cancel-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='patient');
                SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-cancel-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='doctor');
                sendSms($patient,$doctor_details,$app_details,"Cancelled");                
            }

        return redirect('/doctor-dashboard'.'/'.$type)->with('success', 'Appointment ' . $Update_data['status'] . ' successfully!');
        } else {
            return redirect('/doctor-dashboard'.'/'.$type)->with('error', 'Appointment id not found');
        }
    }

    public function confirm_appointment_v2_post(Request $request)
    {

        // if ($request->isMethod('post')) 

        if ($request->isMethod('post')) {
            $doctor_id = $request->doctor_id;
            $appointment_id = $request->appointment_id;

            if ($appointment_id != null && $doctor_id != null) {

                $appointmentData = Schedule::findOrFail($appointment_id);

                if($appointmentData && $appointmentData != ''){
                    $slot_id = $appointmentData['slot_id'];
                    $user = $this->getSessionData('user');
                    if($user && $user != ''){
                        $DoctorUser['doctor_id'] = $user['id'];
                        DB::table('tbl_available_schedule_slots')
                            ->where('id', $slot_id)
                            ->update($DoctorUser);
                    }
                }

                $Update_data['status'] = 'Confirmed';
                $Update_data['doctor_id'] = $doctor_id;
                $res = DB::table('tbl_appointments_booked')
                    ->where('id', $appointment_id)
                    ->update($Update_data);

                return redirect('/not-confirmed-appintments')->with('success', 'Appointment ' . $Update_data['status'] . ' successfully!');
            } else {

                return redirect('/not-confirmed-appintments')->with('error', 'Appointment id not found');
            }
        } else {
            return redirect('/not-confirmed-appintments')->with('error', 'Invalid method, Form not posted');
        }
    }

    public function confirm_appointment_v3_post(Request $request)
    {
        // dd($request);
        // if ($request->isMethod('post')) 

        // echo'<pre>';print_r($_POST);die;

      $AppData = null;
      try
      {
        $not_confirm_appointment = '';
        
        if ($request->isMethod('post')) {
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
    
                            $loggedin_doctor_id = $this->getSessionData('user');
        
                            //release slot                            
                            // echo "AppData<br>";
                            // var_dump($AppData);
                            // echo "<br>";
                            //free slot
                            //slot_id of $AppData[0]->doctor_id set booked = 0 and is availabel = 1
                            $res = DB::table('tbl_available_schedule_slots')
                            ->where('id', $AppData[0]->slot_id)
                            ->update(["is_available"=>"1","booked"=>"0"]);
        
                            // echo "<br>";
                            // echo 'old slot updated ["is_available"=>"1","booked"=>"0"]'." Res:: ".$res;
                            // echo "<br>";
                            // echo "old slot id". $AppData[0]->slot_id;
                            // echo "<br>";
                            
                            //get old doctor slot data
                            $old_doctor_slot = "SELECT * FROM tbl_available_schedule_slots 
                            where id = ".$AppData[0]->slot_id;
                            
                            $old_doctor_slot = DB::select($old_doctor_slot);
                            $old_doctor_slot = collect($old_doctor_slot);
        
                            if($old_doctor_slot->isEmpty())
                            {
                                    return redirect('/not-confirmed-appintments')->with('error', 'Old slot not found in database.appointment cannot be swaped.');
                            }

                            //get new doctors slot_id
                            // echo "<br>";
                            // echo "old_doctor_slot";
                            // print_r($old_doctor_slot);
                            
                            // echo 'Not confirmed error : start : '.$old_doctor_slot[0]->start.' end :'.$old_doctor_slot[0]->end.' loggedin_doctor_id :'.$loggedin_doctor_id->id;
                            // echo "<br>";
                            
                            if(!empty($old_doctor_slot[0]->start) &&  !empty($old_doctor_slot[0]->end) && !empty($loggedin_doctor_id->id))
                            {                            
                                    
                                    $new_doctors_slot_id = "SELECT * FROM tbl_available_schedule_slots 
                                    where booked = 0 AND is_available = 1 AND `start` = '".$old_doctor_slot[0]->start."' AND end = '".$old_doctor_slot[0]->end."' AND doctor_id =".$loggedin_doctor_id->id;
        
                                    $new_doctors_slot_id = DB::select($new_doctors_slot_id);
                                    $new_doctors_slot_id = collect($new_doctors_slot_id);
                            
                                    if($new_doctors_slot_id->isNotEmpty())
                                    {
                                        //update app record with slot id
                                        
                                        if(!empty($new_doctors_slot_id[0]->id))
                                        {
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
                                        ->update(["is_available"=>0,"booked"=>1]);
                                             
                                        // print_r($Update_data);     
                                        // dd($res );   
                                    
                                        }
                                        else
                                        {
                                            $dtime = new DateTime($old_doctor_slot[0]->start);                        
                                            $dtime = $dtime->format("m/d/y H:i");
                                            return redirect('/not-confirmed-appintments')->with('error', 'Your slot at DateTime:'.$dtime.' is not found, Please add and try again.');
                                
                                        }
                                    
        
        
                                    }
                                    else
                                    {                        
                                            $dtime = new DateTime($old_doctor_slot[0]->start);                        
                                            $dtime = $dtime->format("m-d-y H:i");
                                            return redirect('/not-confirmed-appintments')->with('error', 'Your slot at DateTime:'.$dtime.' is not found, Please add and try again.');
                                
                                    }
                                    
        
                            }
                            else
                            {
                                // Log::info('Not confirmed error : start : '.$old_doctor_slot[0]->start.' end :'.$old_doctor_slot[0]->end.' loggedin_doctor_id :'.$loggedin_doctor_id->id);
                                return redirect('/not-confirmed-appintments')->with('error', 'Cannot confirm, Please try again.');
                                
                            }
                    
                    
                    
                }
                elseif($not_confirm_appointment == '')
                {
        
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

                SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-confirm-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $phone_meeting_link, $rec_by='patient');
                SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-confirm-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $phone_meeting_link, $rec_by='doctor');                
                sendSms($patient,$doctor_details,$app_details,"Confirm");                

                if(!empty($not_confirm_appointment) && $not_confirm_appointment == '1'){
                    return redirect('/not-confirmed-appintments')->with('success', 'Appointment confirmed successfully!');
                
                } 

                if(empty($not_confirm_appointment))
                {
                    $dateFromAppData = date('Y-m-d', strtotime($AppData[0]->start));
                    $today = date('Y-m-d');

                    if($dateFromAppData == $today)
                    {                        
                        return redirect('/doctor-dashboard/todays')->with('success', 'Appointment ' . $Update_data['status'] . ' successfully!');
                    }

                    if($dateFromAppData > $today)
                    {                        
                        return redirect('/doctor-dashboard/upcoming')->with('success', 'Appointment ' . $Update_data['status'] . ' successfully!');
                    }

                    if($dateFromAppData < $today)
                    {                        
                        return redirect('/doctor-dashboard/past')->with('success', 'Appointment ' . $Update_data['status'] . ' successfully!');
                    }
                }
            } else {

                if($not_confirm_appointment == '')
                return redirect('/doctor-dashboard/new')->with('error', 'appointment id OR phone/link cannot be blank');

                if($not_confirm_appointment === '1')
                return redirect('/doctor-dashboard/not-confirmed-appintments')->with('error','appointment id OR phone/link cannot be blank');
            }
        } else {

            // if($not_confirm_appointment == '')
            // return redirect('/doctor-dashboard/new')->with('error', 'Invalid method, Form not posted');
        }
      }
      catch(\Exception $e)
        {
           

            $controllerName = class_basename(get_class($this));
            Log::error('Date::----------------'.date("Y-m-d H:i:s")."-------------------------");
            Log::error('Class::---------------'.$controllerName.'');
            Log::error('Function::---------------'.__METHOD__.'');
            Log::error('Line::---------------'.__LINE__); // Logs the line number of this statement
            Log::error('Exception: '.$e->getMessage());
            Log::error('Exception Line: '.$e->getLine()); // Logs the line number where the exception was thrown

        }
    }



    public function confirm_appointment_v2(Request $request, $slot_name = null, $appointment_id = null)
    {

        $AppData = null;

        if ($slot_name == 'confirm') {
            $Update_data['status'] = 'Confirmed';
            $type = '';
            $opr = 'doctor_confirm';
            $reason = $request->reason;
        } else if ($slot_name == 'reject') {
            $Update_data['status'] = 'Rejected';
            $Update_data['CancelPatientOrDoctor'] = 2;
            $type = 'rejected';
            $reason = $request->reason;
        } else if ($slot_name == 'cancel') {
            $Update_data['status'] = 'Cancelled';
            $Update_data['CancelPatientOrDoctor'] = 2;
            $reason = $request->reason;
        }

        if ($appointment_id != null) {
            $Appsql = "SELECT * FROM tbl_appointments_booked where id = $appointment_id";
            $app_details = DB::select($Appsql);
            $AppData = collect($app_details);
            
            $res = DB::table('tbl_appointments_booked')
                ->where('id', $appointment_id)
                ->update($Update_data);

                $patient = dbl_users::where('id', $AppData[0]->patient_id)->first();
                $doctor_details = dbl_users::where('id', $AppData[0]->doctor_id)->first();
                $app_details = Schedule::findOrFail($appointment_id);
                
                if($slot_name == 'reject'){
                    $subject = 'Appointment Rejected Successfully';
                    $opr = 'doctor_reject';
                    $reason = $request->reason;
                    SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-reject-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='patient');
                    SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-reject-by-doctor-email-template', $patient, $doctor_details, $app_details, $opr, NULL, $reason, $rec_by='doctor');
                    sendSms($patient,$doctor_details,$app_details,"Rejected");                

                } else if($slot_name == 'cancel'){
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

            return redirect('/not-confirmed-appintments')->with('success', 'Appointment ' . $Update_data['status'] . ' successfully!');
        } else {
            return redirect('/not-confirmed-appintments')->with('error', 'Appointment id not found');
        }
    }

    public function getNewAccessToken()
    {
        $client_id = 'CcidIclnQk6M2IavxUObIg';
        $client_secret = 'Hkq2Sv84SqH5XHCbm7LsvHT9RUpqFYP3';
        $account_id = '46z_9cJMS72GlfxLOwrVGQ';
        
        $token_url = 'https://zoom.us/oauth/token';
        
        // Set up basic authorization with your client ID and secret
        $headers = [
        'Authorization: Basic ' . base64_encode($client_id . ':' . $client_secret),
        'Content-Type: application/x-www-form-urlencoded'
        ];
        
        // Set the request data
        $data = [
        'grant_type' => 'account_credentials',
        'account_id' => $account_id
        ];
        
        // Initialize cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $token_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        
        // Execute the request
        $response = curl_exec($ch);
        
        // Check for errors
        if (curl_errno($ch)) {
        // echo 'Error:' . curl_error($ch);
        // return response()->json(['error' => curl_error($ch)], 500);
        return ["error"=>curl_error($ch)];
        exit;
        }
        
        // Close the cURL session
        curl_close($ch);
        
        // Decode the JSON response to get the access token
        $response_data = json_decode($response, true);
        
        $access_token = '';
        $expires_in = '';
        
        
        if (isset($response_data['access_token'])) {
        $access_token = $response_data['access_token'];
        $expires_in = $response_data['expires_in'] ?? 3600; // Time in seconds
        
        return ["access_token"=>$access_token,"expires_in"=>$expires_in];
        // Store the access token and expiration time in the session
        
        } else {
        // return response()->json(['error' => "Failed to obtain access token."], 500);
        return ["error"=>'Failed to obtain access token'];
        exit;
        }
    }
    
    public function generate_zoom_meeting_link(Request $request)
    {
        $appointmentId = $request->input('appointment_id');
        
        // Fetch the access token and create a Zoom meeting (similar to what we discussed earlier)
        
        $access_token = Cache::get('zoom_access_token');
        
        if (!$access_token) {
            // If no token exists or it's expired, generate a new token
            $new_token = $this->getNewAccessToken();
            
            // Store new token and its expiry time in cache
            if(isset($new_token['access_token']) && isset($new_token['expires_in']))
            {
            Cache::put('zoom_access_token', $new_token['access_token'], $new_token['expires_in'] / 60);
            Cache::put('zoom_token_expiry', now()->addSeconds($new_token['expires_in']));
            }
            
            if(isset($new_token['error']))
            {
                return response()->json(['error' => $new_token['error']], 500);
            }
            
            $access_token = $new_token['access_token'];
        }

        
        $meeting_data['join_url'] = '';

        // create meeting
        
                $doctor_email = 'zahoor.aviontech@gmail.com';
                // The doctor's email or user ID
                
                // Check if user exists
                $user_check_url = 'https://api.zoom.us/v2/users/' . $doctor_email;
                $user_check_ch = curl_init();
                curl_setopt($user_check_ch, CURLOPT_URL, $user_check_url);
                curl_setopt($user_check_ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($user_check_ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $access_token,
                'Content-Type: application/json',
                ]);
                
                $user_check_response = curl_exec($user_check_ch);
                curl_close($user_check_ch);
                
                $user_data = json_decode($user_check_response, true);
                
                // If user does not exist (i.e., the response returns a "User not found" error)
                if (isset($user_data['code']) && $user_data['code'] == 1001) 
                { // 1001 is the error code for "User not found"
                        // Create a new user
                        $create_user_url = 'https://api.zoom.us/v2/users';
                        $new_user_data = [
                        'action' => 'create',
                        'user_info' => [
                        'email' => $doctor_email,
                        'first_name' => 'Doctor',
                        'last_name' => 'Secondlookortho',
                        'type' => 1 // User type (1 = Basic, 2 = Pro, etc.)
                        ]
                        ];
                        
                        $create_user_ch = curl_init();
                        curl_setopt($create_user_ch, CURLOPT_URL, $create_user_url);
                        curl_setopt($create_user_ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($create_user_ch, CURLOPT_POST, true);
                        curl_setopt($create_user_ch, CURLOPT_POSTFIELDS, json_encode($new_user_data));
                        curl_setopt($create_user_ch, CURLOPT_HTTPHEADER, [
                        'Authorization: Bearer ' . $access_token,
                        'Content-Type: application/json',
                        ]);
                        
                        $create_user_response = curl_exec($create_user_ch);
                        curl_close($create_user_ch);
                        
                        $created_user_data = json_decode($create_user_response, true);
                        // Handle the response for user creation if needed
                        if (isset($created_user_data['error'])) {
                        return response()->json(['error' => 'Failed to create user: ' . $created_user_data['error']], 500);
                        }
                }
                else{

                    Log::info('Date::----------------'.date("Y-m-d H:i:s")."-------------------------");
                    Log::info('user found on zoom ::'.$doctor_email);                   

                }
                

                // Zoom API URL to create a meeting
                $meeting_url = 'https://api.zoom.us/v2/users/' . $doctor_email . '/meetings';
                
                // Meeting details
                $meeting_data = [
                'topic' => 'Doctor Appointment',
                'type' => 2, // 2 = Scheduled meeting
                'start_time' => date('Y-m-d').'T00:00:30', // Scheduled start time (in UTC)
                'duration' => 30, // Meeting duration in minutes
                'timezone' => 'UTC', // Timezone
                ];
                
                // Initialize cURL
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $meeting_url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($meeting_data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $access_token,
                'Content-Type: application/json',
                ]);
                
                // Execute the request
                $response = curl_exec($ch);
                
                // Check for errors
                if (curl_errno($ch)) {
                return response()->json(['error' => curl_error($ch)], 500);
                exit;
                }
                
                if($response === false) {
                return response()->json(['error' => curl_error($ch)], 500);
                exit;
                } 
                
                // Close cURL
                curl_close($ch);
                
                // Decode and display the response (meeting details)
                $meeting_data = json_decode($response, true);
                
                if (isset($meeting_data['join_url'])) {
                    
                    $join_url = $meeting_data['join_url'] ?? null;

                    if ($join_url) {
                        return response()->json([
                            'join_url' => $join_url
                            ]);
                    } else {
                        return response()->json(['error' => 'Failed to create meeting'], 500);
                    }
                }
                else
                {
                     return response()->json(['error' => $response], 500);
                }
                
                //create meeting close
        
    }    
   

   
}
