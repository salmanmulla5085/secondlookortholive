<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\StaticPageContent;
use App\Models\Faq;
use App\Models\Plan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class AdminPlansController extends Controller
{
    // Show the list of Plan
    public function listplans()
    {
        // $pageSections = StaticPageContent::with('staticPage')->get(); // Load related static page data       
        $planData = Plan::all();      
        return view('admin_plan.list-plan', compact('planData'));
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
    
    public function plan_status($id = null, $status = null)
    {
        if ($id != null && $id > 0) {
            if ($status == 'Inactive') {
                $UpdatedData['status'] = 'Inactive';
                $msg = 'Plan is inactive';
            } else {
                $UpdatedData['status'] = 'Active';
                $msg = 'Plan is active.';
            }

            $res = DB::table('tbl_plans')
                ->where('id', $id)
                ->update($UpdatedData);

            return redirect('/admin/plan')->with('success', $msg);
        }
    }


    //showing the edit form
    public function editPlan($id)
    {
        $planData = Plan::findOrFail($id);      
        return view('admin_plan.edit-plan', compact('planData'));
    }

    public function updatePlan(Request $request, $id)
    {
        

        // Check if the request is a POST
        if ($request->isMethod('post')) {
            // Validate the form data
            $validatedData = $request->validate([
                'plan_type' => 'required',
                // 'plan_duration' => 'required',
                'plan_amount' => 'required',
                'plan_detail' => 'required',

            ]);
            
            $Plan = Plan::findOrFail($id);
            $Plan->update([
                'plan_type' => $request->input('plan_type'),
                'plan_duration' => $request->input('plan_duration'),
                'plan_amount' => $request->input('plan_amount'), 
                'plan_detail' => $request->input('plan_detail'),              
            ]);

            // Redirect or return success message
            return redirect()->route('admin.plan')->with('success', 'Plan updated successfully!');
        }

        // // Render the add-static-page form view
        // return view('admin_article.ed-article', compact('planData'));
    }

    public function deletePlan($id)
    {
        $category = Plan::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.plan')->with('success', 'Plan deleted successfully');
    }
}
