<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ReportReviewsReplies extends Model {
    use HasFactory;
    protected $table = 'tbl_report_reviews_replies'; // Ensure this matches your table name

    protected $guarded = [];

    protected $hidden = [
        'password',
    ];



    public $timestamps = false; // Disable timestamps

    // Add any additional methods or attributes here

    public function schedule()
    {
        return $this->belongsTo(Schedule::class, 'appointment_id');
    }

}

