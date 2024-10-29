<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Models\StaticPageContent;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function index(){
        $staticPage = StaticPage::where('page_name', 'About_Us')->first();
        $pageSections = StaticPageContent::where('static_page_id', $staticPage->id)->get();

        dd($pageSections);

        return view('frontend.about');
    }
}
