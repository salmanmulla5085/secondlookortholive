<?php
use Illuminate\Support\Facades\Log;
use App\Models\dbl_users;
use App\Models\Schedule;
use Carbon\Carbon;

if (!function_exists('sendSmsCurl')) {
    function sendSmsCurl($to, $message)
    {
        
        try
        {

        $sid = env('TWILIO_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $from = env('TWILIO_NUMBER');

        $url = 'https://api.twilio.com/2010-04-01/Accounts/' . $sid . '/Messages.json';

        $data = [
            'From' => $from,
            'To' => $to,
            'Body' => $message,
        ];

        
        // $auth = base64_encode($sid . ':' . $token);

        // // Make the HTTP request using Laravel's Http Client
        // $response = \Illuminate\Support\Facades\Http::withHeaders([
        //     'Authorization' => 'Basic ' . $auth,
        // ])->post($url, $data);

        // var_dump($data);
        // dd($response->json());

        // // Log the response or display it
        // Log::info('Twilio Response', ['response' => $response->json()]);
        
        // // Check if the response status is successful (200 range)
        // if ($response->successful()) {
        //     return true;
        // } else {
        //     // Log or display the error message
        //     Log::error('Twilio Error', ['error' => $response->body()]);
        //     return false;
        // }


        //curl using
        $post = http_build_query($data);
        $auth = $sid . ':' . $token;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Basic ' . base64_encode($auth)]);
        
        $response = curl_exec($ch);
        curl_close($ch);

        $response_data = json_decode($response, true);

        if(isset($response_data['sid'])){
            return isset($response_data['sid']);
        } else {
            return true;
        }
         // True if SMS sent successfully
        }
        catch(\Exception $e)
                {
                    Log::error("Exception SMS:: ", ["error"=>$e->getMessage()]);
                    return false;
                }
    }
}


function sendSms($patient = null,$doctor_details = null,$app_details = null,$sms_type = null,$otp = null)
{
                //Confirm , Book , Register, Cancel, Reject Complete, Reminder before app, 
                // Reschedule sms to doctor, Forgot password verification code
                try
                {

                    if($sms_type == null)
                    {
                        $log_failed_message = "SMS failed :: sms_type is blank";
                        Log::info($log_failed_message);                        
                        return false;
                    }

                    $sms_datetime_string = '';
                    $to = '';
                    $message = '';                            
                    $log_message = '';
                    $log_failed_message = '';  

                    if(!empty($app_details) && $app_details !=null)
                    {   
                            // $app_details = $app_details[0];
                            $app_date = Carbon::parse($app_details->start)->Format('j F Y');
                            $app_startTime = Carbon::parse($app_details->start)->Format('G:i');
                            $app_endTime = Carbon::parse($app_details->end)->Format('G:i');        
                            $sms_datetime_string = $app_date." at ".$app_startTime;          
                    } 

                    
                    if($sms_type == "Report_Book" && $doctor_details != null && $app_details != null)
                    {
                        $rec_by = 'patient';
                        $message_title = 'Appointment Report Review booked with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.'.';
                        $message = 'Thank you for booking report review on SecondLookOrtho! with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.'.';    
                        $to = $app_details->contactNumber;
                        $log_message = $sms_type." SMS sent successfully to :: patient_id".$app_details->patient_id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: patient_id".$app_details->patient_id."  Phone:".$to;
                    }
                    
                    if($sms_type == "Report_Book_To_Doctor" && $app_details != null)
                    {
                        $rec_by = 'doctor';
                        $message_title = $app_details->name." has booked report review with you.";
                        $message = $app_details->name." has booked report review with you.";    
                        $to = $doctor_details->phone_number;
                        $log_message = $sms_type." SMS sent successfully to :: doctor_id".$app_details->doctor_id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: doctor_id".$app_details->doctor_id."  Phone:".$to;
                    }

                    if($sms_type == "Book_To_Doctor" && $app_details != null)
                    {   
                        $rec_by = 'doctor';
                        $message_title = $app_details->name. ' has booked appointment with you.';
                        $message = $app_details->name." has booked appointment with you on ".$sms_datetime_string.'.';    
                        $to = $doctor_details->phone_number;
                        $log_message = $sms_type." SMS sent successfully to :: doctor_id".$app_details->doctor_id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: doctor_id".$app_details->doctor_id."  Phone:".$to;
                    }

                    if($sms_type == "Reminder" && !empty($sms_datetime_string) && $app_details != null && $doctor_details != null)
                    {       
                            $rec_by = 'patient';
                            $message_title = 'Appointment reminder';
                            $to = $app_details->contactNumber;                            
                            $message = 'Just a reminder: Your appointment on SecondLookOrtho! with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.' is set for '.$sms_datetime_string;    

                            $log_message = $sms_type." SMS sent successfully to patient";
                            $log_failed_message = $sms_type." SMS Failed  to patient";
                            
                    }

                    if($sms_type == "Reminder_To_Doctor" && !empty($sms_datetime_string) && $app_details != null && $doctor_details != null)
                    {   
                            $rec_by = 'doctor';
                            $message_title = 'Appointment reminder';
                            $to = $doctor_details->phone_number;                            
                            $message = 'Just a reminder: Your appointment on SecondLookOrtho! with Patient '.$app_details->name.' is set for '.$sms_datetime_string;    

                            $log_message = $sms_type." SMS sent successfully to doctor";
                            $log_failed_message = $sms_type." SMS Failed to doctor";
                            
                    }

                    if($sms_type == "Confirm" && !empty($sms_datetime_string) && $app_details != null  && $doctor_details != null)
                    {
                            $rec_by = 'patient';
                            $message_title = 'Appointment Confirmed with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.'.';
                            $to = $app_details->contactNumber;                            
                            $message = 'Thank you for booking appointment on SecondLookOrtho! with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.'. Your appointment is confirmed for '.$sms_datetime_string;    
                            $log_message = $sms_type." SMS sent successfully to :: patient_id".$app_details->patient_id."  Phone:".$to;
                            $log_failed_message = $sms_type." SMS Failed to :: patient_id".$app_details->patient_id."  Phone:".$to;
                            
                    }
                        
                    if($sms_type == "Book" && $doctor_details != null && !empty($sms_datetime_string) && $app_details != null)
                    {
                        $rec_by = 'patient';
                        $message_title = 'Appointment Booked with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.'.';
                        $message = 'Thank you for booking appointment on SecondLookOrtho! with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.'. Your appointment is book for '.$sms_datetime_string.'.';    
                        $to = $app_details->contactNumber;
                        $log_message = $sms_type." SMS sent successfully to :: patient_id".$app_details->patient_id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: patient_id".$app_details->patient_id."  Phone:".$to;
                    }

                    if($sms_type == "Cancelled" && $doctor_details != null)
                    { 
                        $rec_by = 'patient';
                        $message_title = 'Appointment Cancelled with Dr. '.$doctor_details->first_name.' '.$doctor_details->last_name.'.';
                        $message = 'Your appointment on SecondLookOrtho! with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.' has been cancelled by doctor, Please reschedule appointment from Dashboard';    
                        $to = $app_details->contactNumber;
                        $log_message = $sms_type." SMS sent successfully to :: patient_id".$app_details->patient_id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: patient_id".$app_details->patient_id."  Phone:".$to;
                    }

                    if($sms_type == "Cancelled_To_Doctor" && $doctor_details != null && $app_details != null && !empty($sms_datetime_string))
                    {
                        $rec_by = 'doctor';
                        $message_title = 'Appointment Cancelled with '.$app_details->name.'.';
                        // $message_title = 'Appointment Cancelled with Dr. '.$doctor_details->first_name.' '.$doctor_details->last_name.'.';
                        $message = $app_details->name.' has cancelled his appointment with you on '.$sms_datetime_string;    
                        $to = $doctor_details->phone_number;
                        $log_message = $sms_type." SMS sent successfully to :: doctor_id".$app_details->doctor_id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: doctor_id".$app_details->doctor_id."  Phone:".$to;
                    }

                    if($sms_type == "Rescheduled_To_Doctor" && $doctor_details != null && $app_details != null && !empty($sms_datetime_string))
                    {
                        $rec_by = 'doctor';
                        $message_title = 'Appointment Reschedule with '.$app_details->name.'.';
                        $message = $app_details->name.' has rescheduled his appointment with you on '.$sms_datetime_string; 
                        $to = $doctor_details->phone_number;
                        $log_message = $sms_type." SMS sent successfully to :: doctor_id".$app_details->doctor_id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: doctor_id".$app_details->doctor_id."  Phone:".$to;
                    }

                    if($sms_type == "Rescheduled_To_Patient" && $doctor_details != null && $app_details != null && !empty($sms_datetime_string))
                    {
                        $rec_by = 'patient';
                        $message_title = 'Appointment Reschedule with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.'.';
                        $message = 'Your appointment on SecondLookOrtho! with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.' has been rescheduled.';    
                        $to = $app_details->contactNumber;
                        $log_message = $sms_type." SMS sent successfully to :: patient_id".$app_details->patient_id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: patient_id".$app_details->patient_id."  Phone:".$to;
                    }

                    if($sms_type == "Replied_To_Patient" && $doctor_details != null && $app_details != null && !empty($sms_datetime_string))
                    {
                        $rec_by = 'patient';
                        $message_title = 'Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.' replied on your report review.';
                        $message = 'Dr. '.$doctor_details->first_name.' '.$doctor_details->last_name.' replied on your report review on SecondLookOrtho!.';    
                        $to = $app_details->contactNumber;
                        $log_message = $sms_type." SMS sent successfully to :: patient_id".$app_details->patient_id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: patient_id".$app_details->patient_id."  Phone:".$to;
                    }

                    if($sms_type == "Rejected" && $doctor_details != null)
                    {
                        $rec_by = 'patient';
                        $message_title = 'Appointment rejected by Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.'.';
                        $message = 'Your appointment on SecondLookOrtho! with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.' has been rejected by doctor, Please reschedule appointment from Dashboard';    
                        $to = $app_details->contactNumber;
                        $log_message = $sms_type." SMS sent successfully to :: patient_id".$app_details->patient_id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: patient_id".$app_details->patient_id."  Phone:".$to;
                    }


                    if($sms_type == "Completed" && $doctor_details != null)
                    {   
                        $rec_by = 'patient';
                        $message_title = 'Appointment Completed with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.'.';
                        $message = 'Thank you for booking appointment on SecondLookOrtho! with Dr.'.$doctor_details->first_name.' '.$doctor_details->last_name.', appointment has been completed by doctor.';    
                        $to = $app_details->contactNumber;
                        $log_message = $sms_type." SMS sent successfully to :: patient_id".$app_details->patient_id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: patient_id".$app_details->patient_id."  Phone:".$to;
                    }

                    if(($sms_type == "Register" || $sms_type == "forgot_password") && $otp != null && $patient != null && !empty($patient->phone_number))
                    {   
                        if($sms_type == "Register"){
                            $message = $otp.' is your OTP to signup on SecondLookOrtho.';
                        } else {
                            $message = $otp.' is your OTP to change password on SecondLookOrtho.';
                        }
                        
                        $to = $patient->phone_number;
                        $log_message = $sms_type." SMS sent successfully to :: patient_id".$patient->id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: patient_id".$patient->id."  Phone:".$to;
                                    
                    }

                    if($sms_type == "admin_otp" && $otp != null && $patient != null && !empty($patient->phone_number))
                    {
                        $message = $otp.' is your OTP to login on SecondLookOrtho admin.';
                        $to = $patient->phone_number;
                        $log_message = $sms_type." SMS sent successfully to :: patient_id".$patient->id."  Phone:".$to;
                        $log_failed_message = $sms_type." SMS Failed to :: patient_id".$patient->id."  Phone:".$to;
                                    
                    }

                    if(!empty($message) && !empty($to))
                    {
                        $to = str_replace("(","",$to);
                        $to = str_replace(")","",$to);
                        $to = str_replace(" ","",$to);
                        $to = str_replace("-","",$to);
                        $to = '+1'.$to;

                        if($sms_type != "Register" && $sms_type != "forgot_password" && $sms_type != "admin_otp"){

                            if($rec_by == 'patient'){
                                $user_id = $app_details->patient_id;
                            } else {
                                $user_id = $app_details->doctor_id;
                            }
        
                            if($user_id && $user_id != ''){
                                $EmailEntry = ([
                                    'user_id'       =>      $user_id,
                                    'title'         =>      $message_title,
                                    'description'   =>      $message,
                                    'received_by'   =>      $rec_by
                                ]);
                
                                $insertedId = DB::table('tbl_notifications')->insertGetId($EmailEntry);
                            }
        
                        }
                
                        if (sendSmsCurl($to, $message)) {
                            Log::info($log_message);
                            return true;
                        } else {                
                            Log::info($log_failed_message);                        
                            return false;
                        }
                    }
                    else
                    {
                    Log::info('blank to and message sms_type = '.$sms_type);                        
                    return false;
                    }
                }   
                catch(\Exception $e)
                {
                    Log::error("Exception SMS:: ", ["error"=>$e->getMessage()]);
                    return false;
                }
}