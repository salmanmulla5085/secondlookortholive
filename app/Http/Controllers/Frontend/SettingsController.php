<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller; 
use Illuminate\Http\Request;
use App\Models\dbl_users;
use App\Models\UsState;
use App\Models\UsCity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index(){
        $data['PageName'] = 'Settings';
        $data['icon'] = 'Group 9677.png';
        return view('frontend.settings', $data);
    }
    
    public function account_update(Request $request, $reset_id = null){
        $data['PageName'] = 'Account';
        $data['icon'] = 'Group.png';
        $user = $this->getSessionData('user');
        if(empty($reset_id)){
            $data["user"] = $user = dbl_users::find($user->id);
        }
        
        if ($request->isMethod('post')) 
        {
                // Validate the incoming request data
                $validatedData = $request->validate([
                    'name' => 'required|string|max:255',
                    'gender' => 'nullable|in:male,female,other',
                    'age' => 'nullable|integer|min:0|max:99',
                    'email_address' => 'required|email|max:255',
                    'phone_number' => 'required',
                    'alternateContactNumber' => 'nullable',
                    'state' => 'required|string|max:2',
                    'city' => 'required|string|max:255',
                    'speciality' => 'nullable',
                    'experience' => 'nullable',
                    'degree' => 'nullable',
                    'about' => 'nullable',
                    'allergies' => 'nullable',
                    'MedicalHistory' => 'nullable',
                    'profile_photo.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
                    'medicalDocuments.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
                ], [
                    'name.required' => 'Name is required',
                    'email_address.required' => 'Email is required',
                    'phone_number.required' => 'Contact number is required',
                    'state.required' => 'State is required',
                    'city.required' => 'City is required',
                ]);
                
                if (isset($_FILES['profile_photo']) && !empty($_FILES['profile_photo']['name'][0]))
                {
                    
                    $fileNames = [];
                    $errors = [];
        
                        foreach ($_FILES['profile_photo']['name'] as $key => $name) {
                            $tmpName = $_FILES['profile_photo']['tmp_name'][$key];
                            $size = $_FILES['profile_photo']['size'][$key];
                            $error = $_FILES['profile_photo']['error'][$key];
                            
                            // Validate file
                            if ($error === UPLOAD_ERR_OK) {
                                // Specify allowed file types and size limit (e.g., 2MB)
                                $allowedTypes = ['pdf','jpeg','jpg','png'];
                                $maxSize = 4 * 1024 * 1024; // 2MB
                                $fileExt = strtolower(pathinfo($name, PATHINFO_EXTENSION));
                
                                $new_name = uniqid().".".$fileExt;
                                
                                if (in_array($fileExt, $allowedTypes) && $size <= $maxSize) {
                                    // Move uploaded file to the 'uploads' directory
                                    if($user['user_type'] === 'patient'){
                                        $filePath = 'public/patient_photos/' . $new_name;
                                    } else {
                                        $filePath = 'public/doctor_photos/' . $new_name;
                                    }
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
                        
                        $profile_file_names = implode(",",$fileNames);
                        
                        $validatedData['profile_photo'] = $profile_file_names;
                        
                        
                        if (!empty($errors)) {
                            echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                            die;
                        } else {
                            // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                            // die;
                        }
                } else {
                    
                }

                if (isset($_FILES['medicalDocuments']) && !empty($_FILES['medicalDocuments']['name'][0]))
                {
                    
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
                                    $filePath = 'public/patient_reports/' . $new_name;
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
                        
                        $report_file_names = implode(",",$fileNames);
                        
                        $validatedData['medicalDocuments'] = $report_file_names;
                        
                        
                        if (!empty($errors)) {
                            echo '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                            die;
                        } else {
                            // echo '<div class="alert alert-success">Files uploaded successfully: ' . implode(', ', $fileNames) . '</div>';
                            // die;
                        }
                } else {
                    
                }
                
                
                $stateId = $request->state;

                $state = UsState::find($stateId);

                $stateName = $state ? $state->STATE_NAME : null; 

                // Calculate timezone based on state (dummy implementation, customize as needed)
                $timezone = $this->getTimezoneByState($stateName); 
                
                $full_name = $request->name;
                
                if(!empty($full_name)){
                    $ImpFullName = explode(' ', $full_name);
                    $validatedData['first_name'] = $ImpFullName[0];
                    $validatedData['last_name'] = $ImpFullName[1];
                }
                
                $validatedData['timezone'] = $timezone;
                
                unset($validatedData['name']);
                
                $res = DB::table('dbl_users')
                ->where('id', $user->id)
                ->update($validatedData);

                $user = dbl_users::where('id', $user->id)->first();                
                $this->setSessionData('user', $user);                

                // if ($res) { 
                    return redirect('acc-update')->with('success', 'Profile updated successfully!');
                // }
        }
        
        return view('frontend.account', $data);
    }

    // ReportReviewsRepliesController.php
    public function deleteUserFile(Request $request)
    {
        $userId = $request->input('userId');
        $filename = $request->input('filename');
        
        $user = dbl_users::find($userId);
        if ($user) {
            // Get existing filenames
            $files = explode(',', $user->medicalDocuments);
            
            // Remove the file to be deleted
            $files = array_filter($files, function($file) use ($filename) {
                return trim($file) !== $filename;
            });

            // Update the column with the new comma-separated values
            $user->medicalDocuments = implode(',', $files);
            $user->save();
            
            return response()->json(['success' => true]);
        }
        
        return response()->json(['success' => false], 404);
    }
}
