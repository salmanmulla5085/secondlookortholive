<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Notifications\Notifiable;
use App\Models\AdminUser;
use App\Models\dbl_users;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use App\Notifications\ForgotPassword;

class ResetPassword extends Controller
{
    use Notifiable;

    public function show()
    {
        return view('auth.reset-password');
    }

    public function ResetPassword(Request $request){
        $user = $this->getSessionData('user');   
        if ($request->isMethod('post')) 
        {
            $validatedData = $request->validate([
                'old_password' => ['required'],
                'password' => ['required'],
                'confirm_password' => ['required']
            ]);

            if($request->old_password && $request->old_password != ''){
                $old_password = md5($request->old_password);
                $user_id = $user->id;

                $check_password = dbl_users::where('id', $user->id)
                    ->where('password', $old_password)  // Add more conditions here
                    ->first();
                
                if($check_password){
                    $UserData['password'] = md5($request->password);

                    $res = DB::table('dbl_users')
                    ->where('id', $user_id)
                    ->update($UserData);

                    return redirect('/settings')->with('success', 'Password updated successfully!');
                } else {
                    return redirect('/settings')->with('error', 'Old password does not match!');
                }
            }
        }
    }

    public function routeNotificationForMail() {
        return request()->email;
    }

    public function send(Request $request)
    {
        $email = $request->validate([
            'email' => ['required']
        ]);
        $user = AdminUser::where('email', $email)->first();

        if ($user) {
            $this->notify(new ForgotPassword($user->id));
            return back()->with('succes', 'An email was send to your email address');
        }
    }
}
