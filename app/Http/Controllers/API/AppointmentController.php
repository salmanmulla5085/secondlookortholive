<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use App\Services\AppointmentService;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    public function patient_appointments(Request $request, $record_type = 'upcoming')
    {
        // Extract the token from the request
        
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        // Fetch appointments
        $appointments = $this->appointmentService->fetchAppointments($user, $record_type);

        // Return response
        return response()->json([
            'record_type' => $record_type,
            'result' => $appointments,            
            'debug_user'=>$user
        ]);
    }

    public function doctor_appointments(Request $request, $record_type = 'todays')
    {
        // Extract the token from the request
        $token = $request->bearerToken();

        // Get the user using AppointmentService
        $user = $this->appointmentService->getUserFromToken($token);

        if (!$user) {
            return response()->json(['error' => 'Invalid token or user not found'], 401);
        }

        // Fetch appointments
        $appointments = $this->appointmentService->fetchAppointments($user, $record_type);

        // Return response
        return response()->json([
            'record_type' => $record_type,
            'result' => $appointments,            
            'debug_user'=>$user
        ]);
    }
    
}
