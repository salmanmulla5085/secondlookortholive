<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\StaticPage;
use App\Models\StaticPageContent;

class StaticPageController extends Controller
{
    public function getPageSections(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'page_name' => 'required|string|max:255',
        ]);

        $pageName = $request->query('page_name');
        if (!$pageName) {
            return response()->json(['error' => 'page_name is required'], 400);
        }
        
        // Find the StaticPage matching the page_name
        $staticPage = StaticPage::where('page_name', $pageName)->first();

        // If no matching page is found, return an error response
        if (!$staticPage) {
            return response()->json([
                'error' => 'Page not found',
            ], 404);
        }

        // Retrieve all related StaticPageContent rows
        $pageSections = StaticPageContent::where('static_page_id', $staticPage->id)->get();

        // Return the page data along with its sections
        return response()->json([
            'page' => $staticPage,
            'sections' => $pageSections,
        ]);
    }
}
