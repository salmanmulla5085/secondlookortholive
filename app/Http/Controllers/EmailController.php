<?php

namespace App\Http\Controllers;

use App\Services\SendGridService;

class EmailController extends Controller
{
    protected $sendGridService;

    public function __construct(SendGridService $sendGridService)
    {
        $this->sendGridService = $sendGridService;
    }

    public function sendEmail()
    {
        $to = 'zahoor.aviontech@gmail.com';
        $subject = 'Hello, second look ortho!';
        $content = 'Appointment booked successfully';

        try {
            $response = $this->sendGridService->sendEmail($to, $subject, $content);
            return response()->json(['message' => 'Email sent successfully', 'response' => $response]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
