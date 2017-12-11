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

  public function shifts($corpID) {
    $company = \App\Company::findOrFail($corpID);

    $shiftModel = new \App\Shift;
    $shiftModel->setConnection($company->database_name);

    $shifts = $shiftModel->whereBetween('Shift_ID', [$this->Start_CRR, $this->End_CRR])
                         ->where('Branch', '=', $this->Branch)
                         ->get();

    $result = [];
    foreach($shifts as $key => $shift) {
      if(!$shift->branch) {
        continue;
      }
      $result[$shift->branch->Branch][$shift->ShiftDate->format('D,M-d-Y')][] = $shift;
    }
    return $result;
  }

}
