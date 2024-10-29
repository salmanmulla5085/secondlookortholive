<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;


class PaymentController extends Controller
{
    public function createCustomer(Request $request)
    {
        $user = auth()->user();
        $customer = stripe_create_customer($user->email_address, $user->first_name . ' ' . $user->last_name, $user->phone_number);

        if (isset($customer['id'])) {
            // Store Stripe customer ID in database if needed
        }

        return response()->json($customer);
    }

    public function createCard(Request $request)
    {
        $customer_id = $request->input('customer_id');
        $card_token = $request->input('card_token');  // You need to get card token from frontend

        $card = stripe_create_card($customer_id, $card_token);
        return response()->json($card);
    }

    public function chargeCard(Request $request)
    {
        $amount = $request->input('amount');
        $currency = 'usd';  // Set your currency here
        $customer_id = $request->input('customer_id');
        $card_id = $request->input('card_id');
        $description = $request->input('description');

        $charge = stripe_charge_card($amount, $currency, $customer_id, $card_id, $description);

        if (isset($charge['id'])) {
            // Store transaction data in the tbl_payments table
        }

        return response()->json($charge);
    }

    public function getBalance()
    {
        $balance = stripe_get_balance();
        return response()->json($balance);
    }

    public function getTransactions()
    {
        $transactions = stripe_get_all_transactions();
        return response()->json($transactions);
    }
}
