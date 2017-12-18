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

  public function shifts($corpID, $queries = []) {
    $company = \App\Company::findOrFail($corpID);

    if($company->corp_type == 'ICAFE') {
      $shiftModel = new \App\Shift;
      $remittanceModel = new \App\Remittance;
    }else {
      $shiftModel = new \App\KShift;
      $remittanceModel = new \App\KRemittance;
    }
    
    $shiftModel->setConnection($company->database_name);

    $shifts = $shiftModel->whereBetween("{$shiftModel->getTable()}.Shift_ID", [$this->Start_CRR, $this->End_CRR])
                         ->join("{$remittanceModel->getTable()}", "{$remittanceModel->getTable()}.Shift_ID", '=', "{$shiftModel->getTable()}.Shift_ID")
                         ->where("{$shiftModel->getTable()}.Branch", '=', $this->Branch);
    
    if($queries['status'] != 'all') {
      $shifts = $shifts->where("{$remittanceModel->getTable()}.Sales_Checked", '=', $queries['status']);
    }

    if($queries['remarks_only'] == 1 && $company->corp_type == 'ICAFE') {
      $shifts = $shifts->whereNotNull("{$remittanceModel->getTable()}.Notes")
                       ->where("{$remittanceModel->getTable()}.Notes", "<>", "");
    }

    if($queries['shortage_only'] == 1) {
      $shifts = $shifts->whereRaw("{$remittanceModel->getTable()}.TotalSales > {$remittanceModel->getTable()}.TotalRemit");
    }

    $shifts = $shifts->get();

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
