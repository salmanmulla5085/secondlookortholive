<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;
    
    protected $table = 'blog_comments'; 

    protected $guarded = [];

    //article_id column in blog_comments table

     public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    
}
