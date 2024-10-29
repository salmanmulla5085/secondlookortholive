<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Comment extends Model {
    use HasFactory;
    protected $table = 'blog_comments'; // Ensure this matches your table name

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


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function article()
    {
        return $this->belongsTo(Article::class);
    }
    
}

