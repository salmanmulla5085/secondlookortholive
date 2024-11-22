<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use App\Models\StaticPage;
use App\Models\StaticPageContent;
use App\Models\Testimonial;
use App\Models\ContactUs;
use App\Services\SendGridService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
    */

    protected $sendGridService;

    public function __construct(SendGridService $sendGridService)
    {
        $this->sendGridService = $sendGridService;
    }
    public function index(){
        $pagename = "Home_Page";
        $staticPage = StaticPage::where('page_name',$pagename)->first();
        $staticPageData = StaticPageContent::where('static_page_id', $staticPage->id)->get();
        $testimonials = Testimonial::all();     
        $faqs = Faq::where('status',1)->get();  
        return view('frontend.index',compact('staticPageData','testimonials','faqs'));
    }

    public function create(Request $request)
    {

        $adminData = DB::table('tbl_admins')->get();
        // Check if the request is a POST
        if ($request->isMethod('post')) {
            // Validate the form data
            $validatedData = $request->validate([
                'full_name' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'message' => 'required',               
            ]);

            if($request->full_name && $request->full_name != ''){
                $full_name = explode(' ', $request->full_name);

                $first_name = $last_name = '';

                if($full_name && $full_name[0]){
                    $first_name = $full_name[0];
                }

                if($full_name && $full_name[1]){
                    $last_name = $full_name[1];
                }
            }

            $UpdatedData = ([
                'first_name' => $first_name,
                'last_name' => $last_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message
            ]);

            $res = DB::table('tbl_contact_us')->insert($UpdatedData);

            if($res){
                $subject = $UpdatedData['first_name'].' has been Contacted You';
                $email = 'secondlookorthotj@gmail.com';
                SendEmail($this->sendGridService, $email, $subject, 'emails.contact-us-email-template', $UpdatedData, null, null, null, null, null, null);
            }

            if($res){
                return redirect('/add_contact_us')->with('success', 'Request submited successfully!');
            } else {
                return redirect('/add_contact_us')->with('error', 'Request not submited!');
            }
        }
        // Render the add-static-page form view
        return view('admin_contact_us.add');
    }

    public function support()
    {
        return view('pages.support');
    }
}

