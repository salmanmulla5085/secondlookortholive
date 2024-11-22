<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSetting extends Model
{
    use HasFactory;
    protected $table = 'tbl_admin_setting';
    protected $guarded = [];
    public $timestamps = false; // Disable timestamps
}
