<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StaticPage;
use App\Models\StaticPageContent;
use App\Models\Faq;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;


class StaticPageController extends Controller
{
    public function create(Request $request)
    {
        // Check if the request is a POST
        if ($request->isMethod('post')) {

            // Validate the form data
            $validatedData = $request->validate([
                'page_name' => 'required|string|max:255',
                'page_title' => 'required|string|max:255',
                'meta_keyword' => 'required|string|max:255',
                'meta_desc' => 'required|string|max:255',
            ]);

            // Save the validated data to the database
            $staticPage = new StaticPage();
            $staticPage->page_name = $validatedData['page_name'];
            $staticPage->page_title = $validatedData['page_title'];
            $staticPage->meta_keyword = $validatedData['meta_keyword'];
            $staticPage->meta_desc = $validatedData['meta_desc'];
            $staticPage->save();

            // Redirect or return success message
            return redirect()->route('admin.add-static-page')->with('success', 'Static page added successfully!');
        }

        // Render the add-static-page form view
        return view('admin_static_pages.add-static-page');
    }

    // Show the list of static pages
    public function listStaticPages()
    {
        $staticPages = StaticPage::all();
        return view('admin_static_pages.list-static-pages', compact('staticPages'));
    }

    public function addPageSection()
    {
        // Fetch all static pages
        $staticPages = StaticPage::all();
        return view('admin_static_pages.add-page-section', compact('staticPages'));
    }

    // Store a newly created page section in storage
    public function storePageSection(Request $request)
    {
        $validated = $request->validate([
            'static_page_id' => 'required|integer|exists:static_pages,id',
            'section_name' => 'required|string|max:255',
            'section_heading1' => 'required|string|max:255',            
        ]);

        $section = new StaticPageContent($validated);

        if ($request->hasFile('section_image1')) {
            $image = $request->file('section_image1');
            
            // Define the path where the image should be stored
            $destinationPath = public_path('frontend/img/static_pages');
            
            // Generate a unique file name for the image
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Move the image to the desired location
            $image->move($destinationPath, $imageName);
            
            // Save the path in the database
            $section->section_image1 = 'frontend/img/static_pages/' . $imageName;
        }

        if ($request->hasFile('section_image2')) {
            $image = $request->file('section_image2');
            
            // Define the path where the image should be stored
            $destinationPath = public_path('frontend/img/static_pages');
            
            // Generate a unique file name for the image
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Move the image to the desired location
            $image->move($destinationPath, $imageName);
            
            // Save the path in the database
            $section->section_image2 = 'frontend/img/static_pages/' . $imageName;
        }

        if ($request->hasFile('section_image3')) {
            $image = $request->file('section_image3');
            
            // Define the path where the image should be stored
            $destinationPath = public_path('frontend/img/static_pages');
            
            // Generate a unique file name for the image
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Move the image to the desired location
            $image->move($destinationPath, $imageName);
            
            // Save the path in the database
            $section->section_image3 = 'frontend/img/static_pages/' . $imageName;
        }

        $section->save();

        return redirect()->route('admin.listPageSections')->with('success', 'Page Section added successfully');
    }

    // Show the list of page sections
    public function listPageSections()
    {
        $pageSections = StaticPageContent::with('staticPage')->get(); // Load related static page data
        return view('admin_static_pages.list-page-sections', compact('pageSections'));
        
    }

    public function editStaticPage($id)
    {
        $staticPage = StaticPage::findOrFail($id);
        return view('admin_static_pages.edit-static-page', compact('staticPage'));
    }

    // Method to update the static page in the database
    public function updateStaticPage(Request $request, $id)
    {
        $request->validate([
            'page_name' => 'required|string|max:255',
            'page_title' => 'required|string|max:255',
            'meta_keyword' => 'required|string|max:255',
            'meta_desc' => 'required|string|max:255',
        ]);

        $staticPage = StaticPage::findOrFail($id);
        $staticPage->update([
            'page_name' => $request->input('page_name'),
            'page_title' => $request->input('page_title'),
            'meta_keyword' => $request->input('meta_keyword'),
            'meta_desc' => $request->input('meta_desc'),
        ]);

        return redirect()->route('admin.listStaticPages')->with('success', 'Static page updated successfully');
    }
     // Show the edit page section form
     public function editPageSection($id)
     {
         // Fetch the page section from the database
         $section = StaticPageContent::findOrFail($id);
         
         $staticPages = StaticPage::all();
    
         // Pass the section data and static pages to the view
         if($id == 1){
            return view('admin_static_pages.edit-home-page', compact('section', 'staticPages'));
         }else{
         return view('admin_static_pages.edit-page-section', compact('section', 'staticPages'));
         }

     }

     public function viewPageSection($id)
     {
         // Fetch the page section from the database
         $section = StaticPageContent::findOrFail($id);
         
         $staticPages = StaticPage::all();
    
         // Pass the section data and static pages to the view
         if($id == 1){
            return view('admin_static_pages.view-home-section', compact('section', 'staticPages'));
         }else{
         return view('admin_static_pages.view-page-section', compact('section', 'staticPages'));
         }

     }
 
     // Update the page section in the database
     public function updatePageSection(Request $request, $id)
     {
         // Validate the request data
         $request->validate([
             'static_page_id' => 'required|integer',
             'section_name' => 'required|string|max:255',
             'section_heading1' => 'required|string|max:255',             
            //  'section_image1' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
             
         ]);
 
         // Find the section to update
         $section = StaticPageContent::findOrFail($id);
 
         // Update the section details
         $section->static_page_id = $request->input('static_page_id');
         $section->section_name = $request->input('section_name');
         $section->section_heading1 = $request->input('section_heading1');
         $section->section_heading2 = $request->input('section_heading2');
         $section->section_heading3 = $request->input('section_heading3');
         $section->section_heading4 = $request->input('section_heading4');
         $section->section_heading5 = $request->input('section_heading5');
         $section->section_heading6 = $request->input('section_heading6');
         $section->section_heading7 = $request->input('section_heading7');
         $section->section_heading8 = $request->input('section_heading8');
         $section->section_heading9 = $request->input('section_heading9');
         $section->section_heading10 = $request->input('section_heading10');
         
         $section->section_short_desc1 = $request->input('section_short_desc1');
         $section->section_short_desc2 = $request->input('section_short_desc2');
         $section->section_short_desc3 = $request->input('section_short_desc3');
         $section->section_short_desc4 = $request->input('section_short_desc4');
         $section->section_short_desc5 = $request->input('section_short_desc5');
         $section->section_short_desc6 = $request->input('section_short_desc6');
         $section->section_short_desc7 = $request->input('section_short_desc7');
         $section->section_short_desc8 = $request->input('section_short_desc8');
         $section->section_short_desc9 = $request->input('section_short_desc9');
         
         $section->section_long_desc1 = $request->input('section_long_desc1');
         $section->section_long_desc2 = $request->input('section_long_desc2');

         $section->step1 = $request->input('step1');
         $section->step2 = $request->input('step2');
         $section->step3 = $request->input('step3');
         /*// Handle image uploads
         if ($request->hasFile('section_image1')) {
            $image = $request->file('section_image1');
            
            // Define the path where the image should be stored
            $destinationPath = public_path('frontend/img/static_pages');
            
            // Generate a unique file name for the image
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Move the image to the desired location
            $image->move($destinationPath, $imageName);
            
            // Save the path in the database
            $section->section_image1 = 'frontend/img/static_pages/' . $imageName;
        }

        if ($request->hasFile('section_image2')) {
            $image = $request->file('section_image2');
            
            // Define the path where the image should be stored
            $destinationPath = public_path('frontend/img/static_pages');
            
            // Generate a unique file name for the image
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Move the image to the desired location
            $image->move($destinationPath, $imageName);
            
            // Save the path in the database
            $section->section_image2 = 'frontend/img/static_pages/' . $imageName;
        }

        if ($request->hasFile('section_image3')) {
            $image = $request->file('section_image3');
            
            // Define the path where the image should be stored
            $destinationPath = public_path('frontend/img/static_pages');
            
            // Generate a unique file name for the image
            $imageName = time() . '_' . $image->getClientOriginalName();
            
            // Move the image to the desired location
            $image->move($destinationPath, $imageName);
            
            // Save the path in the database
            $section->section_image3 = 'frontend/img/static_pages/' . $imageName;
        }*/
        for ($i = 1; $i <= 8; $i++) {
            $fileKey = 'section_image' . $i; // Construct the file input key dynamically
            
            if ($request->hasFile($fileKey) && $request->file($fileKey)->isValid()) {
                $image = $request->file($fileKey);
                
                // Define the path where the image should be stored
                $destinationPath = public_path('frontend/img/static_pages');
                
                // Generate a unique file name for the image
                $imageName = time() . '_' . $image->getClientOriginalName();
                
                // Move the image to the desired location
                $image->move($destinationPath, $imageName);
                
                // Save the path in the database dynamically
                $section->{'section_image' . $i} = 'frontend/img/static_pages/' . $imageName;
            }
        }
        

         // Save the updated section
         $section->save();
 
         // Redirect back with success message
         return redirect()->route('admin.listPageSections')->with('success', 'Page section updated successfully.');
     }


     public function deleteStaticPage($id)
{
    $page = StaticPage::findOrFail($id);
    $page->delete();

    return redirect()->route('admin.listStaticPages')->with('success', 'Static Page deleted successfully');
}

public function deletePageSection($id)
{
    $section = StaticPageContent::findOrFail($id);
    $section->delete();

    return redirect()->route('admin.listPageSections')->with('success', 'Page Section deleted successfully');
}


public function admin_index()
{
    $faqs = FAQ::all(); // Ensure you have a model FAQ for this
    return view('admin_faq.index', compact('faqs'));
}

public function createFAQ()
{
    return view('admin_faq.create');
}

public function storeFAQ(Request $request)
{
    $request->validate([
        'question' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    FAQ::create($request->all());

    return redirect()->route('admin.faq.index')->with('success', 'FAQ added successfully.');
}

public function editFAQ($id)
{
    $faq = FAQ::findOrFail($id);
    return view('admin_faq.edit', compact('faq'));
}

public function updateFAQ(Request $request, $id)
{
    $request->validate([
        'question' => 'required|string|max:255',
        'description' => 'required|string',
    ]);

    $faq = FAQ::findOrFail($id);
    $faq->update($request->all());

    return redirect()->route('admin.faq.index')->with('success', 'FAQ updated successfully.');
}

public function faq_status($faq_id = null, $status = null){
    if($faq_id != null && $faq_id > 0 ){
        if($status == 0){
            $UpdatedData['status'] = 0;
            $msg = 'Faq is inactive';
        } else {
            $UpdatedData['status'] = 1;
            $msg = 'Faq is active.';
        }

        $res = DB::table('faqs')
                            ->where('id', $faq_id)
                            ->update($UpdatedData);       
  
    return redirect('faq')->with('success', $msg);
    }
}

public function destroyFAQ($id)
{
    $faq = FAQ::findOrFail($id);
    $faq->delete();

    return redirect()->route('admin.faq.index')->with('success', 'FAQ deleted successfully.');
}



}

