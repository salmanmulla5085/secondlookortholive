<?php

namespace App\Http\Controllers\Frontend;


use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\dbl_users;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule as SchedulingSchedule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;



class PatientHistoryController extends Controller
{
    public function index($app_id = null, $patient_id = null, $type = null)
    {
        $data['PageName'] = 'Medical History';
        $data["AppBooked"] = $data["TotalAppBooked"] = '';
        $data['type'] = $type;

        $data['TotalSymtoms'] = $data['TotalAllergies'] = $data['TotalMedicalHis'] = '';

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

        // echo'<pre>';print_r($data);die;
        return view('frontend.patient-history', $data);
    }
}
