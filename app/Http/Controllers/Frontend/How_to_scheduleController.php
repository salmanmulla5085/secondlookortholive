<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class How_to_scheduleController extends Controller
{
    public function index(){
        return view('frontend.how-to-schedule');
    }
}
