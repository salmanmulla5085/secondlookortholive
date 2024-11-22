<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller; 
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class PlansController extends Controller
{
    public function index(){
        session()->forget('selected_plan');
        $data['selected_plan'] = '';

        $user = $this->getSessionData('user');

        if($user && $user != ''){
            $plan_sql = "SELECT plan_id FROM dbl_users where id=".$user['id'];
            $plan_sql = DB::select($plan_sql);
            $PlanData = collect($plan_sql);
            $PlanData = $PlanData->first();

            if($PlanData && $PlanData->plan_id != ''){
                $plan_name_sql = "SELECT plan_type FROM tbl_plans where id=".$PlanData->plan_id;
                $plan_name_sql = DB::select($plan_name_sql);
                $PlanName = collect($plan_name_sql);
                $PlanName = $PlanName->first();
                $data['selected_plan'] = $PlanName->plan_type;
            }
            
            if($data['selected_plan'] != ''){
                    
                $sql = "SELECT * FROM tbl_appointments_booked WHERE active = 1 AND end >= '".date('Y-m-d H:i:s')."' AND (status = 'In-Process' OR status = 'Confirmed' OR status = 'Completed') AND patient_id=" . $user['id'];
                $ext_app_sql = DB::select($sql);
                $data['check_app'] = count($ext_app_sql);
            }
        }
        
        $data['PageName'] = 'Consultation Plan';
        $data['icon'] = 'patient_icon_03.png';
        
        return view('frontend.plans', $data);
    }
}
