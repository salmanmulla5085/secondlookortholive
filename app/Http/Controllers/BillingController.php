<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\StaticPageContent;
use App\Models\Faq;
use App\Models\Payment;
use App\Models\Plan;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;

class BillingController extends Controller
{
    // Show the list of Plan
    public function listbillings(Request $request)
    {
        


        // Start the query
        $paymentData = DB::table('tbl_payments')
            ->join('dbl_users', 'tbl_payments.patient_id', '=', 'dbl_users.id')
            ->leftJoin('tbl_plans', 'tbl_payments.plan_id', '=', 'tbl_plans.id')
            ->leftJoin('tbl_appointments_booked', 'tbl_payments.id', '=', 'tbl_appointments_booked.payment_id')
            ->leftJoin('dbl_users as doctor', 'tbl_appointments_booked.doctor_id', '=', 'doctor.id')
           
            ->select(
                'tbl_payments.*',
                'dbl_users.first_name',
                'dbl_users.last_name',
                'tbl_plans.plan_type',
                'doctor.id as doctor_id',
                'doctor.first_name as doctor_first_name',
                'doctor.last_name as doctor_last_name'
            )
                ->whereNotNull('doctor.first_name')
                ->whereNotNull('doctor.last_name')
                ->where('doctor.first_name', '!=', '')
                ->where('doctor.last_name', '!=', '');

            if ($request->filled('doctor_id')) {
                $paymentData->where('tbl_appointments_booked.doctor_id', $request->doctor_id);
            }

        


        // for changing date format new code added  added by salman
        if ($request->filled('start') && $request->filled('end')) {
            // Convert start and end dates to 'Y-m-d' format
            $startDate = Carbon::createFromFormat('m-d-Y', $request->start)->format('Y-m-d');
            $endDate = Carbon::createFromFormat('m-d-Y', $request->end)->format('Y-m-d');
            // Use formatted startDate and endDate in the query
            $paymentData->whereRaw('DATE(tbl_payments.txn_time) >= ? AND DATE(tbl_payments.txn_time) <= ?', [$startDate, $endDate]);
        } elseif ($request->filled('start')) {
            // Convert start date to 'Y-m-d' format
            $startDate = Carbon::createFromFormat('m-d-Y', $request->start)->format('Y-m-d');
            $paymentData->where('tbl_payments.txn_time', '>=', $startDate);
        } elseif ($request->filled('end')) {
            // Convert end date to 'Y-m-d' format
            $endDate = Carbon::createFromFormat('m-d-Y', $request->end)->format('Y-m-d');           
            $paymentData->where('tbl_payments.txn_time', '<=', $request->end);
        }
        // Order and get the results
        $paymentData = $paymentData->orderBy('tbl_payments.id', 'DESC')->get();

        $data = [];
        $doctor_sql = "SELECT * FROM dbl_users where user_type = 'doctor' order by first_name ASC";
        $doctor_Data = DB::select($doctor_sql);
        $result['doctors'] = collect($doctor_Data);
        return view('admin_payment.list-payment', compact('paymentData','result'));
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
    public function viewBilling($id)
    {

        $payment = DB::table('tbl_payments')
            ->join('dbl_users', 'tbl_payments.patient_id', '=', 'dbl_users.id')
            ->leftJoin('tbl_plans', 'tbl_payments.plan_id', '=', 'tbl_plans.id')
            ->select(
                'tbl_payments.*',
                'dbl_users.first_name',
                'dbl_users.last_name',
                'tbl_plans.plan_type'
            )
            ->where('tbl_payments.id', $id)
            ->first();

        return view('admin_payment.view-billing', compact('payment'));
    }

    public function updatePlan(Request $request, $id)
    {


        // Check if the request is a POST
        if ($request->isMethod('post')) {
            // Validate the form data
            $validatedData = $request->validate([
                'plan_type' => 'required',
                'plan_duration' => 'required',
                'plan_amount' => 'required',


            ]);

            $Plan = Plan::findOrFail($id);
            $Plan->update([
                'plan_type' => $request->input('plan_type'),
                'plan_duration' => $request->input('plan_duration'),
                'plan_amount' => $request->input('plan_amount'),
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
