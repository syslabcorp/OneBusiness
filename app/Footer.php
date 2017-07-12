<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $table = "t_stubfoot";

    protected $fillable = [
        'content', 'sort'
    ];
}
