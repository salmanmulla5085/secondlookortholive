<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\AboutController;
use App\Http\Controllers\Frontend\BenefitsController;
use App\Http\Controllers\Frontend\HomBlog_DetailsControllereController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\FaqController;
use App\Http\Controllers\Frontend\Forgot_passwordController;
use App\Http\Controllers\Frontend\How_it_worksController;
use App\Http\Controllers\Frontend\How_to_scheduleController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\RegisterController;
use App\Http\Controllers\Frontend\Second_opinionController;
use App\Http\Controllers\Frontend\Blog_DetailsController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\Frontend\MessagesController;
use App\Http\Controllers\Frontend\SettingsController;
use App\Http\Controllers\Frontend\PlansController;
use App\Http\Controllers\AdminPlansController;
use App\Http\Controllers\Frontend\NotificationsController;
use App\Http\Controllers\Frontend\DoctorController;
use App\Http\Controllers\Frontend\JointsController;
use App\Http\Controllers\AdminContactUsController;

use App\Http\Controllers\Frontend\Appointments_bookController;
use App\Http\Controllers\Frontend\ChatController;
use App\Http\Controllers\Frontend\CronJobs;
use App\Http\Controllers\Frontend\PrivacyController;

//Admin
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\AdminNewController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\AvailableScheduleSlotsController;
use App\Http\Controllers\DoctorsController;
use App\Http\Controllers\DoctorAvailableScheduleSlotsController;
use App\Http\Controllers\StripDeleteDataController;

//CMS
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\Frontend\PatientHistoryController;

//blogs
use App\Http\Controllers\ArticleController;
//categories
use App\Http\Controllers\CategoryController;

use App\Mail\SendGridTestMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Frontend\ReportReviewsRepliesController;
use App\Http\Controllers\EmailController;
use App\Http\Controllers\BillingController;
use App\Http\Controllers\Frontend\StaticfrontendpageController;

use App\Http\Controllers\ReviewController;
use App\Http\Controllers\PatientPaymentsController;
use App\Http\Controllers\ReportController;

use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| Web Routes

|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/send-test-email', function () {
//     Mail::to('zahoor.ahmed@aviontechnology.us')->send(new SendGridTestMail());
//     return 'Email sent successfully!';
// });


// Route::get('/test-email', function () {
//     Mail::raw('This is a test email from SendGrid!', function ($message) {
//         $message->to('darshankondekar01@gmail.com')
//                 ->subject('Test Email');
//     });
//     return 'Test email sent!';
// });

// Route::get('/test-email2', function () {
//     $details = [
//         'subject' => 'Test Email',
//         'body' => 'This is a test email sent using SendGrid API in Laravel 10.'
//     ];

//     Mail::raw($details['body'], function ($message) use ($details) {
//         $message->to('zahoor.aviontech@gmail.com')  // Replace with recipient's email
//                 ->subject($details['subject']);
//     });

//     return 'Test email sent!';
// });



Route::get('/test-send-sms', function () {
    $to = '+919028388512'; // Replace with the recipient's phone number
    $message = 'Hello! This is a test message from secondlook.';
    
    if (sendSms($to, $message)) {
        return 'SMS sent successfully!';
    } else {
        return 'Failed to send SMS.';
    }
});


Route::get('/admin_login', [AdminNewController::class, 'login2'])->name('login2');
Route::post('/admin_login', [AdminNewController::class, 'login2_perform'])->name('login2_perform');
Route::post('/admin_logout', [AdminNewController::class, 'admin_logout'])->name('admin_logout');
Route::get('/admin_logout', [AdminNewController::class, 'admin_logout'])->name('admin_logout');


Route::get('/send-email', [EmailController::class, 'sendEmail']);


Route::get('/', [HomeController::class, 'index']);
Route::get('/about', [AboutController::class, 'index'])->name('about');
Route::get('/privacy_policy', [PrivacyController::class, 'index'])->name('privacy');
Route::get('/term_condition', [PrivacyController::class, 'term'])->name('term');
Route::get('/Compliance', [PrivacyController::class, 'Compliance'])->name('Compliance');
Route::get('/documentation', [PrivacyController::class, 'documentation'])->name('documentation');
Route::get('/deauthorize', [PrivacyController::class, 'deauthorize'])->name('deauthorize');

Route::get('/staticpage/{pagename}', [StaticfrontendpageController::class, 'pagename'])->name('staticpages');


Route::get('/add_contact_us', [HomeController::class, 'create']);
Route::post('/add_contact_us', [HomeController::class, 'create']);

Route::get('/support', [HomeController::class, 'support']);

Route::get('/benefits', [BenefitsController::class, 'index']);
Route::get('/how-it-works', [How_it_worksController::class, 'index']);
Route::get('/faq_homepage', [FaqController::class, 'index']);
Route::get('/blog-details/{id?}', [BlogController::class, 'article_details'])->name('blog-details');
Route::get('/blog', [BlogController::class, 'index']);
Route::get('/blog/{id}', [BlogController::class, 'index']);


Route::get('/login', [LoginController::class, 'index']);
Route::post('/login', [LoginController::class, 'login']);

Route::get('/login_doctor', [LoginController::class, 'index']);
Route::post('/login_doctor', [LoginController::class, 'login_doctor']);

Route::get('/login/doctor', [LoginController::class, 'index']);
Route::post('/login/doctor', [LoginController::class, 'login_doctor']);

Route::get('/logout', [LoginController::class, 'logout']);
Route::get('/doctor_logout', [LoginController::class, 'doctor_logout']);
Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'index']);
Route::get('/send-otp', [RegisterController::class, 'send_otp']);
Route::post('/send-otp', [RegisterController::class, 'send_otp']);
Route::get('/send-otp/{UserId}', [RegisterController::class, 'send_otp']);
Route::post('/send-otp/{UserId}', [RegisterController::class, 'send_otp']);
Route::get('/resend_otp/{UserId}', [RegisterController::class, 'resend_otp']);
Route::get('/create-patient', [RegisterController::class, 'create_patient']);
Route::get('/create-patient/{UserId}', [RegisterController::class, 'create_patient']);
Route::get('/second-opinion-on/{joint}', [Second_opinionController::class, 'index']);
Route::get('/states', [RegisterController::class, 'getStates']);
Route::get('/cities/{stateId}', [RegisterController::class, 'getCities']);

Route::get('/UpdateLockTimeAndBooked', [CronJobs::class, 'UpdateLockTimeAndBooked']);
Route::get('/UpdateNotConfirmed', [CronJobs::class, 'UpdateNotConfirmed']);
Route::get('/SendAppReminderToPatientAndDoctor', [CronJobs::class, 'SendAppReminderToPatientAndDoctor']);

// Forgot password
// Route::get('/forgot-password', [Forgot_passwordController::class,'index']);
Route::get('/forgot-password', [Forgot_passwordController::class, 'forgot_password']);
Route::post('/forgot-password', [Forgot_passwordController::class, 'forgot_password']);
Route::get('/forgot-password-otp/{user_id}', [Forgot_passwordController::class, 'forgot_password_otp']);
Route::post('/forgot-password-otp/{user_id}', [Forgot_passwordController::class, 'forgot_password_otp']);
Route::get('/create-new-password/{user_id}', [Forgot_passwordController::class, 'create_new_password']);
Route::post('/create-new-password/{user_id}', [Forgot_passwordController::class, 'create_new_password']);

// Routes for patient:
Route::middleware('checkpatient')->group(function () {

	Route::get('/payments/{id}', [PatientPaymentsController::class, 'show'])->name('payment.view');
	Route::get('/patient_payments_history', [PatientPaymentsController::class,'index'])->name('patient_payments_history');	
	Route::get('/patient-dashboard/{record_type?}', [DashboardController::class, 'index'])->name('patient.dashboard');
	Route::get('/patient-report-reviews', [DashboardController::class, 'patient_report_reviews'])->name('patient.report-reviews');
	Route::get('/download-pdf/{id}', [DashboardController::class, 'downloadPDF'])->name('download.pdf');

	// Other patient-specific routes

	Route::get('/plans', [PlansController::class, 'index'])->name('plans');

	Route::get('/doctor-details/{id}', [DashboardController::class, 'doctor_details'])->name('doctor-details');
	Route::get('/cancel-appointment/{id}', [DashboardController::class, 'cancel_appointment']);
	Route::get('/reschedule-appointment/{id}', [DashboardController::class, 'reschedule_appointment']);
	Route::get('/show_booking', [Appointments_bookController::class, 'show_booking'])->name('show_booking');

	Route::get('/reschedule-appointment/{id}/{follow_up}', [DashboardController::class, 'reschedule_appointment']);

	Route::get('/book_appointment/{id?}', [Appointments_bookController::class, 'book']);
	Route::get('/book_appointment/{id?}/{app_id?}', [Appointments_bookController::class, 'book']);
	Route::get('/book_appointment/{id?}/{app_id?}/{type}', [Appointments_bookController::class, 'book']);
	Route::post('/book_appointment', [Appointments_bookController::class, 'book']);
	Route::post('/book_appointment', [Appointments_bookController::class, 'book']);
	Route::get('/book_appointment_step2', [Appointments_bookController::class, 'book_step2']);
	Route::post('/book_appointment_step2', [Appointments_bookController::class, 'book_step2']);

	Route::get('/book_appointment_step2/{app_id?}', [Appointments_bookController::class, 'book_step2']);
	Route::post('/book_appointment_step2/{app_id?}', [Appointments_bookController::class, 'book_step2']);

	Route::get('/book_appointment_step3', [Appointments_bookController::class, 'book_step3']);
	Route::post('/book_appointment_step3', [Appointments_bookController::class, 'book_step3']);
	Route::post('/delete-medicalDoc', [Appointments_bookController::class, 'deleteFile'])->name('delete.medicalDoc');
});

// Routes for Doctors:
Route::middleware('checkdoctor')->group(function () {
	Route::post('/generate-zoom-meeting-link', [DoctorController::class, 'generate_zoom_meeting_link'])->name('generate_zoom_meeting_link');	
	Route::post('/doctor-report-reply', [DoctorController::class, 'doctor_report_reply'])->name('doctor.report-reply');
	Route::post('/save_doctor_prescription', [DoctorController::class, 'save_doctor_prescription'])->name('doctor.save_doctor_prescription');
	
	Route::post('/modify_doctor_prescription', [DoctorController::class, 'modify_doctor_prescription'])->name('doctor.modify_doctor_prescription');

	Route::post('/modify-doctor-report-reply', [DoctorController::class, 'modify_doctor_report_reply'])->name('doctor.modify-report-reply');

	Route::get('/doctor-report-reviews', [DoctorController::class, 'doctor_report_reviews'])->name('doctor.report-reviews');
	Route::post('/doctor-report-reviews', [DoctorController::class, 'doctor_report_reviews'])->name('doctor.filter_report_reviews');

	Route::get('/not-confirmed-appintments', [DoctorController::class, 'not_confirmed_appintments'])->name('doctor.not_confirmed_appintments');

	Route::get('/doctor-dashboard/{record_type?}', [DoctorController::class, 'doctor_dashboard'])->name('doctor.dashboard');
	Route::post('/doctor-dashboard/{record_type?}', [DoctorController::class, 'doctor_dashboard'])->name('doctor.dashboard_post');
	Route::get('/doctor-profile', [DoctorController::class, 'profile'])->name('doctor.profile');
	Route::get('/doctor-calendar', [DoctorController::class, 'doctor_calendar'])->name('doctor.calendar');

	Route::get('/Doctor-AvailableScheduleSlots', [DoctorAvailableScheduleSlotsController::class, 'index'])->name('DoctorAvailableScheduleSlots');
	Route::get('/Doctor-AvailableScheduleSlots_events', [DoctorAvailableScheduleSlotsController::class, 'getEvents'])->name('DoctorgetEvents');
	Route::view('/Doctor-add-AvailableScheduleSlots', 'Doctor-AvailableScheduleSlots.add');
	Route::post('/Doctor-create-AvailableScheduleSlots', [DoctorAvailableScheduleSlotsController::class, 'create']);

	Route::get('/Doctor-AvailableScheduleSlots/delete/{id}', [DoctorAvailableScheduleSlotsController::class, 'deleteEvent']);
	Route::get('/Doctor-get-timeslots/{doctor_id}/{selected_date}', [DoctorAvailableScheduleSlotsController::class, 'getTimeslots']);
	Route::get('/Doctor-get-available-dates/{doctor_id}', [DoctorAvailableScheduleSlotsController::class, 'getAvailableDates']);
	Route::get('/doctor-cancel-appointment/{id}', [DoctorController::class, 'doctor_cancel_appointment']);

	//Booked Appointments
	Route::get('/Doctor-schedule', [DoctorScheduleController::class, 'index'])->name('Doctor.schedule');
	Route::get('/Doctor-events', [DoctorScheduleController::class, 'getEvents'])->name('Doctor.getEvents');
	Route::view('Doctor-add-schedule', 'Doctor-schedule.add');
	Route::post('/Doctor-create-schedule', [DoctorScheduleController::class, 'Doctor.create']);
	Route::post('/appointments/confirm', [DoctorController::class, 'confirmAppointment'])->name('appointments.confirm');
	Route::get('/confirm-appointment/{selected_slot}/{doctor_id}', [DoctorController::class, 'confirm_appointment']);
	Route::post('/confirm-appointment/{selected_slot}/{doctor_id}', [DoctorController::class, 'confirm_appointment']);
	Route::get('/confirm-appointment-v2/{selected_slot}/{doctor_id}', [DoctorController::class, 'confirm_appointment_v2']);
	Route::post('/confirm-appointment-v2/{selected_slot}/{doctor_id}', [DoctorController::class, 'confirm_appointment_v2']);

	Route::post('/confirm_appointment_v2_post', [DoctorController::class, 'confirm_appointment_v2_post'])->name('confirm_appointment_v2_post');
	Route::post('/confirm_appointment_v3_post', [DoctorController::class, 'confirm_appointment_v3_post'])->name('confirm_appointment_v3_post');

	//Patient History
	Route::get('/patient-history/{app_id}/{patient_id}/{type}', [PatientHistoryController::class, 'index'])->name('patient-history');
	Route::get('/patient-history/{app_id}/{patient_id}', [PatientHistoryController::class, 'index'])->name('patient-history2');
});


// for both
Route::middleware('checkuser')->group(function () {
	Route::get('/messages', [MessagesController::class, 'index']);
	Route::get('/messages-list', [MessagesController::class, 'messages_list']);
	Route::get('/new-messages', [MessagesController::class, 'new_messages'])->name('new-messages');
	Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
	Route::get('/notifications', [NotificationsController::class, 'index']);
	Route::get('/delete-notification/{Id}', [NotificationsController::class, 'delete']);
	Route::get('/acc-update', [SettingsController::class, 'account_update']);
	Route::post('/acc-update', [SettingsController::class, 'account_update']);	
	Route::post('/chat/create', [ChatController::class, 'createChat']);
	Route::post('/chat/{chatId}/message', [ChatController::class, 'sendMessage']);
	Route::get('/chat/{chatId}/messages', [ChatController::class, 'getMessages']);
	Route::get('/chat/{chatId}/{appointmentId}/{from_msg}', [ChatController::class, 'showChat'])->name('chat.show');
	Route::get('/chats', [ChatController::class, 'listChats'])->name('chats.list');
	Route::post('/chat/initiate', [ChatController::class, 'initiateChat'])->name('chat.initiate');
	Route::post('/delete-file', [ReportReviewsRepliesController::class, 'deleteFile'])->name('delete.file');
	Route::post('/delete-user-file', [SettingsController::class, 'deleteUserFile'])->name('delete_user.file');
	Route::post('/deleteprescriptionfile', [DoctorController::class, 'deletePrescriptionFile'])->name('deleteprescriptionfile');
	
});

	//Admin
	Route::middleware('checkadmin')->group(function () {    
	
		
		Route::get('/admin_doctors', [DoctorsController::class, 'index'])->name('admin_doctors');
		Route::get('/admin_patient', [DoctorsController::class, 'admin_patient'])->name('admin_patient');
		
		Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
		Route::view('add-schedule', 'schedule.add');
		Route::post('/create-schedule', [ScheduleController::class, 'create']);

		Route::get('/admin/review', [ReviewController::class, 'listReview'])->name('admin.review');
		Route::get('/view-review/{id}', [ReviewController::class, 'viewReview'])->name('admin.viewReview');
	
		Route::post('/testimonials/store', [TestimonialController::class, 'store'])->name('admin.testimonials.store');
		
		Route::get('/testimonials/activate/{id}', [TestimonialController::class, 'activate'])->name('admin.testimonials.activate');
		Route::get('/testimonials/deactivate/{id}', [TestimonialController::class, 'deactivate'])->name('admin.testimonials.deactivate');

		Route::get('delete_all_customers_and_cards_from_stripe_and_local', [StripDeleteDataController::class, 'delete_all_customers_and_cards_from_stripe_and_local']);
		Route::get('/AvailableScheduleSlots', [AvailableScheduleSlotsController::class, 'index'])->name('AvailableScheduleSlots');
		Route::get('/admin_plans', [AdminPlansController::class,'index']);
		Route::get('testimonials', [TestimonialController::class, 'index'])->name('admin.testimonials.index');
		Route::get('testimonials/create', [TestimonialController::class, 'create'])->name('admin.testimonials.create');
		Route::get('testimonials/{id}/edit', [TestimonialController::class, 'edit'])->name('admin.testimonials.edit');
		Route::put('testimonials/{id}', [TestimonialController::class, 'update'])->name('admin.testimonials.update');
		//faq
		
		Route::get('faq', [StaticPageController::class, 'admin_index'])->name('admin.faq.index');
		Route::get('faq/create', [StaticPageController::class, 'createFAQ'])->name('admin.faq.create');
		Route::post('faq', [StaticPageController::class, 'storeFAQ'])->name('admin.faq.store');
		Route::get('faq/{id}/edit', [StaticPageController::class, 'editFAQ'])->name('admin.faq.edit');
		Route::put('faq/{id}', [StaticPageController::class, 'updateFAQ'])->name('admin.faq.update');
		
		Route::get('/admin/faq_status/{faq_id}/{status}', [StaticPageController::class, 'faq_status'])->name('faq.status');

		Route::get('delete_testimonials/{id}', [TestimonialController::class, 'destroy'])->name('admin.testimonials.destroy');
		Route::get('delete_faq/{id}', [StaticPageController::class, 'destroyFAQ'])->name('admin.faq.destroy');
		// Route::delete('faq/{id}', [StaticPageController::class, 'destroyFAQ'])->name('admin.faq.destroy');
	
		Route::get('/admin_dashboard', [AdminController::class, 'index'])->name('admin_dashboard');	

		Route::get('/get_admin_otp/{user_id?}', [AdminNewController::class, 'get_admin_otp'])->name('get_admin_otp');
		Route::post('/get_admin_otp/{user_id?}', [AdminNewController::class, 'get_admin_otp'])->name('get_admin_otp');

		Route::view('add-doctor', 'doctor.add');
		Route::post('/create-doctor', [DoctorsController::class, 'create']);

		//Doctor from admin
		Route::get('/doctor_status/{doctor_id}/{status}', [DoctorsController::class, 'doctor_status']);
		Route::get('/view_doctor/{doctor_id}/{opr}', [DoctorsController::class, 'view_doctor']);
		Route::get('/delete_doctor/{doctor_id}', [DoctorsController::class, 'delete_doctor']);

		//Patient from admin
		Route::get('/patient_status/{patient_id}/{status}', [DoctorsController::class, 'patient_status']);
		Route::get('/view_patient/{patient_id}/{opr}', [DoctorsController::class, 'view_patient']);
		Route::post('/create-patient', [DoctorsController::class, 'create_patient']);
		Route::get('/delete_patient/{patient_id}', [DoctorsController::class, 'delete_patient']);

		Route::get('/joints', [JointsController::class, 'index'])->name('joints');
	Route::get('/view_joint/{joint_id}/{opr}', [JointsController::class, 'view_joint']);
	Route::post('/update-joint', [JointsController::class, 'update']);

	// Route::post('/admin_logout', [AdminLoginController::class, 'logout'])->name('admin.logout');

	
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::post('/changePassword', [UserProfileController::class, 'changePassword'])->name('changepassword');

	//CMS
	Route::get('admin/add-static-page', [StaticPageController::class, 'create'])->name('admin.add-static-page');
	Route::post('admin/add-static-page', [StaticPageController::class, 'create'])->name('admin.add-static-page-post');
	// List Static Pages
	Route::get('/admin/list-static-pages', [StaticPageController::class, 'listStaticPages'])->name('admin.listStaticPages');
	// Add Page Section
	Route::get('/admin/add-page-section', [StaticPageController::class, 'addPageSection'])->name('admin.addPageSection');
	Route::post('/admin/store-page-section', [StaticPageController::class, 'storePageSection'])->name('admin.storePageSection');
	// List Page Sections
	Route::get('/admin/list-page-sections', [StaticPageController::class, 'listPageSections'])->name('admin.listPageSections');
	// Edit Static Page
	Route::get('/admin/edit-static-page/{id}', [StaticPageController::class, 'editStaticPage'])->name('admin.editStaticPage');
	Route::post('/admin/update-static-page/{id}', [StaticPageController::class, 'updateStaticPage'])->name('admin.updateStaticPage');
	// Route to show the edit page section form
	Route::get('admin/edit-page-section/{id}', [StaticPageController::class, 'editPageSection'])
		->name('admin.editPageSection');
		Route::get('admin/view-page-section/{id}', [StaticPageController::class, 'viewPageSection'])
		->name('admin.viewPageSection');

	// Route to update the page section
	Route::get('admin/update-page-section/{id}', [StaticPageController::class, 'updatePageSection'])
		->name('admin.updatePageSection-get');

	Route::post('admin/update-page-section/{id}', [StaticPageController::class, 'updatePageSection'])
		->name('admin.updatePageSection');

	Route::put('admin/update-page-section/{id}', [StaticPageController::class, 'updatePageSection'])
		->name('admin.updatePageSection');

	Route::delete('admin/static-page/{id}', [StaticPageController::class, 'deleteStaticPage'])->name('admin.deleteStaticPage');
	Route::delete('admin/page-section/{id}', [StaticPageController::class, 'deletePageSection'])->name('admin.deletePageSection');

	// Blogs
	Route::get('/admin/articles', [ArticleController::class, 'listArticles'])->name('admin.articles');
	Route::get('admin/add-article', [ArticleController::class, 'create'])->name('admin.add-article-get');
	Route::post('admin/add-article', [ArticleController::class, 'create'])->name('admin.add-article');
	Route::get('/article_status/{id}/{status}', [ArticleController::class, 'article_status']);
	Route::get('/edit-article/{id}', [ArticleController::class, 'editArticle'])->name('admin.editArticle');
	Route::post('/updateArticle/{id}', [ArticleController::class, 'updateArticle'])->name('admin.updateArticle');
	Route::get('delete-article/{id}', [ArticleController::class, 'deleteArticle'])->name('admin.deleteArticle');
    Route::get('view-article-comment/{id}', [ArticleController::class,'viewArticleComment'])->name('admin.viewArticle');
	Route::get('/comment_status/{id}/{status}', [ArticleController::class, 'comment_status']);

	// Categories
	Route::get('/admin/categories', [CategoryController::class, 'listCategories'])->name('admin.categories');
	Route::get('admin/add-category', [CategoryController::class, 'create'])->name('admin.add-categories');
	Route::post('admin/add-category', [CategoryController::class, 'create'])->name('admin.add-categories-post');
	Route::get('/category_status/{id}/{status}', [CategoryController::class, 'category_status']);
	Route::get('/edit-category/{id}', [CategoryController::class, 'editCategory'])->name('admin.editCategory');
	Route::post('/updateCategory/{id}', [CategoryController::class, 'updateCategory'])->name('admin.updateCategory');
	Route::get('delete-category/{id}', [CategoryController::class, 'deleteCategory'])->name('admin.deleteCategory');

	//plan
	Route::get('/admin/plan', [AdminPlansController::class, 'listplans'])->name('admin.plan');
	Route::get('admin/add-plan', [AdminPlansController::class, 'create'])->name('admin.add-plan');
	Route::post('admin/add-plan', [AdminPlansController::class, 'create'])->name('admin.add-plan-post');
	Route::get('/plan_status/{id}/{status}', [AdminPlansController::class, 'plan_status']);
	Route::get('/edit-plan/{id}', [AdminPlansController::class, 'editPlan'])->name('admin.editPlan');
	Route::post('/updatePlan/{id}', [AdminPlansController::class, 'updatePlan'])->name('admin.updatePlan');
	Route::get('delete-plan/{id}', [AdminPlansController::class, 'deletePlan'])->name('admin.deletePlan');

	Route::get('/admin/billing', [BillingController::class, 'listbillings'])->name('admin.billing');
	Route::get('/view-billing/{id}', [BillingController::class, 'viewBilling'])->name('admin.viewBilling');


	});

	// Route::get('/admin_login', [AdminNewController::class, 'login2'])->name('admin_login');
	// Route::post('/admin_login', [AdminLoginController::class, 'login'])->name('adminlogin.perform');

	//Contact Us
	Route::get('/admin/contact_us', [AdminContactUsController::class, 'index'])->name('admin.contact_us');
	Route::get('/view-message/{id}', [AdminContactUsController::class, 'ViewMessage'])->name('admin.view-message');
	Route::get('/delete-contact/{id}', [AdminContactUsController::class, 'delete'])->name('admin.delete-contact');
	Route::get('/admin/reports', [ReportController::class, 'ReportsList'])->name('admin.reports');

	Route::get('/reset-password', [ResetPassword::class, 'show'])->name('reset-password');
	Route::post('/ResetPassword', [ResetPassword::class, 'ResetPassword'])->name('ResetPassword');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->name('change.perform');
	
	// AvailableScheduleSlots
	Route::get('/AvailableScheduleSlots_events', [AvailableScheduleSlotsController::class, 'getEvents2'])->name('getEvents');
	Route::get('/events', [ScheduleController::class, 'getEvents'])->name('getEvents2');
	Route::view('add-AvailableScheduleSlots', 'AvailableScheduleSlots.add');
	Route::post('/create-AvailableScheduleSlots', [AvailableScheduleSlotsController::class, 'create']);
	Route::get('AvailableScheduleSlots/delete/{id}', [AvailableScheduleSlotsController::class, 'deleteEvent']);	
	Route::get('/get-timeslots/{doctor_id}/{selected_date}/{patient_id?}', [AvailableScheduleSlotsController::class,'getTimeslots']);
	Route::get('/get-available-dates/{doctor_id}', [AvailableScheduleSlotsController::class,'getAvailableDates']);
		
	Route::get('/how-to-schedule', [How_to_scheduleController::class,'index']);
	
	
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('/comments', [BlogController::class, 'store'])->name('comments.store');
	