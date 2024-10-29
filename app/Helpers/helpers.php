<?php

use App\Mail\WelcomeMail;
use Illuminate\Support\Facades\Mail;
//use App\Http\Requests\RegisterRequest;
use App\Models\dbl_users;
use App\Models\UsState;
use App\Models\UsCity;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\SendGridService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;


if (!function_exists('formatPhoneNumber')) {
    function formatPhoneNumber($number) {
        // Remove any characters that are not digits
        $number = preg_replace('/[^0-9]/', '', $number);

        // Check if the number has 10 digits (standard US phone number)
        if (strlen($number) === 10) {
            return sprintf('(%s) %s-%s',
                substr($number, 0, 3),
                substr($number, 3, 3),
                substr($number, 6));
        }

        // If it's not a 10-digit number, return it unformatted
        return $number;
    }
}

if (!function_exists('SendEmail')) {
    function SendEmail($sendGridService, $email_id = NULL, $subject = NULL, $temp_name = null, $patient = NULL, $doctor = NULL, $app_details = NULL, $operation = NULL, $otp = NULL, $reason = NULL, $rec_by = NULL) {
        if ($temp_name) {
            $data['patient'] = $patient;
            $data['doctor'] = $doctor;
            $data['app_details'] = $app_details;
            $data['operation'] = $operation;
            $data['otp'] = $otp;
            $data['reason'] = $reason;
            $data['rec_by'] = $rec_by;
            
            $html_template = View::make($temp_name, $data)->render();

            // echo'<pre>';print_r($html_template);die;

            try { 
                $response = $sendGridService->sendEmail(
                    $email_id,
                    $subject, 
                    $html_template,
                    'text/html' // Specify content type as HTML
                );
    
                Log::info('email sent ::'.$subject." => ".$email_id);

                return $response;
                
            } catch (\Exception $e) {
                // Log the error if email sending fails        
                Log::error('Failed to send welcome email to ' . $email_id . '. Error: ' . $e->getMessage());
                
            }
        }

        // Return null or default if no view name is provided
        return null;
    }
}

function _get_not_replies_report_reviews($user_id = NULL)
{
    $report_review = DB::table('tbl_appointments_booked')
        ->where('active', 1)
        ->where('doctor_id', $user_id)
        ->where('appointmentType', 'Report Review')
        ->where('status', 'Not-Replied')
        ->get([
            'id'
        ]);

    if ($report_review) {
        return $report_review;
    } else {
        return false;
    }
}

function _get_notification_message_count($user_id = NULL){
    $not_msg_count = DB::table('tbl_notifications')
        ->where('red_flag', 0)
        ->where('user_id', $user_id)
        ->get([
            'id'
        ]);

    if ($not_msg_count) {
        return $not_msg_count;
    } else {
        return false;
    }
}

function _get_message_count($user_id = NULL){

    $userData = DB::table('dbl_users')
    ->where('id', $user_id)
    ->get();

    if(count($userData) > 0 && $userData[0]->user_type == 'doctor'){
        $chat_room = DB::table('chats')
            ->where('doctor_id', $user_id)
            ->get();

            $NewArr = [];

            if($chat_room->isNotEmpty()){
                foreach ($chat_room as $key => $val) {
                    $not_msg_count = DB::table('messages')
                        ->join('tbl_appointments_booked', 'messages.app_id', '=', 'tbl_appointments_booked.id') // Join condition
                        ->where('messages.msg_flag', 0)
                        ->where('messages.sender_id', $val->patient_id)
                        ->where('tbl_appointments_booked.doctor_id', $user_id)
                        ->get([
                            'messages.id', // Select fields from messages
                            'tbl_appointments_booked.*' // Select all fields from tbl_appointments_booked (optional)
                        ]);
        
                    if($not_msg_count->isNotEmpty()){
                        array_push($NewArr, $not_msg_count);
                    }
                }
            }

    } else {
        $chat_room = DB::table('chats')
            ->where('patient_id', $user_id)
            ->get();

        $NewArr = [];

        if($chat_room->isNotEmpty()){
            foreach ($chat_room as $key => $val) {
                $not_msg_count = DB::table('messages')
                    ->join('tbl_appointments_booked', 'messages.app_id', '=', 'tbl_appointments_booked.id') // Join condition
                    ->where('messages.msg_flag', 0)
                    ->where('messages.sender_id', $val->doctor_id)
                    ->where('tbl_appointments_booked.patient_id', $user_id)
                    ->get([
                        'messages.id', // Select fields from messages
                        'tbl_appointments_booked.*' // Select all fields from tbl_appointments_booked (optional)
                    ]);
    
                if($not_msg_count->isNotEmpty()){
                    array_push($NewArr, $not_msg_count);
                }
            }
        }
    }

    // echo'<pre>';print_r($chat_room);die;

    if ($NewArr && count($NewArr[0]) > 0) {
        return $NewArr[0];
    } else {
        return false;
    }
}


