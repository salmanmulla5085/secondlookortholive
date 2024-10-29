<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsCity extends Model
{
    // Specify the table name if it's different from the default plural form
    protected $table = 'us_cities';

    // Specify the primary key if it's different from 'id'
    protected $primaryKey = 'ID';

    // Disable timestamps if your table does not have `created_at` and `updated_at` columns
    public $timestamps = false;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'ID_STATE',
        'CITY',
        'COUNTY',
        'LATITUDE',
        'LONGITUDE',
    ];

    // Optionally, you can define relationships if needed
    // e.g., Belongs-to relationship with UsState
    public function state()
    {
        return $this->belongsTo(UsState::class, 'ID_STATE');
    }
    
    public static function getCityIdByName($cityName)
    {
        // Using Eloquent to retrieve the state ID by the state name
        $city = self::where('CITY', $cityName)->first();
    
        // If the state is found, return the ID, otherwise return null
        return $city ? $city->ID : null;
    }
}
