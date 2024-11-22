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

class DoctorAvailableScheduleSlotsController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $data['PageName'] = 'My Availability';
        $data['icon'] = 'Group 9978.png';
        return view("Doctor-AvailableScheduleSlots.index", $data);
    }

    public function getEvents()
    {
        $user = $this->getSessionData("user");
        $schedules = AvailableScheduleSlots::where('doctor_id',$user["id"] )->get();
        
        return response()->json($schedules);
    }

    public function create(Request $request)
    {
        $request->validate([
            // 'days' => 'required|array|min:1', 
            // 'days.*' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday',            
            'start' => 'required',
            'end' => 'required',
            'slots' => 'required',
            // 'start' => 'required|date_format:DD/MM/YYYY H:i', 
        ]);
        
        // Start:: Code for two date range and all timeslots of a day 

        $startDate = Carbon::createFromFormat('m-d-Y', $request->start);
        $startDate = $startDate->format('Y-m-d');
        $startDate = Carbon::parse($startDate);

        $endDate = Carbon::createFromFormat('m-d-Y', $request->end);
        $endDate = $endDate->format('Y-m-d');
        $endDate = Carbon::parse($endDate);
        
        $user = $this->getSessionData("user");
        $doctor_id = $user["id"];
        
        while ($startDate <= $endDate) 
        {
            $sql = "SELECT * FROM dbl_users where user_type = 'doctor' AND id=".$doctor_id;
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
                
                $item->doctor_id = $doctor_id;
            
                // $item->is_available = $request->is_available;
                // $item->color = $request->color;
                // $item->booked = $request->booked; 
                
                $SlotSql = "SELECT id FROM tbl_available_schedule_slots where 
                doctor_id = ".$item->doctor_id." AND start='".$item->start."' AND end='".$item->end."'";
                $SlotData = DB::select($SlotSql);
                $SlotData = collect($SlotData);

                if ($SlotData->isNotEmpty()) 
                {
                    // dd($SlotData);
                    // Collection has items, do something
                    // $SlotData = $SlotData->first();
                        
                        if(!empty($SlotData) && $SlotData->count() > 0)
                        {        
                            // dd($SlotData);
                            //slot already present, continue
                            continue;
                        
                        }
                    
                } else {
                 
                    $item->save();

                }
                
            }
            
            $startDate->addDay();

            
        }
        
        return redirect('/Doctor-AvailableScheduleSlots')->with('success', 'Timeslots added successfully!');
                
    }
    
    
    public function deleteEvent($id)
    {
        $schedule = AvailableScheduleSlots::findOrFail($id);
        $schedule->delete();

        return response()->json(['message' => 'Time-slot deleted successfully']);
    }
    
    public function getTimeslots($doctor_id,$selected_id)
    {
        // Fetch available schedules for the given doctor_id and start date id 
        
        $sql = "SELECT * FROM tbl_available_schedule_slots where id = ".$selected_id;
        
        $record = DB::select($sql);
        $collection = collect($record);
        $firstRecord = $collection->first();
        
        $start_datetime = $firstRecord->start;
        
        $start_datetime = Carbon::parse($start_datetime);
        
        $selected_date = $start_datetime->toDateString();
        
        $end_datetime = $start_datetime->copy();
        
        $end_datetime->addDay();

        $now_plus_30min = "";
        
        
        if($selected_date == date("Y-m-d"))
        {
        //means today, so add 30 min to now and show timeslots greater than that.
        $now = Carbon::now();
        $now_plus_30min = $now->addMinutes(30);
        $sql = "SELECT id, DATE_FORMAT(start, '%H:%i') as start, DATE_FORMAT(end, '%H:%i') as end FROM tbl_available_schedule_slots where is_available = 1 AND start >= '".$now_plus_30min."' AND DATE_FORMAT(end, '%Y-%m-%d') < '".$end_datetime->toDateString()."' AND doctor_id = ".$doctor_id;
        }
        else
        {
        $sql = "SELECT id, DATE_FORMAT(start, '%H:%i') as start, DATE_FORMAT(end, '%H:%i') as end FROM tbl_available_schedule_slots where is_available = 1 AND DATE_FORMAT(start, '%Y-%m-%d') = '".$start_datetime->toDateString()."' AND doctor_id = ".$doctor_id;
        }

        // $sql = "SELECT id, TIME(start) as start,TIME(end) as end FROM tbl_available_schedule_slots where start >= '".$start_datetime."' AND end < '".$end_datetime."' AND doctor_id = ".$doctor_id;
        
        $schedules = DB::select($sql);
        $schedules = collect($schedules);

        // dd($sql);
        // Return the timeslots as JSON response
        return response()->json(['schedules' => $schedules,'sql'=>$sql,'selected_date_with_current_time'=>$now_plus_30min]);
    }
    
    public function getAvailableDates($doctor_id)
    {
        
        // $sql = "SELECT id, TIME(start) as start,TIME(end) as end FROM tbl_available_schedule_slots where start >= '".$start_datetime."' AND end < '".$end_datetime."' AND doctor_id = ".$doctor_id;
        $sql = "SELECT MIN(id) as id, date(start) as date FROM `tbl_available_schedule_slots` WHERE doctor_id = ".$doctor_id." AND date(start) >='".date("Y-m-d")."' GROUP BY date";
        
        
                $AvailableDates = DB::select($sql);
        
                $AvailableDates = collect($AvailableDates);

        // dd($sql);
        // Return the timeslots as JSON response
        return response()->json(['dates' => $AvailableDates]);
    }
    
    

}
