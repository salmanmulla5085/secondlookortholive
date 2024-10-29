<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    public function index(){
        $data['PageName'] = 'Message';
        return view('frontend.messages', $data);
    }
    
    public function messages_list(){
        $data['PageName'] = 'Message';
        return view('frontend.messages-list', $data);
    }

    public function new_messages(){
        $data['PageName'] = 'Message';
        $data['icon'] = 'patient_icon_02.png';

        $user = $this->getSessionData('user');

        $data['user_type'] = $user['user_type'];

        if($user['user_type'] == 'patient'){
            // $data['extData'] = DB::table('tbl_appointments_booked')
            //     ->where('patient_id', $user['id'])
            //     ->where('status', 'Completed')
            //     ->orWhere('status', 'Confirmed')
            //     ->where('start', '<', now()) // using now() for current timestamp
            //     ->orderBy('start', 'desc')
            //     ->paginate(10); // paginate method
                
                $extData = DB::table('tbl_appointments_booked')
                ->where('patient_id', $user['id'])
                ->where('start', '<', now()) // using now() for current timestamp
                ->whereIn('status', ['Completed', 'Confirmed']) // Add status condition
                ->orderBy('start', 'desc');

                // Get the count of the records before pagination
                $record_count = $extData->count();

                // Now apply the condition based on the record count
                if ($record_count > 10) {
                    $data['pagination'] = 1;
                    $data['extData'] = $extData->paginate(10);
                } else {
                    $data['pagination'] = 0;
                    $data['extData'] = $extData->get();  // You can change this logic if needed
                }


        } elseif($user['user_type'] == 'doctor') {
            // $extData = DB::table('tbl_appointments_booked')
            //     ->where('doctor_id', $user['id'])
            //     ->where('status', 'Completed')
            //     ->orWhere('status', 'Confirmed')
            //     ->where('start', '<', now()) // using now() for current timestamp
            //     ->orderBy('start', 'desc')
            //     ->paginate(10); // paginate method
                
            $extData = DB::table('tbl_appointments_booked')
                ->where('doctor_id', $user['id'])
                ->where('start', '<', now()) // using now() for current timestamp
                ->whereIn('status', ['Completed', 'Confirmed']) // Add status condition
                ->orderBy('start', 'desc'); // paginate method

            // Get the count of the records before pagination
            $record_count = $extData->count();

            // Now apply the condition based on the record count
            if ($record_count > 10) {
                $data['pagination'] = 1;
                $data['extData'] = $extData->paginate(10);
            } else {
                $data['pagination'] = 0;
                $data['extData'] = $extData->get();  // You can change this logic if needed
            }
        }

        return view('frontend.new_messages', $data);
    }
    
}
