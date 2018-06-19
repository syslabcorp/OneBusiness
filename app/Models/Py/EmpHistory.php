<?php

namespace App\Models\Py;

use Illuminate\Database\Eloquent\Model;

class EmpHistory extends Model {
  public $timestamps = false;
  protected $table = "py_emp_hist";
  protected $primaryKey = "txn_id";

  protected $fillable = [
    'txn_id', 'Branch', 'EmpID', 'StartDate', 'EndDate', 'Last13_Date'
  ];

  protected $dates = [
      'StartDate', 'EndDate', 'Last13_Date'
  ];

  public function branch()
  {
    return $this->belongsTo(\App\Branch::class, 'Branch', 'Branch');
  }

  public function user()
  {
    return $this->belongsTo(\App\User::class, 'UserID', 'EmpID');
  }

  public function rates()
  {
    return $this->hasMany(EmpRate::class, 'txn_id', 'txn_id');
  }
}
