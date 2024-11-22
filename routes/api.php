<?php

use App\Http\Controllers\API\ApiDoctorController;
use App\Http\Controllers\API\ApiDoctorDashboardController;
use App\Http\Controllers\API\ApiPatientController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
use App\Http\Controllers\API\FAQController;
use App\Http\Controllers\API\StatesController;
use App\Http\Controllers\API\StaticPageController;
use App\Http\Controllers\API\SignupController;
use App\Http\Controllers\Frontend\DashboardController;
use App\Http\Controllers\API\AppointmentController;
use Laravel\Sanctum\Http\Controllers\CsrfCookieController;

Route::post('/signup_save_user', [SignupController::class, 'save_user']);
Route::post('/validate_otp', [SignupController::class, 'validate_otp']);

Route::middleware('basic.auth')->group(function () {
    Route::post('/login', [SignupController::class, 'login']);
    Route::post('/login_doctor', [SignupController::class, 'login_doctor']);
    
   
});

Route::post('/logout', [SignupController::class, 'logout']);

 
Route::middleware('basic.auth')->get('/faqs', [FAQController::class, 'index']);
// Route::middleware('basic.auth')->get('/get_page_sections', [StaticPageController::class, 'getPageSections']);
Route::middleware('basic.auth')->get('/get_page_sections', [StaticPageController::class, 'getPageSections']);
Route::post('patient/appointments/{record_type?}', [AppointmentController::class, 'patient_appointments']);
Route::post('doctor/appointments/{record_type?}', [AppointmentController::class, 'doctor_appointments']);

Route::post('/logout_old', [SignupController::class, 'logout_old']);
// Route::middleware('auth:sanctum')->post('/logout_old', [SignupController::class, 'logout_old']);
//token protected API
Route::middleware('basic.auth')->get('/states', [StatesController::class, 'index']);
Route::middleware('basic.auth')->get('/get_cities/{state_id}', [StatesController::class, 'get_cities']);
Route::get('/sanctum/csrf-cookie', [CsrfCookieController::class, 'show']);

// api created by salman start
Route::post('getDoctorList', [ApiDoctorController::class, 'getDoctorList']);
Route::post('SearchDoctor', [ApiDoctorController::class, 'SearchDoctor']);
Route::post('getPatientList', [ApiDoctorController::class, 'getPatientList']);
Route::post('getPatientApp', [ApiDoctorController::class, 'getPatientApp']);
Route::get('getConsultationPlan', [ApiDoctorController::class, 'getConsultationPlan']);
Route::post('getAvailableDates', [ApiDoctorController::class, 'getAvailableDates']);
Route::post('getTimeslots', [ApiDoctorController::class, 'getTimeslots']);
Route::post('Step1Submit', [ApiDoctorController::class, 'Step1Submit']);
Route::post('bookStep2', [ApiDoctorController::class, 'bookStep2']);
Route::post('bookStep3', [ApiDoctorController::class, 'bookStep3']);
Route::post('getAppOnId', [ApiDoctorController::class, 'getAppOnId']);
Route::post('CancelAppointment', [ApiDoctorController::class, 'CancelAppointment']);
Route::post('downloadPDF',[ApiDoctorController::class,'downloadPDF']);
Route::post('notifications',[ApiDoctorController::class,'notifications']);
Route::post('delete_notification',[ApiDoctorController::class,'delete_notification']);
Route::post('messages',[ApiDoctorController::class,'messages']);
Route::post('open_message',[ApiDoctorController::class,'open_message']);
Route::post('ShowChat',[ApiDoctorController::class,'ShowChat']);
Route::post('patient_report_reviews',[ApiDoctorController::class,'patient_report_reviews']);
Route::post('patient_payments_history',[ApiDoctorController::class,'patient_payments_history']);
Route::post('ShowPayment',[ApiDoctorController::class,'ShowPayment']);
Route::post('ResetPassword',[ApiDoctorController::class,'ResetPassword']);
Route::post('UpdaterProfile',[ApiDoctorController::class,'UpdaterProfile']);

Route::post('doctor_dashboard',[ApiDoctorDashboardController::class,'doctor_dashboard']);
Route::post('patient_history',[ApiDoctorDashboardController::class,'patient_history']);
Route::post('FilterOnDoctorDashboard',[ApiDoctorDashboardController::class,'FilterOnDoctorDashboard']);
Route::post('getNotConfirmedAppintments',[ApiDoctorDashboardController::class,'not_confirmed_appintments']);
Route::post('confirm_appointment_v3_post',[ApiDoctorDashboardController::class,'confirm_appointment_v3_post']);
Route::post('getNewMessages',[ApiDoctorDashboardController::class,'getNewMessages']);
Route::post('changeAppointmentStatus',[ApiDoctorDashboardController::class,'changeAppointmentStatus']);
Route::post('confirmAppointment',[ApiDoctorDashboardController::class,'confirmAppointment']);
Route::post('markAsCompleted',[ApiDoctorDashboardController::class,'save_doctor_prescription']);
Route::post('modifyDoctorPrescription',[ApiDoctorDashboardController::class,'modifyDoctorPrescription']);
Route::post('confirm_appointment_v2',[ApiDoctorDashboardController::class,'confirm_appointment_v2']);
Route::post('GetReportReviewByDoctor',[ApiDoctorDashboardController::class,'GetReportReviewByDoctor']);
Route::post('FilterOnReviewByDoctor',[ApiDoctorDashboardController::class,'FilterOnReviewByDoctor']);
Route::post('doctorReplyOnReportReview',[ApiDoctorDashboardController::class,'doctorReplyOnReportReview']);
Route::post('doctorModifyReplyOnReportReview',[ApiDoctorDashboardController::class,'doctorModifyReplyOnReportReview']);
Route::post('getTimeSlotsEvents',[ApiDoctorDashboardController::class,'getTimeSlotsEvents']);
Route::post('CreateTimeslotByDoctor',[ApiDoctorDashboardController::class,'CreateTimeslotByDoctor']);
Route::post('deleteEvent',[ApiDoctorDashboardController::class,'deleteEvent']);
Route::post('getMyCalenderEvents',[ApiDoctorDashboardController::class,'getMyCalenderEvents']);


//end