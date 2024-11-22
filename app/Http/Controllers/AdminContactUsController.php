<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class AdminContactUsController extends Controller
{
    // Show the list of contact us messages
    public function index()
    {
        $messageData = ContactUs::where('status', 1)->orderBy('id', 'desc')->get();
        return view('admin_contact_us.list-message', compact('messageData'));
    }

    //showing the view form
    public function ViewMessage($id)
    {
        $messageData = ContactUs::findOrFail($id);      
        return view('admin_contact_us.view-message', compact('messageData'));
    }

    public function create(Request $request)
    {
            // Check if the request is a POST
        if ($request->isMethod('post')) {
            // Validate the form data
            $validatedData = $request->validate([
                'category_name' => 'required',               
            ]);

            // Save the validated data to the database
            $Category = new Plan();
            $Category->category_name = $validatedData['category_name'];          
            $Category->save();

            // Redirect or return success message
            return redirect()->route('admin.plan')->with('success', 'Category added successfully!');
        }
        // Render the add-static-page form view
        return view('admin_plan.add-category');
    }

    public function delete($id = null)
    {
        if ($id != null && $id > 0) {
            $UpdatedData['status'] = 0;

            $res = DB::table('tbl_contact_us')
                ->where('id', $id)
                ->update($UpdatedData);

            return redirect('/admin/contact_us')->with('success', 'Contact deleted successfully!');
        }
    }
}
