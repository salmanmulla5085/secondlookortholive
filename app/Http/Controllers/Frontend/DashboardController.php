<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\UsState;
use App\Models\UsCity;
use App\Models\Schedule;
use App\Models\dbl_users;
use App\Mail\WelcomeMail;
// use PDF;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\SendGridService;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session; // Import Session facade

class DashboardController extends Controller
{
    protected $sendGridService;
    public function __construct(Request $request, SendGridService $sendGridService)
    {

        $this->middleware(function ($request, $next){
            $user = Session::get("user");
            // dd($user);
    
            return $next($request);
        });

        $this->sendGridService = $sendGridService;

    }

    
    public function reschedule_appointment($appointment_id = null, $cat_type = null){
        
        if($appointment_id != null)
        {
            $appointment_id = Crypt::decrypt($appointment_id);
            if($cat_type && $cat_type != null){
                $this->setSessionData("cat_type",$cat_type);
            } else {
                $this->setSessionData("cat_type",'');
            }
        
        // $this->setSessionData('patient_rescheduled_appointment_id', $appointment_id);

        $Appsql = "SELECT * FROM tbl_appointments_booked where id = $appointment_id";
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
        
        return redirect('/book_appointment/0/' . Crypt::encrypt($appointment_id))->with('warning', 'Please Reschedule this appointment!');
        }
        else
        return redirect('/patient-dashboard')->with('error', 'Appointment id not found');

    }
    

    public function cancel_appointment($appointment_id = null){
        
        if($appointment_id != null){
            $appointment_id = Crypt::decrypt($appointment_id);
            //Logic for appointment timeslot become free.
            $Appsql = "SELECT * FROM tbl_appointments_booked where id = $appointment_id";
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
                                ->where('id', $appointment_id)
                                ->update($Update_data);  
                                
            $patient = dbl_users::where('id', $AppData[0]->patient_id)->first();
            $doctor_details = dbl_users::where('id', $AppData[0]->doctor_id)->first();
            $app_details = Schedule::findOrFail($appointment_id);
            $subject = 'Appointment Cancelled Successfully';

            SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-cancel-by-patient-email-template', $patient, $doctor_details, $app_details, NULL, NULL, NULL, $rec_by='patient');
            SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-cancel-by-patient-email-template', $patient, $doctor_details, $app_details, NULL, NULL, NULL, $rec_by='doctor');
            
            // sendSms($patient,$doctor_details,$app_details,"Cancelled");                
            sendSms($patient,$doctor_details,$app_details,"Cancelled_To_Doctor"); 

            return redirect('/patient-dashboard')->with('success', 'Appointment cancelled successfully!');
        } else {
            return redirect('/patient-dashboard')->with('error', 'Appointment id not found');
        }
    }

    // public function doctor_details($id = null){
    //     $sql = "SELECT * FROM dbl_users where id = $id";
    //     $data = [];
    //     $doctor_details = DB::select($sql);
    //     $doctor_details = collect($doctor_details);					                
    //     $data["doctor"] =  $doctor_details;
    //     $data['PageName'] = 'Doctor Details';
    //     return view('frontend.doctor-details',$data);
    // }

    public function doctor_details($id = null){
        $sql = "SELECT * FROM dbl_users where id = $id";
        $data = [];
        $doctor_details = DB::select($sql);
        $doctor_details = collect($doctor_details);					                
        $data["doctor"] =  $doctor_details[0];
        $data['PageName'] = 'Doctor Details';
        return response()->json($data);
    }
    
    
    public function patient_report_reviews($record_type = "report_review")
    {
        session()->forget('selected_plan');    
 
        $user = $this->getSessionData('user');
        
        $data = [];
        
        $data['PageName'] = 'Report Reviews';
        $data['icon'] = 'patient_icon_01.png';
        
        if(!empty($record_type))
        $data["result"]["record_type"] = $record_type;       
         //   dd($data);
        if(!empty($user))
        {        
         

         $appointments_booked = Schedule::with(['doctor', 'patient']) // Eager load doctor (User model)
            ->where('active', 1)
            ->where('patient_id', $user->id)
            ->where('appointmentType', 'Report Review')
            ->orderBy('created_at','DESC')
            ->get([
                'id', 'patient_id', 'start', 'end', 
                'doctor_id', 'symptoms', 'reports', 'description', 'appointmentType',
                'status as appointment_status', 'category', 'amount', 'interests', 
                'report_file_names', 'medicalDocuments',
                'notes', 'city', 'state', 'created_at'
            ]);
  
            // dd($appointments_booked);


            // Optionally load report reviews replies
            $appointments_booked->load('reportReviewsReplies');
  
              // dd($appointments_booked);
  
            $data["result"]["appointments_booked"] =  $appointments_booked;
         
        }
      
        return view('frontend.patient-report-reviews',$data);
    }

    
   
    public function index($record_type = "upcoming"){
       session()->forget('selected_plan');    

       $user = $this->getSessionData('user');
       
       $data = [];
       
       $data['PageName'] = 'Dashboard';
       $data['icon'] = 'Group(40).png';
       
       if(!empty($record_type))
       $data["result"]["record_type"] = $record_type;       
        //   dd($data);
       if(!empty($user))
       {
       
        $sql = "SELECT * FROM tbl_appointments_booked where patient_id = $user->id 
        AND `status` != 'Cancelled'";
        if($record_type == "upcoming")
        {
        //upcoming sql
        $sql = "SELECT ab.phone_meeting_link,ab.id,ab.patient_id, 
        ab.status as appointment_status, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, 
        ab.category,
        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments, ab.upload_file1,ab.notes,
        u.first_name AS doctor_first_name, u.last_name AS doctor_last_name, u.phone_number as doctor_phone_number, 
        ab.CancelPatientOrDoctor FROM tbl_appointments_booked ab
        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1  
        AND appointmentType != 'Report Review' AND 
        ab.patient_id = $user->id AND ab.end >= '".date('Y-m-d H:i:s')."' order by start DESC";
        }
        else
        {
        $sql = "SELECT ab.phone_meeting_link,ab.id,ab.patient_id, 
        ab.status as appointment_status, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, 
        ab.category,
        ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments, ab.upload_file1, ab.notes,
        u.first_name AS doctor_first_name, u.last_name AS doctor_last_name, u.phone_number as doctor_phone_number, ab.CancelPatientOrDoctor FROM tbl_appointments_booked ab
        LEFT JOIN dbl_users u ON ab.doctor_id = u.id where  ab.active = 1 
        AND appointmentType != 'Report Review' AND 
        ab.patient_id = $user->id AND ab.end <'".date('Y-m-d H:i:s')."' order by start DESC";
        }

        $appointments_booked = DB::select($sql);
        $appointments_booked = collect($appointments_booked);					                
        // dd($appointments_booked);
        $data["result"]["appointments_booked"] =  $appointments_booked;
        
        // dd($appointments_booked);
        // $sql = "SELECT * FROM dbl_users where user_type = 'patient'";
        // $users = DB::select($sql);
        // $patients = collect($users);					                                                          
        
       }
     
       return view('frontend.patient-dashboard',$data);
    }

    public function downloadPDF($id = null)
    {
        if($id != null){
            $id = Crypt::decrypt($id);
            $data['extAppData'] = DB::table('tbl_appointments_booked')
                ->where('id', $id)
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
            return $pdf->download($data['patient']->first_name.'_history.pdf');

            // return view('emails.patient_pdf', $data);
        }
    }
}

