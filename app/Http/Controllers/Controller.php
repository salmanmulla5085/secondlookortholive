<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session; // Import Session facade
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function setSessionData($key, $value)
    {
        Session::put($key, $value);
    }

    protected function getSessionData($key)
    {
        return Session::get($key);
    }    

    protected function getTimezoneByState($stateName)
    {
        // Dummy timezone logic; replace with actual logic        
        $stateTimezones = [
            'Alabama' => 'America/Chicago',
            'Alaska' => 'America/Anchorage',
            'Arizona' => 'America/Phoenix',
            'Arkansas' => 'America/Chicago',
            'California' => 'America/Los_Angeles',
            'Colorado' => 'America/Denver',
            'Connecticut' => 'America/New_York',
            'Delaware' => 'America/New_York',
            'Florida' => 'America/New_York', // Except the panhandle which is Central
            'Georgia' => 'America/New_York',
            'Hawaii' => 'Pacific/Honolulu',
            'Idaho' => 'America/Boise',
            'Illinois' => 'America/Chicago',
            'Indiana' => 'America/Indiana/Indianapolis',
            'Iowa' => 'America/Chicago',
            'Kansas' => 'America/Chicago',
            'Kentucky' => 'America/New_York',
            'Louisiana' => 'America/Chicago',
            'Maine' => 'America/New_York',
            'Maryland' => 'America/New_York',
            'Massachusetts' => 'America/New_York',
            'Michigan' => 'America/Detroit',
            'Minnesota' => 'America/Chicago',
            'Mississippi' => 'America/Chicago',
            'Missouri' => 'America/Chicago',
            'Montana' => 'America/Denver',
            'Nebraska' => 'America/Chicago',
            'Nevada' => 'America/Los_Angeles',
            'New Hampshire' => 'America/New_York',
            'New Jersey' => 'America/New_York',
            'New Mexico' => 'America/Denver',
            'New York' => 'America/New_York',
            'North Carolina' => 'America/New_York',
            'North Dakota' => 'America/Chicago',
            'Ohio' => 'America/New_York',
            'Oklahoma' => 'America/Chicago',
            'Oregon' => 'America/Los_Angeles', // Except the eastern part which is Mountain
            'Pennsylvania' => 'America/New_York',
            'Rhode Island' => 'America/New_York',
            'South Carolina' => 'America/New_York',
            'South Dakota' => 'America/Chicago',
            'Tennessee' => 'America/Chicago', // Except Eastern Tennessee which is New York timezone
            'Texas' => 'America/Chicago', // Except El Paso which is Mountain
            'Utah' => 'America/Denver',
            'Vermont' => 'America/New_York',
            'Virginia' => 'America/New_York',
            'Washington' => 'America/Los_Angeles',
            'West Virginia' => 'America/New_York',
            'Wisconsin' => 'America/Chicago',
            'Wyoming' => 'America/Denver',
        ];
        

        return $stateTimezones[$stateName] ?? 'UTC';
    }
    

}
