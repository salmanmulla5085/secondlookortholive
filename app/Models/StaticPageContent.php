<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticPageContent extends Model
{
    use HasFactory;

    protected $table = 'static_page_contents';

    // protected $fillable = [
    //     'static_page_id', 
    //     'section_name', 
    //     'section_heading1', 
    //     'section_heading2', 
    //     'section_short_desc1', 
    //     'section_short_desc2', 
    //     'section_long_desc1', 
    //     'section_long_desc2', 
    //     'section_image1', 
    //     'section_image2', 
    //     'section_image3'
    // ];
    protected $guarded = [];

    public function staticPage()
    {
        return $this->belongsTo(StaticPage::class, 'static_page_id');
    }

    
}
