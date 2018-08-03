<?php
namespace App\Transformers\Py;

use League\Fractal;
use App\Models\Py\EmpHistory;
use App\Models\Py\EmpRate;
use App\Corporation;
use App\Branch;
use DB;

class PositionTransformer extends Fractal\TransformerAbstract
{
    private $database_name;

    public function transform(EmpHistory $item)
    {
      $position = "";
      
      if($item->rates()->first())
      {
        
        if ($item->rates()->first()->mstr)
        {
            $position = $item->rates()->first()->mstr()->first()->position;
        }
      }
      $branch = $item->branch ? $item->branch()->first()->ShortName : "";

      $statuses = [
        'Active', 'For Quit Claim', 'For Printing', 'Printed', 'Released',
        'Transferred/Promoted'
      ];

      return [
          'Branch' => $branch,
          'StartDate' => $item->StartDate ? $item->StartDate->format('d/m/Y') : "",
          'SeparationDate' => $item->EndDate ? $item->EndDate->format('d/m/Y') : "",
          'Position' => $position,
          'Status' => $statuses[$item->for_qc]
      ];
    }
}
