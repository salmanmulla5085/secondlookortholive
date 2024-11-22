<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $fillable = ['doctor_id', 'patient_id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function doctor()
    {
        return $this->belongsTo(dbl_users::class, 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(dbl_users::class, 'patient_id');
    }
}
