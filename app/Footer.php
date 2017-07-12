<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $table = "t_stubfoot";
    protected $primaryKey = "Foot_ID";

    protected $fillable = [
        'Foot_Text', 'sort', "Branch"
    ];
}
