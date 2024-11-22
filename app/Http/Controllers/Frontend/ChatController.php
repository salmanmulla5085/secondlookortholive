<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 
use App\Models\Chat;
use App\Models\dbl_users;
use App\Models\Message;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ChatController extends Controller
{

    public function messages_list()
    {
        $data['PageName'] = 'Message';
        
        // $data['appointments'] = $appointments = Schedule::where('status','Confirm');

        $now = Carbon::now();
        

        $sql = "SELECT * 
                FROM tbl_appointments_booked 
                WHERE status = 'Confirm' 
                AND end <= NOW() 
                AND DATE_ADD(end, INTERVAL 1 DAY) > NOW()";
        
        $data["appointments"] = DB::select($sql);
        
        // dd($schedules);   

        return view('frontend.messages-list', $data);
    }

    public function initiateChat(Request $request)
    {
        // Assume the request contains the doctor's ID
        $doctorId = $request->doctor_id;
        $patientId = $request->patient_id;
        $appointmentId = $request->appointment_id;
        
        if($request->from_msg && $request->from_msg != ''){
            $from_msg = $request->from_msg;
        } else {
            $from_msg = 0;
        }
        
        $appointment = Schedule::find($appointmentId); 
        
        $res = DB::table('tbl_appointments_booked')
            ->where('id', $appointmentId)
            ->update(["msg_flag"=>1]);

        // Call the createChat function to start a new chat
        $user = $this->getSessionData('user');

        if(!empty($doctorId))
        {
            $sender_id = $doctorId;

            $chat = Chat::firstOrCreate([
                'doctor_id' => $doctorId,
                'patient_id' => $user["id"]
            ]);
        }

        if(!empty($patientId))
        {
            $sender_id = $patientId;

            $chat = Chat::firstOrCreate([
                'doctor_id' => $user["id"],
                'patient_id' => $patientId
            ]);
        }

        $res = DB::table('messages')
            ->where('chat_id', $chat->id)
            ->where('sender_id', $sender_id)
            ->update(["msg_flag"=>1]);

        // echo $chat->id;die;
        // echo $appointmentId;
        // die;
        // Redirect to the chat page
        return redirect()->route('chat.show', ['chatId' => Crypt::encrypt($chat->id),'appointmentId' => Crypt::encrypt($appointmentId), 'from_msg' => $from_msg]);
        // return redirect()->route('chat.show', ['chatId' => $chat->id]);
    }

    // public function createChat(Request $request)
    // {
    // // Create a new chat session
    // $chat = Chat::create([
    //     'doctor_id' => $request->doctor_id,
    //     'patient_id' => Auth::id(),
    // ]);

    // // Now you have $chat->id available
    // return redirect()->route('chat.show', ['chatId' => $chat->id]);
    // }

    public function showChat($chatId, $appointmentId, $from_msg = null)
    {
        $chatId = Crypt::decrypt($chatId);
        $appointmentId = Crypt::decrypt($appointmentId);
        $this->setSessionData("msg_or_all",$from_msg); 
        // Fetch the chat session details
        $chat = Chat::with(['doctor', 'patient'])->findOrFail($chatId); 
        $appointment = Schedule::find($appointmentId);        
        $endDateTime = new \DateTime($appointment->end);
        $currentDateTime = new \DateTime();
        $interval = $currentDateTime->diff($endDateTime);        
        $isWithin24Hours = ($interval->days == 0 && $interval->h < 24);

        // dd($isWithin24Hours);
        
        // Ensure the logged-in user is either the doctor or the patient in this chat
        $user = $this->getSessionData('user');
        
        if ($user["id"] !== $chat->doctor_id && $user["id"] !== $chat->patient_id) {
            abort(403, 'Unauthorized access to this chat.');
        }

        // dd($chat);
        $user = $this->getSessionData('user');
        
        if($user["user_type"] == "patient")
        $opp_user_data = dbl_users::find($chat->doctor_id);                  

        if($user["user_type"] == "doctor")
        $opp_user_data = dbl_users::find($chat->patient_id); 

        $this->setSessionData("msg_or_all_app_id",$appointment['id']); 

        if($chatId != '' && $appointmentId != ''){
            $updateFlag['msg_flag'] = 1;
            $res = DB::table('messages')
                ->where('chat_id', $chatId)
                ->where('app_id', $appointmentId)
                ->where('sender_id', $opp_user_data['id'])
                ->update($updateFlag);
        }

        // Pass the chat session data to the view        
        return view('frontend/chat',[
            'chat_id' => Crypt::encrypt($chatId),
            'chat' => $chat,
            'isWithin24Hours' => $isWithin24Hours,
            'user_data'=>$user,
            'opp_user_data'=>$opp_user_data,
            'user_type'=>$user["user_type"],
            'appointment'=>$appointment,
            'PageName'=>"Messages"            
        ]);
        
    }


    // public function createChat(Request $request)
    // {
    //     $chat = Chat::create([
    //         'doctor_id' => $request->doctor_id,
    //         'patient_id' => Auth::id(),
    //     ]);

    //     return response()->json($chat);
    // }

    

    public function listChats()
    {
    $chats = Chat::where('doctor_id', Auth::id())
                    ->orWhere('patient_id', Auth::id())
                    ->get();

    return view('chat-list', compact('chats'));
    }

    public function sendMessage(Request $request, $chatId)
    {
        if($chatId != ''){
            $chatId = Crypt::decrypt($chatId);
        }
        
        if($request->message && $request->message != ''){
            $message = $request->message;
            $message = Crypt::encrypt($message);
        } else {
            $message = NULL;
        }
        
        $report_file_names = "0";
        if (isset($_FILES['medicalDocuments']) && !empty($_FILES['medicalDocuments']['name'][0]))
        {

            if($_POST['message'] && $_POST['message'] != ''){
                $message = $_POST['message'];
                $message = Crypt::encrypt($message);
            } else {
                $message = NULL;
            }

            $fileNames = [];
            $errors = [];

            foreach ($_FILES['medicalDocuments']['name'] as $key => $name) {
                $tmpName = $_FILES['medicalDocuments']['tmp_name'][$key];
                $size = $_FILES['medicalDocuments']['size'][$key];
                $error = $_FILES['medicalDocuments']['error'][$key];
                
                // Validate file
                if ($error === UPLOAD_ERR_OK) {
                    // Specify allowed file types and size limit (e.g., 2MB)
                    $allowedTypes = ['pdf','jpeg','jpg','png'];
                    $maxSize = 4 * 1024 * 1024; // 2MB
                    $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    
                    $new_name = uniqid().".".$fileExt;
                    
                    if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                        // Move uploaded file to the 'uploads' directory
                        $filePath = 'public/chat_files/' . $new_name;
                        if (move_uploaded_file($tmpName, $filePath)) {
                            // Save file information to the database
                            
                            $fileNames[] = $new_name;
                        } else {
                            $errors[] = "Failed to move file $name.";
                        }
                    } else {
                        
                        $errors[] = "Invalid file type or size for file $name.";
                    }
                } else {
                    $errors[] = "Error uploading file $name. Error code: $error.";
                }
            }

            // Add existing file login
            if($fileNames && count($fileNames) > 0){
                $report_file_names = implode(",",$fileNames);
            }
            
            if (!empty($errors)) {
                // echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                // die;
                $errors = implode('<br>', $errors);
                return redirect('/book_appointment_step2')
                        ->with('error', $errors);
            } else {
                // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                // die;
            }
        }

        $user = $this->getSessionData('user');

        $message = Message::create([
            'chat_id' => $chatId,
            'app_id'  => $request->app_id,
            'sender_id' => $user["id"],
            'message' => $message,
            'files' => $report_file_names,
        ]);

        if($user != ''){

            $sql = "SELECT * FROM dbl_users where id=".$user['id'];
            $UserData = DB::select($sql);
            $UserData = collect($UserData);

            $sql = "SELECT * FROM tbl_appointments_booked where id=".$request->app_id;
            $ext_app_sql = DB::select($sql);
            $app_details = collect($ext_app_sql);
            
            if($user['user_type'] == 'patient'){
                $user_id = $app_details[0]->doctor_id;
                $message_title = 'Message Received';
                $message = $UserData[0]->first_name.' '.$UserData[0]->last_name.' has been send message to you.';
                $rec_by = 'doctor';
            } else {
                $user_id = $app_details[0]->patient_id;
                $message_title = 'Message Received';
                $message = 'Dr. '.$UserData[0]->first_name.' '.$UserData[0]->last_name.' has been send message to you.';
                $rec_by = 'patient';
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

        return response()->json($message);
    }

    public function getMessages($chatId, $message = null,$FromMsg = null)
    {
        $msg_or_all = $this->getSessionData('msg_or_all');
        
        $chatId = Crypt::decrypt($chatId);

        if($msg_or_all == 1){
            $msg_or_all_app_id = $this->getSessionData('msg_or_all_app_id');
            $messages = Message::where('chat_id', $chatId)
                        ->where('app_id', $msg_or_all_app_id )
                        ->get();
        } else {
            $messages = Message::where('chat_id', $chatId)->get();
        }

        // Decrypt each message
        $decryptedMessages = $messages->map(function ($message) {
            // Check if the message field is not empty and decrypt it
            $message->message = !empty($message->message) ? Crypt::decrypt($message->message) : '';
            return $message;
        });

        return response()->json($decryptedMessages);
    }
}
