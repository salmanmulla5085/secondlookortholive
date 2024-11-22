<?php

namespace App\Http\Controllers;


use App\Models\AvailableScheduleSlots;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule as SchedulingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class AvailableScheduleSlotsController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view("AvailableScheduleSlots.index");
    }

    public function getEvents2(Request $request)
    {
        $doctorId = $request->query('doctor_id');
        $schedules = $doctorId ? AvailableScheduleSlots::where('doctor_id', $doctorId)->get() : AvailableScheduleSlots::all();                
        return response()->json($schedules);
    }

    public function create(Request $request)
    {
        
        $request->validate([
            // 'days' => 'required|array|min:1', 
            // 'days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',
            'doctor_id' => 'required',
            'start' => 'required',
            'end' => 'required',
            'slots' => 'required',
            // 'start' => 'required|date_format:DD/MM/YYYY H:i', 
        ]);
        
        // Start:: Code for two date range and all timeslots of a day 
        
        $startDate = Carbon::parse($request->start);
        $endDate = Carbon::parse($request->end);
        
         while ($startDate <= $endDate) 
         {
            $sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND id=".$request->doctor_id;
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
                
                $item->doctor_id = $request->doctor_id;
            
                // $item->is_available = $request->is_available;
                // $item->color = $request->color;
                // $item->booked = $request->booked;    
                
                $item->save();
            }
            
            $startDate->addDay();
            
        }
        
       

        return redirect('/AvailableScheduleSlots');
    }
    
    
    public function deleteEvent($id)
    {
        $schedule = AvailableScheduleSlots::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Time-slot deleted successfully']);
    }
    
    public function getTimeslots($doctor_id = null,$selected_id = null,$patient_id = null)
    {
        // Fetch available schedules for the given doctor_id and start date id         
        $patient =null;
        $doctor =null;

        if(!empty($doctor_id))
        {
        $sql = "SELECT * FROM dbl_users where id = ".$doctor_id;
        $record = DB::select($sql);
        $collection = collect($record);
        $doctor = $collection->first();
        }

        if(!empty($patient_id))
        {
        $sql = "SELECT * FROM dbl_users where id = ".$patient_id;
        $record = DB::select($sql);
        $collection = collect($record);
        $patient = $collection->first(); 
        
        $to_tz = $patient->timezone; // Replace with your target time zone
        
        }

        $firstRecord = null;

        if(!empty($selected_id))
        {
        $sql = "SELECT * FROM tbl_available_schedule_slots where id = ".$selected_id;        
        $record = DB::select($sql);
        $collection = collect($record);
        $firstRecord = $collection->first();
        }
        
        $start_datetime = $firstRecord->start;        
        $start_datetime = Carbon::parse($start_datetime);        
        $selected_date = $start_datetime->toDateString();        
        $end_datetime = $start_datetime->copy();        
        $end_datetime->addDay();
        $now_plus_30min = "";        
        
        

        $from_tz = $doctor->timezone; // Replace with your source time zone

        

        if($selected_date == date("Y-m-d"))
        {
        
        // echo $selected_date;
        // echo "<br>";

        //means today, so add 30 min to now and show timeslots greater than that.
        $now = Carbon::now();
        
        // echo $now;
        // echo "<br>";

        // $now_plus_30min = $now->addMinutes(30);
        $now_plus_6hours = $now->addHours(6);

        // echo $now_plus_6hours;
        // echo "<br>";
        
        // $sql = "SELECT id, DATE_FORMAT(CONVERT_TZ(start, '{$from_tz}', '{$to_tz}'), '%H:%i') as start, 
        // DATE_FORMAT(CONVERT_TZ(end, '{$from_tz}', '{$to_tz}'), '%H:%i') as end FROM tbl_available_schedule_slots where is_available = 1 AND start >= '".$now_plus_30min."' AND DATE_FORMAT(end, '%Y-%m-%d') < '".$end_datetime->toDateString()."' AND doctor_id = ".$doctor_id;

        $sql = "SELECT id, DATE_FORMAT(start, '%H:%i') as start, 
        DATE_FORMAT(end, '%H:%i') as end FROM tbl_available_schedule_slots where 
        is_available = 1 AND start >= '".$now_plus_6hours."' 
        AND DATE_FORMAT(end, '%Y-%m-%d') < '".$end_datetime->toDateString()."' AND doctor_id = ".$doctor_id;

        // echo $sql;
        }
        else
        {
        // $sql = "SELECT id, DATE_FORMAT(CONVERT_TZ(start, '{$from_tz}', '{$to_tz}'), '%H:%i') as start, 
        // DATE_FORMAT(CONVERT_TZ(end, '{$from_tz}', '{$to_tz}'), '%H:%i') as end FROM tbl_available_schedule_slots where is_available = 1 AND DATE_FORMAT(start, '%Y-%m-%d') = '".$start_datetime->toDateString()."' AND doctor_id = ".$doctor_id;
        
        $sql = "SELECT id, DATE_FORMAT(start, '%H:%i') as start, 
        DATE_FORMAT(end, '%H:%i') as end FROM tbl_available_schedule_slots 
        where is_available = 1 AND DATE_FORMAT(start, '%Y-%m-%d') = '".$start_datetime->toDateString()."' 
        AND doctor_id = ".$doctor_id;
        }

        // $sql = "SELECT id, TIME(start) as start,TIME(end) as end FROM tbl_available_schedule_slots where start >= '".$start_datetime."' AND end < '".$end_datetime."' AND doctor_id = ".$doctor_id;
        

        $schedules = DB::select($sql);
        $schedules = collect($schedules);        
        

        // dd($schedules);
        

        // dd($sql);
        // Return the timeslots as JSON response
        return response()->json(['schedules' => $schedules,'sql'=>$sql,'selected_date_with_current_time'=>$now_plus_30min]);
    }
    
    public function getAvailableDates($doctor_id)
    {
        
        // $sql = "SELECT id, TIME(start) as start,TIME(end) as end FROM tbl_available_schedule_slots where start >= '".$start_datetime."' AND end < '".$end_datetime."' AND doctor_id = ".$doctor_id;
        
        // $sql = "SELECT MIN(id) as id, 
        // date(start) as date 
        // FROM `tbl_available_schedule_slots` 
        // WHERE doctor_id = ".$doctor_id." AND 
        // date(start) >='".date("Y-m-d")."' GROUP BY date";        

        
        //get dates
        // $sql = "SELECT MIN(id) as id, 
        // date(start) as date 
        // FROM `tbl_available_schedule_slots` 
        // WHERE doctor_id = ".$doctor_id." AND 
        // date(start) >='".date("Y-m-d")."' GROUP BY date";

        $sql = "SELECT MIN(id) as id, 
        date(start) as date 
        FROM `tbl_available_schedule_slots` 
        WHERE doctor_id = ".$doctor_id." 
        AND date(start) >= '".date("Y-m-d")."' 
        AND date(start) <= '".date("Y-m-d", strtotime("+1 month"))."' 
        GROUP BY date";

        $AvailableDates = DB::select($sql);
        $AvailableDates2 = $AvailableDates = collect($AvailableDates);

        $now = Carbon::now();
        // $now_plus_next_time = $now->addMinutes(30);
        $now_plus_next_time = $now->addHours(8);

        foreach($AvailableDates2 as $k=>$v)
        {
            $date = $v->date;
            //output : "2024-09-09"             

            //get timeslots of that each date if its present then select that date else remve         
            if(!empty($date))
            {
                $sql = "SELECT id,DATE_FORMAT(start, '%Y-%m-%d') as start_date,DATE_FORMAT(start, '%H:%i') as start, 
                DATE_FORMAT(end, '%H:%i') as end FROM tbl_available_schedule_slots where 
                is_available = 1 AND DATE_FORMAT(start, '%Y-%m-%d') = '".$date."' 
                AND start >= '".$now_plus_next_time."' AND doctor_id = ".$doctor_id;

                // dd($sql);
                $slots = DB::select($sql);
                $slots = collect($slots);        
            
                if(!$slots->isNotEmpty())
                {
                    // dd($slots);p[]74*                    //elimuinate $date from AvailableDates                                        
                    $AvailableDates->forget($k);
                }        
            }

        }
        // dd($sql);
        // Return the timeslots as JSON response
        $AvailableDates = $AvailableDates->toArray();        
        // dd($AvailableDates);
        return response()->json($AvailableDates);
    }
    
    

}
