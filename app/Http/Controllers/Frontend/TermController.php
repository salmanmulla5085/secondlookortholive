<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 
use App\Models\StaticPageContent;

use Illuminate\Http\Request;

class TermController extends Controller
{
    public function index(){
        //privacypolicy id =5 
        $privacy= StaticPageContent::findOrFail(5);


        return view('frontend.term',compact('term'));
    }
}
