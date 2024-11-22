<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Card extends Model
{
    // Specify the table associated with the model
    protected $table = 'tbl_cards';

    // Specify the primary key of the table
    protected $primaryKey = 'id';

    // Disable timestamps if you are not using them
    public $timestamps = true; // Set to false if not using created_at and updated_at

    // Specify the attributes that are mass assignable
    protected $fillable = [
        'card_number',
        'card_name',
        'expiry_date',
        'cvv',
        'save_card'
    ];

    // Optionally, you can hide sensitive attributes from JSON representation
    protected $hidden = [
        'cvv' // Hide CVV from JSON responses for security reasons
    ];

    // If you need to format dates, you can specify them here
    protected $dates = [
        'expiry_date'
    ];

    // Add any additional custom methods or relationships here
}
