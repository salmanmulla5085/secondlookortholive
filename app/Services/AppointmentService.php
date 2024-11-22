<?php
namespace App\Services;

use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\PersonalAccessToken;
use App\Models\dbl_users; // Adjust the namespace as needed

class AppointmentService
{
    public function getUserFromToken($token)
    {
        $tokenRecord = PersonalAccessToken::findToken($token);

        if (!$tokenRecord) {
            return null; // Token is invalid
        }

        return $tokenRecord->tokenable; // Return the associated user
    }

    public function fetchAppointments($user, $record_type)
    {
        // $sql = "SELECT * FROM tbl_appointments_booked 
        //         WHERE patient_id = ? AND `status` != 'Cancelled'";

        // if ($record_type === 'upcoming') {
        //     $sql .= " AND start >= ? order by ab.start DESC";
        // } else {
        //     $sql .= " AND start < ? order by ab.start DESC";
        // }

        // // Execute the query with bindings
        // $appointments = DB::select($sql, [$user->id, date('Y-m-d H:i:s')]);


        // Start building the query
        if($user->user_type == 'patient')
        {
                if ($record_type === 'upcoming') {
                    $sql = "SELECT ab.*,u.* FROM tbl_appointments_booked ab LEFT JOIN dbl_users u ON ab.doctor_id = u.id
                    where ab.active = 1 AND ab.appointmentType !='Report Review' AND 
                    ab.patient_id = $user->id AND ab.start >= now()";
                }
                if ($record_type === 'past') 
                {
                    $sql = "SELECT ab.*,u.*  FROM tbl_appointments_booked ab LEFT JOIN dbl_users u ON ab.doctor_id = u.id
                    where ab.active = 1 AND ab.appointmentType !='Report Review' AND 
                    ab.patient_id = $user->id AND ab.start < now()";
                }                  
                
                
        }

        if($user->user_type == 'doctor')        
        {
            if ($record_type == "new") {
                $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
            ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
            ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state 
            FROM tbl_appointments_booked ab 
            LEFT JOIN dbl_users u ON ab.doctor_id = u.id 
            where ab.active = 1 AND ab.status = 'In-Process' AND ab.doctor_id = $user->id 
            AND ab.start > NOW() order by ab.start desc";
            } elseif ($record_type == "past") {
                $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
            ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
            ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state 
            FROM tbl_appointments_booked ab 
            LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
            ab.active = 1 AND ab.status != 'Cancelled' AND ab.status != 'Rejected' 
            AND ab.doctor_id = $user->id AND ab.start <' " . date('Y-m-d H:i:s') . "' order by ab.start desc";
            } elseif ($record_type == "todays") {
                $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
            ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
            ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state 
            FROM tbl_appointments_booked ab 
            LEFT JOIN dbl_users u ON ab.doctor_id = u.id where 
            ab.active = 1 AND (ab.status = 'Confirmed' || ab.status = 'Completed')  
            AND ab.doctor_id = $user->id 
            AND DATE(ab.start) =' " . date('Y-m-d') . "' order by ab.start desc";
            } elseif ($record_type == "rejected") {
                $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
            ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
            ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state 
            FROM tbl_appointments_booked ab 
            LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.doctor_id = $user->id AND (ab.status ='Rejected' OR ab.status ='Cancelled') order by ab.start desc";
            } elseif ($record_type == "upcoming") {
                $sql = "SELECT ab.completed_at,ab.upload_file1,ab.phone_meeting_link,ab.id,ab.patient_id, ab.start,ab.end, ab.doctor_id , ab.symptoms, ab.reports, ab.description, ab.appointmentType, ab.status as appointment_status,ab.category,
            ab.amount,ab.interests, ab.report_file_names,ab.medicalDocuments,ab.notes,
            ab.name AS patient_name, u.phone_number as patient_phone_number,ab.city,ab.state 
            FROM tbl_appointments_booked ab 
            LEFT JOIN dbl_users u ON ab.doctor_id = u.id where ab.active = 1 AND ab.doctor_id = $user->id AND ab.status = 'Confirmed' AND DATE(ab.start) >' ".date('Y-m-d')."' order by ab.start desc";
            }           

        }
       
        
        $appointments_booked = DB::select($sql);
        $appointments_booked = collect($appointments_booked);
        

        return ['appointments'=> collect($appointments_booked), 'debug_sql'=>$sql];
    }

    protected function replaceBindings($sql, $bindings)
    {
        foreach ($bindings as $binding) {
            $binding = is_numeric($binding) ? $binding : "'" . addslashes($binding) . "'";
            $sql = preg_replace('/\?/', $binding, $sql, 1);
        }
        return $sql;
    }
}
