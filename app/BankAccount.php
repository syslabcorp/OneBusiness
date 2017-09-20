<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $table = 'cv_bank_acct';
    protected $primaryKey = 'bank_acct_id';
    protected $fillable = [
      'branch', 'acct_no', 'default_acct'
    ];
    public $timestamps = false;

    //relations

    /**
     * Get the bank that owns the account.
     */
    public function banks()
    {
        return $this->belongsTo('App\Bank', 'bank_id');
    }
}
