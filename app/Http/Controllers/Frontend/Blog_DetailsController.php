<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;

class Blog_DetailsController extends Controller
{
    public function index(){
        return view('frontend.blog-details');
    }
}
