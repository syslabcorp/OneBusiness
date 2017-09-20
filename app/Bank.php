<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = 'cv_banks';
    protected $primaryKey = 'bank_id';
    protected $fillable = [
        'bank_code', 'description'
    ];
    public $timestamps = false;

    //relations

    /**
     * gets the accounts in that bank
     */
    public function bankAccounts(){
        return $this->hasMany('App\BankAccount', 'bank_id', 'bank_id');
    }
}
