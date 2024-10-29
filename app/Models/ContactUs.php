<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class ContactUs extends Model {
    use HasFactory;
    protected $table = 'tbl_contact_us'; // Ensure this matches your table name

    protected $guarded = [];
    public $timestamps = false; // Disable timestamps

}

