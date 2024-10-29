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

class DoctorScheduleController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data['PageName'] = 'My Calendar';
        $data['icon'] = 'Group(45).png';
        return view("Doctor-schedule.index", $data);
    }

    public function getEvents()
    {
        $user = $this->getSessionData('user');
        $doctor_id = $user->id;
        $schedules = Schedule::with(['state','city'])
        ->where(['doctor_id'=>$doctor_id,'active'=>1, 'status' => 'Confirmed'])
        ->get();

        $schedulesArray = $schedules->map(function($schedule) {
            return array_merge(
                $schedule->toArray(), // Include all attributes
                [
                    'appointment_start' => $schedule->appointment_start,
                    'appointment_end' => $schedule->appointment_end,                    
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

        return redirect('/Doctor-schedule');
    }

}
