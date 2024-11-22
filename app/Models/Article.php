<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
    
    protected $table = 'blog_articles'; 

    protected $guarded = [];


    // category_id this col from the blog_article table

     public function category()
    {
        return $this->belongsTo(Categories::class, 'category_id');
    }  


    public function comments()
    {
        return $this->hasMany(Comments::class, 'article_id');
    } 
    
    public function users()
    {
        return $this->belongsTo(User::class);
    }
    
}
