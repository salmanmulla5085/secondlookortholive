<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_appointments_booked'; 

    protected $guarded = [];

    public function doctor()
    {
        return $this->belongsTo(dbl_users::class, 'doctor_id');
    }
    public function patient()
    {
        return $this->belongsTo(dbl_users::class, 'patient_id');
    }
    public function payment()
    {
        return $this->belongsTo(Payment::class,'payment_id');
    }

    // Define relationship with UsState model
    public function state()
    {
        return $this->belongsTo(UsState::class, 'state', 'ID');
    }

    // Define relationship with UsCity model
    public function city()
    {
        return $this->belongsTo(UsCity::class, 'city', 'ID');
    }

      // Accessor for appointment_start
      public function getAppointmentStartAttribute()
      {
          return $this->attributes['start'];
      }
  
      // Accessor for appointment_end
      public function getAppointmentEndAttribute()
      {
          return $this->attributes['end'];
      }


    public function reportReviewsReplies()
    {
        return $this->hasMany(ReportReviewsReplies::class, 'appointment_id');
    }    
    // Define relationship with UsState model
    public function user()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
    
}
