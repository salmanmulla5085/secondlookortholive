<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\StaticPageContent;
use App\Models\Faq;
use App\Models\Categories;
use App\Models\Schedule;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class PatientPaymentsController extends Controller
{
    // Show the list of Categories

    public function show($id)
    {
        $id = Crypt::decrypt($id);
        $data = [];
                
        $data['PageName'] = 'Payment Details';   
        $data['icon'] = 'Group 9954.png';        


        $payment = DB::table('tbl_payments')
        ->join('dbl_users', 'tbl_payments.patient_id', '=', 'dbl_users.id')
        ->leftJoin('tbl_plans', 'tbl_payments.plan_id', '=', 'tbl_plans.id')
        ->select(
            'tbl_payments.*', 
            'dbl_users.first_name', 
            'dbl_users.last_name', 
            'tbl_plans.plan_type'
        )
        ->where('tbl_payments.id', $id)
        ->first();
    
    if (!$payment) {
        return redirect()->back()->with('error', 'Payment not found.');
    }

    $data['payment'] = $payment;

        return view('frontend.patient_payments.view', $data);
    }


    public function index()
    {
        $data = [];
                
        $data['PageName'] = 'Payment History';  
        $data['icon'] = 'Group 9954.png';     

        $user = $this->getSessionData('user');

        $data['payments'] = DB::table('tbl_payments')
        ->join('dbl_users', 'tbl_payments.patient_id', '=', 'dbl_users.id')
        ->leftJoin('tbl_plans', 'tbl_payments.plan_id', '=', 'tbl_plans.id')
        ->select(
            'tbl_payments.*', 
            'dbl_users.first_name', 
            'dbl_users.last_name', 
            'tbl_plans.plan_type'
        )
        ->where('patient_id',$user->id)
        ->orderBy('id','DESC')
        ->get();

                // dd($data['result']);
        
        return view('frontend/patient_payments.history', $data);
    }

    
}
