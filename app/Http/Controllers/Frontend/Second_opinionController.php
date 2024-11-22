<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class Second_opinionController extends Controller
{
    public function index($joint_name = null){

        if(!empty($joint_name)){
            $jointSql = "SELECT * FROM tbl_joints where `name` = '".$joint_name."'";
            $joint = DB::select($jointSql);
            $data['jointData'] = collect($joint);
        }
        
        return view('frontend.second-opinion', $data);
    }
}
