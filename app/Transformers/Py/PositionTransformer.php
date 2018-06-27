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
      switch ($item->for_qc) {
        case 0:
          $status = "Active";
          break;
        case 0:
          $status = "For Quit Claim";
          break;
        case 0:
          $status = "For printing";
          break;
        case 0:
          $status = "“Released”";
          break;
        case 0:
          $status = "Transferred/Promoted";
          break;
        default:
          $status = "";
          break;
      }
      return [
          'Branch' => $item->branch()->first()->ShortName,
          'StartDate' => $item->StartDate ? $item->StartDate->format('d/m/Y') : "",
          'SeparationDate' => $item->EndDate ? $item->EndDate->format('d/m/Y') : "",
          'Position' => $position,
          'Status' => $status
      ];
    }
}
