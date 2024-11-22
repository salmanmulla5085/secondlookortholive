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
use App\Http\Controllers\Frontend\NotificationsController;

use App\Http\Controllers\Frontend\Appointments_bookController;

//Admin
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\AdminLoginController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\ResetPassword;
use App\Http\Controllers\ChangePassword;            
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\AvailableScheduleSlotsController; 
use App\Http\Controllers\DoctorsController;

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

Route::get('/', [HomeController::class,'index']);
Route::get('/about', [AboutController::class,'index'])->name('about');
Route::get('/benefits', [BenefitsController::class,'index']);
Route::get('/how-it-works', [How_it_worksController::class,'index']);
Route::get('/faq', [FaqController::class,'index']);
Route::get('/blog-details', [Blog_DetailsController::class,'index']);
Route::get('/blog', [BlogController::class,'index']);
Route::get('/login', [LoginController::class,'index']);
Route::get('/forgot-password', [Forgot_passwordController::class,'index']);
Route::get('/register', [RegisterController::class,'index']);
Route::post('/register', [RegisterController::class, 'index']);
Route::get('/send-otp', [RegisterController::class,'send_otp']);
Route::post('/send-otp', [RegisterController::class,'send_otp']);
Route::get('/create-patient', [RegisterController::class,'create_patient']);
Route::get('/second-opinion-on/ankle-injury', [Second_opinionController::class,'index']);

Route::get('/patient-dashboard/{record_type?}', [DashboardController::class,'index']);
Route::get('/messages', [MessagesController::class,'index']);
Route::get('/messages-list', [MessagesController::class,'messages_list']);

Route::get('/settings', [SettingsController::class,'index']);
Route::get('/plans', [PlansController::class,'index']);
Route::get('/doctor-details/{id}', [DashboardController::class,'doctor_details'])->name('doctor-details');
Route::get('/notifications', [NotificationsController::class,'index']);



Route::post('/login', [LoginController::class, 'login']);

//Admin

Route::get('/admin', function () {return redirect('/admin_dashboard');})->middleware('auth');	
	Route::get('/admin_login', [AdminLoginController::class, 'show'])->middleware('guest')->name('login');
	Route::post('/admin_login', [AdminLoginController::class, 'login'])->middleware('guest')->name('login.perform');
	Route::get('/admin_dashboard', [AdminController::class, 'index'])->name('admin_dashboard')->middleware('auth');	
	Route::get('/reset-password', [ResetPassword::class, 'show'])->middleware('guest')->name('reset-password');
	Route::post('/reset-password', [ResetPassword::class, 'send'])->middleware('guest')->name('reset.perform');
	Route::get('/change-password', [ChangePassword::class, 'show'])->middleware('guest')->name('change-password');
	Route::post('/change-password', [ChangePassword::class, 'update'])->middleware('guest')->name('change.perform');
	
	//Booked Appointments
	Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule');
	Route::get('/events', [ScheduleController::class, 'getEvents'])->name('getEvents');
	Route::view('add-schedule', 'schedule.add');
	Route::post('/create-schedule', [ScheduleController::class, 'create']);
	
	Route::get('/book_appointment', [Appointments_bookController::class, 'book']);
	Route::post('/book_appointment', [Appointments_bookController::class, 'book']);
	Route::get('/book_appointment_step2', [Appointments_bookController::class, 'book_step2']);
	Route::post('/book_appointment_step2', [Appointments_bookController::class, 'book_step2']);
	
	Route::get('/book_appointment_step3', [Appointments_bookController::class, 'book_step3']);
	Route::post('/book_appointment_step3', [Appointments_bookController::class, 'book_step3']);
	
	Route::get('/show_booking', [Appointments_bookController::class, 'show_booking'])->name('show_booking');

	// AvailableScheduleSlots
	Route::get('/AvailableScheduleSlots', [AvailableScheduleSlotsController::class, 'index'])->name('AvailableScheduleSlots');
	Route::get('/AvailableScheduleSlots_events', [AvailableScheduleSlotsController::class, 'getEvents'])->name('getEvents');
	Route::view('add-AvailableScheduleSlots', 'AvailableScheduleSlots.add');
	Route::post('/create-AvailableScheduleSlots', [AvailableScheduleSlotsController::class, 'create']);
	
	Route::get('/AvailableScheduleSlots/delete/{id}', [AvailableScheduleSlotsController::class, 'deleteEvent']);
	Route::get('AvailableScheduleSlots/delete/{id}', [AvailableScheduleSlotsController::class, 'deleteEvent']);
	Route::get('/get-timeslots/{doctor_id}/{selected_date}', [AvailableScheduleSlotsController::class,'getTimeslots']);
	Route::get('/get-available-dates/{doctor_id}', [AvailableScheduleSlotsController::class,'getAvailableDates']);
	
	
	Route::get('/how-to-schedule', [How_to_scheduleController::class,'index']);
	
	Route::view('add-doctor', 'doctor.add');
	Route::post('/create-doctor', [DoctorsController::class, 'create']);
	

	Route::group(['middleware' => 'auth'], function () {
	Route::get('/profile', [UserProfileController::class, 'show'])->name('profile');
	Route::post('/profile', [UserProfileController::class, 'update'])->name('profile.update');
	Route::get('/{page}', [PageController::class, 'index'])->name('page');
	Route::post('admin_logout', [AdminLoginController::class, 'logout'])->name('logout');
	

});