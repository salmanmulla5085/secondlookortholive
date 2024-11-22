<?php
namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UsState;
use App\Models\UsCity;
use Illuminate\Http\JsonResponse;

class StatesController extends Controller
{
    public function index(): JsonResponse
    {
        // Fetch all FAQs
        $faqs = UsState::get();

        // Return as JSON response
        return response()->json($faqs);
    }
    
     public function get_cities($state_id = null): JsonResponse
    {
        // Fetch all FAQs
        if(!empty($state_id))
        $faqs = UsCity::where("ID_STATE",$state_id)->get();

        // Return as JSON response
        return response()->json($faqs);
    }
}
