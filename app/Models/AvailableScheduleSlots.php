<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AvailableScheduleSlots extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_available_schedule_slots'; 

    // protected $fillable = [
    //     'doctor_id',
    //     'start_datetime', // Add this line
    //     'end_datetime'
    // ];
    
    protected $guarded = [];
}
