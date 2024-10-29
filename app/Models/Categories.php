<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Categories extends Model {
    use HasFactory;
    protected $table = 'blog_categories'; // Ensure this matches your table name

    // protected $fillable = [
    //     'first_name', 'last_name', 'email_address', 'phone_number', 'password','User_type','city','state','timezone'
    // ];

    // if we dont want to add every column in above then, use guareded. it will allow all entry in all columns.
    protected $guarded = [];

    // protected $hidden = [
    //     'password',
    // ];

    public $timestamps = false; // Disable timestamps

    // Add any additional methods or attributes here

    public function articles()
    {
        return $this->hasMany(Article::class, 'category_id');
    }
}

