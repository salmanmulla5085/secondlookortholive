<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller; 
use App\Models\notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class NotificationsController extends Controller
{
    public function index(){
        $data['PageName'] = 'Notifications';
        $data['icon'] = 'Vector(31).png';
        
        $user = $this->getSessionData('user');

        if($user != null && $user['id'] != ''){
            $res = DB::table('tbl_notifications')
                    ->where('user_id', $user['id'])
                    ->update(["red_flag"=>1]);

            // Convert raw SQL to query builder
            $query = DB::table('tbl_notifications')
            ->where('user_id', $user['id'])
            ->where('received_by', $user['user_type'])
            ->where('status', 1)
            ->orderBy('id', 'desc');

            // Get the count of the records before pagination
            $record_count = $query->count();

            // Now apply the condition based on the record count
            if ($record_count > 10) {
                $data['pagination'] = 1;
                $data['NotificationData'] = $query->paginate(10);
            } else {
                $data['pagination'] = 0;
                $data['NotificationData'] = $query->get();  // You can change this logic if needed
            }
        }

        return view('frontend.notifications', $data);
    }

    public function delete($id)
    {
        if ($id && $id != '') {
            $id = Crypt::decrypt($id);
            $UpdatedData['status'] = 0;
            DB::table('tbl_notifications')
                ->where('id', $id)
                ->update($UpdatedData);
            
            return redirect('/notifications')->with('success', 'Notification deleted successfully!');
        }
    }
    
}