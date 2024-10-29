<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\StaticPageContent;
use App\Models\Faq;
use App\Models\Categories;
use App\Models\Schedule;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class ReportController extends Controller
{
    // Show the list of all reports
    public function ReportsList(Request $request)
    {

        $currentDate = Carbon::now()->startOfDay(); 
        // $currentDate = date('Y-m-d');
        // dd($currentDate);die();
        $query = Schedule::with(['doctor', 'patient', 'payment'])
            ->whereNotNull('payment_id');

        // Apply doctor filter
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }

        // Apply status filter
        if ($request->filled('appointmentType')) {
            $query->where('appointmentType', $request->appointmentType);
        }
        if ($request->filled('status')) {
            if($request->status == "Upcoming"){
                $query->where('status', 'In-Process')
              ->where('start', '>', $currentDate);
            }elseif($request->status == "Expired"){
                $query->where('status', 'In-Process')
              ->where('start', '<', $currentDate);
            }elseif($request->status == "In-Process"){
                $query->where('status', 'In-Process')
              ->where('start', '>=', $currentDate);
            }elseif($request->status == "Replied"){
                $query->where('status', 'LIKE', 'Replied');
            }elseif($request->status == "Not-Replied"){
                $query->where('status', 'LIKE', '%Not-Replied%');
            }else{          
            $query->where('status', 'LIKE', '%' . $request->status . '%');
            }
        }

        // //  this code added on test server check the test server code
        // if ($request->filled('start') && $request->filled('end')) {
        //     $query->whereRaw('DATE(start) >= ? AND DATE(start) <= ?', [$request->start, $request->end]);
        //     // $query->whereBetween('start', [$request->start, $request->end]);
        // } elseif ($request->filled('start')) {
        //     $query->where('start', '>=', $request->start);
        // } elseif ($request->filled('end')) {
        //     $query->where('end', '<=', $request->end);
        // }

        if ($request->filled('start') && $request->filled('end')) {
            // Convert start and end dates to 'Y-m-d' format
            $startDate = Carbon::createFromFormat('m-d-Y', $request->start)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('m-d-Y', $request->end)->format('Y-m-d');
            
            // Use formatted startDate and endDate in the query
            $query->whereRaw('DATE(start) >= ? AND DATE(start) <= ?', [$startDate, $endDate]);
        } elseif ($request->filled('start')) {
            // Convert start date to 'Y-m-d' format
            $startDate = Carbon::createFromFormat('m-d-Y', $request->start)->format('Y-m-d');
            
            // Use formatted startDate in the query
            $query->where('start', '>=', $startDate);
        } elseif ($request->filled('end')) {
            // Convert end date to 'Y-m-d' format
            $endDate = Carbon::createFromFormat('m-d-Y', $request->end)->format('Y-m-d');
            
            // Use formatted endDate in the query
            $query->where('start', '<=', $endDate);
        }


        

        // Print the last query before executing
        // $sql = $query->toSql();
        // $bindings = $query->getBindings();

        // dd(vsprintf(str_replace('?', '%s', $sql), $bindings));


        $reviewData = $query->orderBy('start', 'DESC')->get();
        // Modify status values based on conditions
        foreach ($reviewData as $review) {
            if ($request->status == "Upcoming" || $request->status == "Expired") {
                if ($review->status === 'In-Process') {
                    if ($review->start >= $currentDate) {
                        $review->status = 'Upcoming';
                    } elseif ($review->start < $currentDate) {
                        $review->status = 'Expired';
                    }
                }
            }
        }
        $data = [];
        $doctor_sql = "SELECT * FROM dbl_users where user_type = 'doctor' order by first_name ASC";
        $doctor_Data = DB::select($doctor_sql);
        $result['doctors'] = collect($doctor_Data);

        return view('admin_report.list-reports', compact('reviewData', 'result'));
    }

    public function viewReview($id)
    {

        // Access the reviews replies
        $schedule = Schedule::with('reportReviewsReplies')->findOrFail($id);
        // dd($schedule);
        // Pass $schedule and $reviewData to your view or return as needed
        return view('admin_review.view-review', compact('schedule'));
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
            $Category = new Categories();
            $Category->category_name = $validatedData['category_name'];
            $Category->save();

            // Redirect or return success message
            return redirect()->route('admin.categories')->with('success', 'Category added successfully!');
        }
        // Render the add-static-page form view
        return view('admin_category.add-category');
    }

    public function category_status($id = null, $status = null)
    {
        if ($id != null && $id > 0) {
            if ($status == 'inactive') {
                $UpdatedData['status'] = 'inactive';
                $msg = 'Category is inactive';
            } else {
                $UpdatedData['status'] = 'active';
                $msg = 'Category is active.';
            }

            $res = DB::table('blog_categories')
                ->where('id', $id)
                ->update($UpdatedData);

            return redirect('/admin/categories')->with('success', $msg);
        }
    }


    //showing the edit form
    public function editCategory($id)
    {
        $categoryData = Categories::findOrFail($id);

        return view('admin_category.edit-category', compact('categoryData'));
    }

    public function updateCategory(Request $request, $id)
    {


        // Check if the request is a POST
        if ($request->isMethod('post')) {
            // Validate the form data
            $validatedData = $request->validate([
                'category_name' => 'required',
            ]);

            $Categories = Categories::findOrFail($id);
            $Categories->update([
                'category_name' => $request->input('category_name'),

            ]);

            // Redirect or return success message
            return redirect()->route('admin.categories')->with('success', 'Category updated successfully!');
        }

        // // Render the add-static-page form view
        // return view('admin_article.ed-article', compact('categoryData'));
    }

    public function deleteCategory($id)
    {
        $category = Categories::findOrFail($id);
        $category->delete();
        return redirect()->route('admin.categories')->with('success', 'Category deleted successfully');
    }
}
