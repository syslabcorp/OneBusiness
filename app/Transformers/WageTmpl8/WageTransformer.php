<?php
namespace App\Transformers\WageTmpl8;

use League\Fractal;
use App\Models\Py\EmpHistory;
use App\Models\Py\EmpRate;
use App\User;
use App\Corporation;
use App\Branch;
use DB;

class WageTransformer extends Fractal\TransformerAbstract
{
    private $database_name;

    public function __construct($database_name) {
      $this->database_name = $database_name;
    }

    public function transform(EmpRate $item)
    {
      $empHist = $item->empHistories($this->database_name);

      return [
          'EffectiveDate' => (int) $item->UserID,
          'BaseRate' => $item->UserName,
          'PayCode' => (int) $item->Address,
          'PayBasic' => $item->Bday ? $item->Bday->format('d/m/Y') : "",
      ];
    }
}
