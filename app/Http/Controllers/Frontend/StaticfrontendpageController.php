<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Models\StaticPageContent;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class StaticfrontendpageController extends Controller
{
    public function pagename($pagename){
        $staticPage = StaticPage::where('page_name',$pagename)->first();
        $staticPageData = StaticPageContent::where('static_page_id', $staticPage->id)->get();
        
        if ($pagename == "About_Us") {
            return view('frontend.about', compact('staticPageData'));
        }

        if ($pagename == "How_It_Work") {
            return view('frontend.how-it-works', compact('staticPageData'));
        }
        
         
    }
}
