<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UsState extends Model
{
    // Specify the table name if it's different from the default plural form
    protected $table = 'us_states';

    // Specify the primary key if it's different from 'id'
    protected $primaryKey = 'ID';

    // Disable timestamps if your table does not have `created_at` and `updated_at` columns
    public $timestamps = false;

    // Define the fillable attributes for mass assignment
    protected $fillable = [
        'STATE_CODE',
        'STATE_NAME',
    ];

    // Optionally, you can define relationships if needed
    // e.g., One-to-Many relationship with UsCity
    public function cities()
    {
        return $this->hasMany(UsCity::class, 'ID_STATE');
    }
    
    public static function getStateIdByName($stateName)
    {
        // Using Eloquent to retrieve the state ID by the state name
        $state = self::where('STATE_NAME', $stateName)->first();
    
        // If the state is found, return the ID, otherwise return null
        return $state ? $state->ID : null;
    }
}
