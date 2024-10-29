<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StaticPage extends Model
{
    // Define the table associated with the model
    protected $table = 'static_pages';

    // Disable timestamps if not using created_at and updated_at
    public $timestamps = true;

    // Specify the fields that are mass assignable
    protected $fillable = [
        'page_name',
        'page_title',
        'meta_keyword',
        'meta_desc',
    ];

    
    
}
