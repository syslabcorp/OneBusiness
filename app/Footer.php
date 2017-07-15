<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    public $timestamps = false;
    protected $table = "t_stubfoot";
    protected $primaryKey = "Foot_ID";

    protected $fillable = [
        'Foot_Text', 'sort', "Branch"
    ];
}
