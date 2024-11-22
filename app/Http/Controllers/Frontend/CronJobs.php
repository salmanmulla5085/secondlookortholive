<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Schedule;
use App\Models\dbl_users;
use Carbon\Carbon;
use App\Services\SendGridService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

class CronJobs extends Controller
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

    /*this cron is used to update is avialable = 1 because patient 
    select doctor but not did payment then select slot should be avaible after 1 hour*/ 
    public function UpdateLockTimeAndBooked()
    {
        try
        {
                // $nowDateTimeBeforeOne = Carbon::now()->subHour()->toDateTimeString();
                $nowDateTimeBeforeFifteen = Carbon::now()->subMinutes(5)->toDateTimeString();
                $sql = "SELECT * FROM tbl_available_schedule_slots where lock_time IS NOT NULL AND 
                lock_time < '".$nowDateTimeBeforeFifteen."' AND is_available = 0 AND booked = 0";
                $GetData = DB::select($sql);

                if($GetData && $GetData != '')
                {
                    
                    Log::info("START-------------------------:: Cron UpdateLockTimeAndBooked---------".date("Y-m-d H:i:s")."--------------");
                    
                    foreach ($GetData as $key => $SlotVal) {
                        $res = DB::table('tbl_available_schedule_slots')
                            ->where('id', $SlotVal->id)
                            ->update(["is_available"=>"1","lock_time"=>null]);

                            Log::info("updated table tbl_available_schedule_slots:  slot id : ".$SlotVal->id." to is_available :1");
                    
                    }
                    Log::info("END---------------------:: Cron UpdateLockTimeAndBooked------------");
                }
        }
        catch(\Exception $e)
        {
            Log::error('Cron Error :: UpdateLockTimeAndBooked----'. $e->getMessage());
        }
        
        

    }

    /*this cron is used to send the email for patient as well as doctor which appointment will start in 15 minutes*/ 
    public function SendAppReminderToPatientAndDoctor()
    {
        $currentDateTime = Carbon::now()->toDateTimeString(); // Current time
        $endDateTime = Carbon::now()->addMinutes(15)->toDateTimeString(); // Current time + 15 minutes

        $sql = "SELECT * FROM tbl_appointments_booked 
                WHERE status = 'Confirmed' 
                AND send_reminder = 0
                AND start >= ? 
                AND start <= ?";

        $GetData = DB::select($sql, [$currentDateTime, $endDateTime]);

        if($GetData && $GetData != ''){

            Log::info("Cron SendAppReminderToPatientAndDoctor----------SQL:".$sql);

            foreach ($GetData as $key => $AppVal) {
    
                
                $patient = dbl_users::where('id', $AppVal->patient_id)->first();
                $doctor_details = dbl_users::where('id', $AppVal->doctor_id)->first();
                $app_details = Schedule::findOrFail($AppVal->id);
                
                $subject = 'Appointment Reminder';
                $opr = 'appointment_reminder';

                SendEmail($this->sendGridService, $patient->email_address, $subject, 'emails.app-reminder-email-template', $patient, $doctor_details, $app_details, $opr, NULL, NULL, $rec_by='patient');
                SendEmail($this->sendGridService, $doctor_details->email_address, $subject, 'emails.app-reminder-email-template', $patient, $doctor_details, $app_details, $opr, NULL, NULL, $rec_by='doctor');
                sendSms($patient,$doctor_details,$AppVal,"Reminder");                
                sendSms($patient,$doctor_details,$AppVal,"Reminder_To_Doctor");                
                
                $res = DB::table('tbl_appointments_booked')
                    ->where('id', $AppVal->id)
                    ->update(["send_reminder"=>1]);
            }
        }
    }

    public function UpdateNotConfirmed()
    {

            try
                            {

                                $affectedRows = DB::table('tbl_appointments_booked')
                                ->where('status', 'In-Process')
                                ->where('NotConfirmed', '0')
                                ->where('created_at', '<', now()->subHours(24))
                                ->update(['NotConfirmed' => 1]);
                                                                
                                if($affectedRows > 0) {
                                    // Log the SQL query, bindings, and time taken
                                    Log::info("START---------------------Cron UpdateNotConfirmed---------".date("Y-m-d H:i:s")."--------------");
                                    Log::info("Total Updated Records: " . $affectedRows);                                    
                                    Log::info("END---------------------:: Cron UpdateNotConfirmed------------");
                                }
                                else
                                {
                                    
                                }
                                
                                
                            }
                            catch(\Exception $e)
                            {
                                Log::error('Cron UpdateNotConfirmed Error:'. $e->getMessage());
                            }
                            
                            
        }


}