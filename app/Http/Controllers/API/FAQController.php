<?php



namespace App\Http\Controllers\API;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Faq;
use Illuminate\Http\JsonResponse;

class FAQController extends Controller
{
    public function index(): JsonResponse
    {
        // Fetch all FAQs
        $faqs = Faq::select('id', 'question', 'description')->get();

        // Return as JSON response
        return response()->json($faqs);
    }
}
