<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Joints extends Model
{
    use HasFactory;
    protected $table = 'tbl_joints';
    protected $fillable = ['question', 'description',];
    public $timestamps = false; // Disable timestamps
}
