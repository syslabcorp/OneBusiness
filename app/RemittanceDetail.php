<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Branch;

class RemittanceDetail extends Model
{
  public $timestamps = false;
  protected $table = "remittance_details";
  protected $primaryKey = "ID";

  protected $fillable = [
    'Branch', 'Start_CRR', 'End_CRR', 'Collection', 'Group', 'RemittanceCollectionID'
  ];

}
