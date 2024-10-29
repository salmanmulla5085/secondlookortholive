<?php

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Stripe\Stripe;
use Stripe\Customer;

function check_payment_mode()
{
    try {
        $check_payment = DB::table('tbl_admin_setting')->where('id', 1)->first();
        return $check_payment ? $check_payment->payment_mode : false;
    } catch (\Exception $e) {
        Log::error('check_payment_mode :: ' . $e->getMessage());
        return false;
    }
}

function get_user($id)
{
    try {
        $result = DB::table('dbl_users')->where('id', $id)->first();
        return $result ? (array) $result : null;
    } catch (\Exception $e) {
        Log::error('get_user :: ' . $e->getMessage());
        return null;
    }
}

function stripe_create_customer($email, $name, $phone)
{
    try {
        $url = "https://api.stripe.com/v1/customers";
        $data = [
            "email" => $email,
            "name" => $name,
            "phone" => $phone
        ];
        return stripe_curl_request($url, $data);
    } catch (\Exception $e) {
        Log::error('stripe_create_customer :: ' . $e->getMessage());
        return null;
    }
}

function stripe_create_card($customer_id, $card_token)
{
    try {
            $existingCards = stripe_list_customer_cards($customer_id);
            $cards = json_decode($existingCards, true);
            
            // print_r($cards);
            

            $api_key = env('SECRET_STRIPE_API_KEY');
            $curl = curl_init();
            curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.stripe.com/v1/tokens/{$card_token}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
            "Authorization: Bearer {$api_key}"
            ],
            ]);
            $response = curl_exec($curl);

            if (curl_errno($curl)) {
                Log::info('Card Check on Stripe Error :-----------------------'. curl_error($curl));                
                return ['error'=>1,'encoded_res'=>curl_error($curl)];
                
            }

            curl_close($curl);

            $newCardDetails = json_decode($response, true);
            // echo "<PRE>";
            // print_r($newCardDetails); 
            // echo "</PRE>";
            // dd();

            if ($newCardDetails['card']['cvc_check'] === 'fail') {                
                return ['error'=>"Incorrect CVV OR Card is Expired",'encoded_res'=>$newCardDetails];
            }
            
            if ($newCardDetails['card']['exp_year'] < now()->year || 
                ($newCardDetails['card']['exp_year'] == now()->year && $newCardDetails['card']['exp_month'] < now()->month)) {                
                return ['error'=>"Expired Card",'encoded_res'=>$newCardDetails];
            }
            

            // Check if there's an error retrieving the new card
            if (isset($newCardDetails['error'])) {
                
                Log::info('newCardDetails[error][message] :-----------------------'.$newCardDetails['error']['message']);                
                return ['card_already_exists'=>null,'card'=>null,'encoded_res'=>$newCardDetails['error']['message']];
            }
            

            $card_found_on_stripe = false;
            $stripe_card = null;

            foreach ($cards['data'] as $card) {
                // if ($card['fingerprint'] == $createdCard['fingerprint']) {
                if ($card['fingerprint'] === $newCardDetails['card']['fingerprint']){
                    $card_found_on_stripe = true;
                    $stripe_card = $card;
                    break;
                }
            }

            if (!$card_found_on_stripe) {

                $encoded_res = null;

                $url = "https://api.stripe.com/v1/customers/{$customer_id}/sources";
                // $data = http_build_query(["source" => $card_token]);
                $data = ["source" => $card_token];
                $stripe_card = stripe_curl_request($url, $data, 'POST');

                // dd($stripe_card);
                    
                $encoded_res = json_encode($stripe_card);                

                if(isset($stripe_card['id']))
                {

                            DB::table('tbl_stripe_cards')->where('stripe_card_id', $stripe_card['id'])->delete();
                            
                            DB::table('tbl_stripe_cards')->insert([
                                'stripe_customer_id' => $customer_id,
                                'stripe_card_id' => $stripe_card['id'],
                                'last4' => $stripe_card['last4'],
                                'brand' => $stripe_card['brand'],
                                'exp_month' => $stripe_card['exp_month'],
                                'exp_year' => $stripe_card['exp_year'],
                                'created_at' => now(),
                            ]);
                            
                            Log::info('Card Created :: Encoded_res :-----------------------'.$encoded_res);                
                            return ['card_already_exists'=>false,'card'=>$stripe_card,'encoded_res'=>$encoded_res];
                            

                }
            }

            if ($card_found_on_stripe) {
                $local_db_record = DB::table('tbl_stripe_cards')->where(['stripe_customer_id' => $customer_id, 'stripe_card_id' => $stripe_card['id']])->count();
                if ($local_db_record == 0) {
                    DB::table('tbl_stripe_cards')->insert([
                        'stripe_customer_id' => $customer_id,
                        'stripe_card_id' => $stripe_card['id'],
                        'last4' => $stripe_card['last4'],
                        'brand' => $stripe_card['brand'],
                        'exp_month' => $stripe_card['exp_month'],
                        'exp_year' => $stripe_card['exp_year'],
                        'created_at' => now(),
                    ]);
                }

                return ['card_already_exists' => true, 'card' => $stripe_card];
            }
        } catch (\Exception $e) {
            Log::error('stripe_create_card :: ' . $e->getMessage());
            return null;
        }
}

function stripe_list_customer_cards($customer_id)
{
    try {
        $url = "https://api.stripe.com/v1/customers/{$customer_id}/sources?object=card";
        $response = stripe_curl_request($url, null, 'GET');
        return is_string($response) ? $response : json_encode($response);
    } catch (\Exception $e) {
        Log::error('stripe_list_customer_cards :: ' . $e->getMessage());
        return null;
    }
}

function stripe_charge_card($amount, $currency, $customer_id, $card_id, $description)
{
    try {
        $url = "https://api.stripe.com/v1/charges";
        $amount = $amount * 100;
        $data = [
            "amount" => $amount,
            "currency" => $currency,
            "customer" => $customer_id,
            "source" => $card_id,
            "description" => $description
        ];
        return stripe_curl_request($url, $data);
    } catch (\Exception $e) {
        Log::error('stripe_charge_card :: ' . $e->getMessage());
        return null;
    }
}

function stripe_curl_request($url, $data = null, $method = 'POST')
{
    try {
        $api_key = env('SECRET_STRIPE_API_KEY');
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $api_key,
            'Content-Type: application/x-www-form-urlencoded'
        ]);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            if ($data) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            }
        } else if ($method === 'GET') {
            if ($data) {
                curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data));
            }
            curl_setopt($ch, CURLOPT_HTTPGET, true);
        } elseif ($method === 'DELETE') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        }

        $response = curl_exec($ch);
        if ($response === false) {
            $error_msg = curl_error($ch);
            Log::error("stripe_curl_request :: " . $error_msg);
        }

        if (curl_errno($ch)) {
            $error_msg = curl_error($ch);
            curl_close($ch);
            Log::error("stripe_curl_request :: " . $error_msg);
        }

        curl_close($ch);

        $decoded_response = json_decode($response, true);
        if (!$decoded_response) {
            Log::error("stripe_curl_request :: Invalid JSON response");
        }

        return $decoded_response;
    } catch (\Exception $e) {
        Log::error('stripe_curl_request :: ' . $e->getMessage());
        return null;
    }
}

function stripe_get_balance()
{
    try {
        $url = "https://api.stripe.com/v1/balance";
        return stripe_curl_request($url);
    } catch (\Exception $e) {
        Log::error('stripe_get_balance :: ' . $e->getMessage());
        return null;
    }
}

function stripe_get_all_transactions()
{
    try {
        $url = "https://api.stripe.com/v1/charges";
        return stripe_curl_request($url);
    } catch (\Exception $e) {
        Log::error('stripe_get_all_transactions :: ' . $e->getMessage());
        return null;
    }
}

function get_or_create_stripe_customer($patient)
{
    try {
        if (isset($patient->id)) {
            $getCustomerFromLocalDB = DB::table('tbl_stripe_customers')->where('patient_id', $patient->id)->first();
            if ($getCustomerFromLocalDB !== null) {
                $is_valid_stripe_customer = is_valid_stripe_customer($getCustomerFromLocalDB->stripe_customer_id);
                if ($is_valid_stripe_customer) return $getCustomerFromLocalDB->stripe_customer_id;

                if (!empty($patient->email_address) && (!empty($patient->first_name) || !empty($patient->last_name)) && !empty($patient->phone_number)) {
                    //delete all customer ids for this patient
                    DB::table('tbl_stripe_customers')->where('patient_id', $patient->id)->delete();
                    $name = !empty($patient->last_name) ? $patient->first_name . ' ' . $patient->last_name : $patient->first_name;
                    $response = stripe_create_customer($patient->email_address, $name, $patient->phone_number);

                    if (isset($response['id'])) {
            
                        
                        DB::table('tbl_stripe_customers')->insert([
                            'patient_id' => $patient->id,
                            'stripe_customer_id' => $response['id'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            } else {
                if (!empty($patient->email_address) && (!empty($patient->first_name) || !empty($patient->last_name)) && !empty($patient->phone_number)) {
                    $name = !empty($patient->last_name) ? $patient->first_name . ' ' . $patient->last_name : $patient->first_name;
                    $response = stripe_create_customer($patient->email_address, $name, $patient->phone_number);

                    if (isset($response['id'])) {
                        DB::table('tbl_stripe_customers')->insert([
                            'patient_id' => $patient->id,
                            'stripe_customer_id' => $response['id'],
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                        return $response['id'];
                    } else {
                        Log::error('Failed to create Stripe customer'.'Failed to create Stripe customer', ['response' => $response]);
                        return null;
                    }
                }
            }
        }
    } catch (\Exception $e) {
        Log::error('get_or_create_stripe_customer :: ' . $e->getMessage());
        return null;
    }
}

function is_valid_stripe_customer($stripeCustomerId)
{
    try {
        $url = "https://api.stripe.com/v1/customers/{$stripeCustomerId}";
        $response = stripe_curl_request($url, null, 'GET');
        if (isset($response['error'])) {
            Log::error("is_valid_stripe_customer :: error " . $response['error']);
        }
        return (!empty($response) && isset($response['id']) && $response['id'] === $stripeCustomerId && !isset($response['deleted']));
    } catch (\Exception $e) {
        Log::error('is_valid_stripe_customer :: ' . $e->getMessage());
        return false;
    }
}

function delete_all_customers_and_cards_from_stripe_and_local()
{
    $api_key = env('SECRET_STRIPE_API_KEY');
    try {
        if (strpos($api_key, 'sk_test_') === 0) {
            $has_more = true;
            $starting_after = null;
            $customers_ids = [];
            while ($has_more) {
                $url = "https://api.stripe.com/v1/customers?limit=100";
                if ($starting_after) $url .= "&starting_after={$starting_after}";
                $customers = stripe_curl_request($url, null, 'GET');
                if (!isset($customers['data']) || !is_array($customers['data'])) {
                    Log::error("Failed to retrieve customers from Stripe.");
                    return false;
                }
                foreach ($customers['data'] as $customer) {
                    $delete_url = "https://api.stripe.com/v1/customers/{$customer['id']}";
                    $delete_response = stripe_curl_request($delete_url, null, 'DELETE');
                    if (isset($delete_response['deleted']) && $delete_response['deleted'] === true) {
                        Log::info("Deleted Stripe customer: " . $customer['id']);
                        $customers_ids['deleted_customers'][] = $customer['id'];
                        DB::table('tbl_stripe_cards')->where('stripe_customer_id', $customer['id'])->delete();
                        DB::table('tbl_stripe_customers')->where('stripe_customer_id', $customer['id'])->delete();
                    } else {
                        Log::error("Failed to delete Stripe customer: " . $customer['id']);
                    }
                }
                $has_more = $customers['has_more'];
                if ($has_more) $starting_after = end($customers['data'])['id'];
            }
        }
    } catch (\Exception $e) {
        Log::error('delete_all_customers_and_cards_from_stripe_and_local :: ' . $e->getMessage());
        return false;
    }
    Log::info('All Stripe customers and local records deleted successfully.');
    return true;
}
