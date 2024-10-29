<?php

namespace App\Http\Controllers;

use App\Models\dbl_users;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class AdminController extends Controller
{
        /**sssss
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {   
        $sevenDaysAgo = Carbon::now()->subDays(7)->format('Y-m-d');
        $oneYearAgo = Carbon::now()->subDays(365)->format('Y-m-d');

        $PatientSql = "SELECT * FROM dbl_users where `user_type` = 'patient' AND `status` = 'Active' AND date(created_at) >='".$sevenDaysAgo."'";
        $patient = DB::select($PatientSql);
        $PatientData = collect($patient);
        $data['PatientData'] = count($PatientData);

        $DoctorSql = "SELECT * FROM dbl_users where `user_type` = 'doctor'";
        $doctor = DB::select($DoctorSql);
        $DoctorData = collect($doctor);
        $data['DoctorData'] = count($DoctorData);

        $ActiveDoctorSql = "SELECT * FROM dbl_users where `user_type` = 'doctor' AND `status` = 'Active'";
        $active_doctor = DB::select($ActiveDoctorSql);
        $ActiveDoctorData = collect($active_doctor);
        $data['ActiveDoctorData'] = count($ActiveDoctorData);

        $TotalPatientSql = "SELECT * FROM dbl_users where `user_type` = 'patient' AND `status` = 'Active' AND date(created_at) >='".$oneYearAgo."'";
        $total_patient = DB::select($TotalPatientSql);
        $TotalPatientData = collect($total_patient);
        $data['TotalPatientData'] = count($TotalPatientData);

        $UpcomeAppSql = "SELECT * FROM tbl_appointments_booked 
                  WHERE (`status` = 'Confirmed' OR `status` = 'In-Process') 
                  AND DATE(start) >= '".date('Y-m-d')."'";
    //   echo $UpcomeAppSql;die();


        $upcome_appointment = DB::select($UpcomeAppSql);
        $UpcomeAppData = collect($upcome_appointment);
        $data['UpcomeAppData'] = count($UpcomeAppData);

        $CancelAppSql = "SELECT * FROM tbl_appointments_booked where `status` = 'Cancelled'";
        $cancel_appointment = DB::select($CancelAppSql);
        $CancelAppData = collect($cancel_appointment);
        $data['CancelAppData'] = count($CancelAppData);

        $CompleteAppSql = "SELECT * FROM tbl_appointments_booked where (`status` != 'Cancelled' OR `status` != 'Rejected')";
        $complete_appointment = DB::select($CompleteAppSql);
        $CompleteAppData = collect($complete_appointment);
        $data['CompleteAppData'] = count($CompleteAppData);

        // new code
         // Get today's date
         $today = Carbon::today();

         // Calculate today's total
         $todayTotal = Payment::whereDate('created_on', $today) // Adjust the column name if necessary
             ->sum('txn_amount');
              
           $todaytotalpatientcount = Payment::whereDate('created_on', $today) 
             ->whereNotNull('patient_id') // Ensure patient_id is not null
             ->count();
         
         $data['todaytotalpatientcount'] = $todaytotalpatientcount;

 
         // Calculate the total for all time
         $totalAmount = Payment::sum('txn_amount');
         
         $data['totalpatientcount'] = Payment::whereNotNull('patient_id')
                                               ->distinct('patient_id') // Select distinct patient_id
                                              ->count();
        //  dd($today);
 
       
             $data['today_total'] = $todayTotal;
             $data['total_amount'] = $totalAmount;
             $TotalPatientSqltoday = "SELECT * FROM dbl_users where `user_type` = 'patient' AND date(created_at) >='".$today."'";
             $today_patient = DB::select($TotalPatientSqltoday);
             $TodayTotalPatientData = collect($today_patient);
             $data['TodayTotalPatientData'] = count($TodayTotalPatientData);
     

             $lastWeek = Carbon::today()->subDays(7);

                // Query for patients added in the last week
                $TotalPatientSqlLastWeek = "SELECT * FROM dbl_users WHERE `user_type` = 'patient' AND created_at >= ?";
                $lastWeekPatients = DB::select($TotalPatientSqlLastWeek, [$lastWeek]);

                // Count the number of patients
                $LastWeekTotalPatientData = collect($lastWeekPatients);
                $data['LastWeekTotalPatientData'] = count($LastWeekTotalPatientData);
        // echo'<pre>';print_r($data);die;
        return view('pages.admin_dashboard', $data);
    }

    
}
