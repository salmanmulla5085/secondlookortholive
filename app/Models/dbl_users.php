<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class dbl_users extends Authenticatable {
    use HasFactory, HasApiTokens, Notifiable;

    protected $table = 'dbl_users'; // Ensure this matches your table name

    // protected $fillable = [
    //     'first_name', 'last_name', 'email_address', 'phone_number', 'password','User_type','city','state','timezone'
    // ];

    // if we dont want to add every column in above then, use guareded. it will allow all entry in all columns.
    protected $guarded = [];

    protected $hidden = [
        'password',
    ];

    public $timestamps = false; // Disable timestamps

    public function availableScheduleSlots()
    {
        return $this->hasMany(AvailableScheduleSlots::class, 'doctor_id');
    }

    // Add any additional methods or attributes here
}

