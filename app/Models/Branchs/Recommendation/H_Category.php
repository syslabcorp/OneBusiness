<?php

namespace App\Models\Branchs\Recommendation;

use Illuminate\Database\Eloquent\Model;

class H_Category extends Model
{
    //
    protected $primaryKey = 'doc_no';
    protected $connection = '';
    protected $table = 'h_category';
    public $timestamps = false;
    
    protected $fillable = [
        'description',
        'series',
        'Deleted'
    ];
}
