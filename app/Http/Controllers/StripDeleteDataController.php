<?php
namespace App\Http\Controllers;
use App\Models\AvailableScheduleSlots;
use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule as SchedulingSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;

ini_set('display_errors', 1);  // Turn on error display
ini_set('display_startup_errors', 1);  // Display startup errors
error_reporting(E_ALL);  // Report all errors


class StripDeleteDataController extends Controller
{
    /**
     * Display all the static pages when authenticated
     *
     * @param string $page
     * @return \Illuminate\View\View
     */
    public function delete_all_customers_and_cards_from_stripe_and_local()
    {

        // echo $undefinedVariable  // This will cause an undefined variable notice

        try
        { 
            delete_all_customers_and_cards_from_stripe_and_local();
        }
        catch(\Exception $e)
            {
                Log::info('esception.'.$e->getMessage());
                var_dump($e->getMessage());
                
            }

    }

}
