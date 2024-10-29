<?php
namespace App\Http\Controllers;
use App\Models\Schedule;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule as SchedulingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ScheduleController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data = [];
        $doctor_sql = "SELECT * FROM dbl_users where user_type = 'doctor' order by first_name ASC";
        $doctor_Data = DB::select($doctor_sql);
        $data['result']['doctors'] = collect($doctor_Data);
                // dd($data);

        return view("schedule.index",$data);
    }

    public function getEvents(Request $request)
    {
        // $doctorId = $request->query('doctor_id');
        // $status = $request->query('status');
        // dd($status);        
        // $schedules = $doctorId
        // ? Schedule::where('doctor_id', $doctorId)->with('state', 'city')->get()
        // : Schedule::all();
        // //  Schedule::with('state', 'city')->get();
        
        $status = $request->query('status');
        $doctorId = $request->query('doctor_id');
        
        DB::enableQueryLog();

        $schedules = Schedule::query();       
        
        if ($doctorId) {
            $schedules->where('doctor_id', $doctorId);
        }
        
        if (!empty($status)) {
            if($status == "Expired")
            {
                $schedules->where('status', 'In-Process');
                $schedules->where('start','<', date("Y-m-d H:i:s"));
            }
            else
            {
                $schedules->where('status', $status);
            }
        }

        if (empty($status)) 
        {
            $schedules->where("status","!=","Un-Paid");
        }
        // $schedules->whereNotNull('payment_id'); 
        // $schedules->where('payment_id', '!=', ''); 
        // $schedules->whereOr('follow_up',1);       
        // $schedules->where('active', '1');

        // $schedules = $schedules->with(['state', 'city','user'])->get();



        $schedules = $schedules->where(function($query) {
            $query->whereNotNull('payment_id') // Condition for payment_id not null
                  ->where('payment_id', '!=', '') // Ensure payment_id is not an empty string
                  ->where('active', '1'); // Ensure the record is active
        })
        ->orWhere(function($query) {
            $query->whereNull('payment_id') // Condition for payment_id null
                  ->where('follow_up', '1'); // Ensure followup is 1
        })
        ->with(['state', 'city', 'user']) // Eager load related models
        ->get();
    
        $queries = DB::getQueryLog();
        
// echo print_r($schedules);die();
        // return response()->json($schedules);
        
        $schedulesArray = $schedules->map(function($schedule) {
            if($schedule->notes != ''){
                $doctor_prescription = Crypt::decrypt($schedule->notes);
            } else {
                $doctor_prescription = '';
            }
            return array_merge(
                $schedule->toArray(), // Include all attributes
                [
                    'prec' => $doctor_prescription,
                    // 'appointment_start' => $schedule->appointment_start,
                    // 'appointment_end' => $schedule->appointment_end,                    
                ]
            );
        });
    
        return response()->json($schedulesArray);
    
    }

    public function create(Request $request)
    {
        $item = new Schedule();
        
        if($request->slot_id >0 && $request->doctor_id > 0 && $request->patient_id > 0)
        {
        
        $sql = "SELECT * FROM tbl_available_schedule_slots where id = ".$request->slot_id;
        $slots = DB::select($sql);
        $slots = collect($slots);
        $slots = $slots->first();
        
        $item->start = $slots->start;
        $item->end = $slots->end;
        $item->slot_id = $slots->id;
        
        $sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND id=".$request->doctor_id;
        $doctor = DB::select($sql);
        $doctor = collect($doctor);
        $doctor = $doctor->first();
        
        $sql = "SELECT * FROM dbl_users where user_type = 'patient' AND id=".$request->patient_id;
        $patient = DB::select($sql);
        $patient = collect($patient);
        $patient = $patient->first();
        
        $start_datetime = Carbon::parse($slots->start);
        $end_datetime = Carbon::parse($slots->end);
        
        $start_time = $start_datetime->format('H:i');
        $end_time = $end_datetime->format('H:i');
        
        $item->title = "Dr.".$doctor->first_name.":".$start_time." To ".$end_time;
        
        $item->description = $request->description;
        // $item->color = $request->color;
        $item->doctor_id = $request->doctor_id;
        $item->patient_id = $request->patient_id;
        
        
        if (isset($_FILES['files']) && !empty($_FILES['files']['name'][0])) 
        {
            $fileNames = [];
            $errors = [];

                foreach ($_FILES['files']['name'] as $key => $name) {
                    $tmpName = $_FILES['files']['tmp_name'][$key];
                    $size = $_FILES['files']['size'][$key];
                    $error = $_FILES['files']['error'][$key];
                    
                    // Validate file
                    if ($error === UPLOAD_ERR_OK) {
                        // Specify allowed file types and size limit (e.g., 2MB)
                        $allowedTypes = ['jpg', 'png'];
                        $maxSize = 2 * 1024 * 1024; // 2MB
                        $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
        
                        if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                            // Move uploaded file to the 'uploads' directory
                            $filePath = 'public/patient_reports/' . uniqid() . '_' . $name;
                            if (move_uploaded_file($tmpName, $filePath)) {
                                // Save file information to the database
                                
                                $fileNames[] = $name;
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
                $item->report_file_names = $report_file_names;
                
                if (!empty($errors)) {
                    echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                    die;
                } else {
                    // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                    // die;
                }
        } else {
            // echo '<div class="alert alert-warning">No files selected for upload.</div>';
            // die;
        }
    
        
        
        
        $item->save();
        }

        return redirect('/schedule');
    }

}
