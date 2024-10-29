<?php

namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\StaticPage;
use App\Models\StaticPageContent;

use Illuminate\Http\Request;

class PrivacyController extends Controller
{
    public function index(){
        //privacypolicy id =5 
        $staticPage = StaticPage::where('page_name','Privacy_policy')->first();
        $privacy = StaticPageContent::where('static_page_id', $staticPage->id)->first();
        // dd($privacy);
        // $privacy= StaticPageContent::findOrFail(7);


        return view('frontend.privacy_policy',compact('privacy'));
    }


    public function term(){
        $staticPage = StaticPage::where('page_name','Terms_and_condition')->first();
        $term = StaticPageContent::where('static_page_id', $staticPage->id)->first();


        return view('frontend.term',compact('term'));
    }

    public function Compliance(){
        $staticPage = StaticPage::where('page_name','HIPPA_Compliance')->first();
        $compliance = StaticPageContent::where('static_page_id', $staticPage->id)->first();
        return view('frontend.compliance',compact('compliance'));
    }

    public function documentation(){
        $staticPage = StaticPage::where('page_name','documentation')->first();
        $term = StaticPageContent::where('static_page_id', $staticPage->id)->first();


        return view('frontend.documentation',compact('term'));
    }

    public function deauthorize(){
        $staticPage = StaticPage::where('page_name','deauthorize')->first();
        $term = StaticPageContent::where('static_page_id', $staticPage->id)->first();
        return view('frontend.deauthorize',compact('term'));   
    }


}
