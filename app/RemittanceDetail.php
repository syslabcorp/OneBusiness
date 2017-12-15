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

    $shiftModel = new \App\Shift;
    $shiftModel->setConnection($company->database_name);

    $shifts = $shiftModel->whereBetween('t_shifts.Shift_ID', [$this->Start_CRR, $this->End_CRR])
                         ->join('t_remitance', 't_remitance.Shift_ID', '=', 't_shifts.Shift_ID')
                         ->where('t_shifts.Branch', '=', $this->Branch);
    
    if($queries['status'] != 'all') {
      $shifts = $shifts->where('t_remitance.Sales_Checked', '=', $queries['status']);
    }

    if($queries['remarks_only'] == 1) {
      $shifts = $shifts->whereNotNull('t_remitance.Notes');
    }

    if($queries['shortage_only'] == 1) {
      $shifts = $shifts->where('t_remitance.TotalSales', '>', 't_remitance.TotalRemit');
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
