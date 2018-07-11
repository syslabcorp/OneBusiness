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
    private $user;

    public function __construct($database_name, $user) {
      $this->database_name = $database_name;
      $this->user = $user;
    }

    public function transform(EmpRate $item)
    {
      switch ($this->user->PayBasis) {
        case 3:
          $basic = "Hourly";
          break;
        case 4:
          $basic = "Monthly";
          break;
        default:
          $basic = "";
          break;
      }
      return [
          'EffectiveDate' => $item->effect_date,
          'BaseRate' => $item->mstrs ? $item->mstrs()->first()->base_rate : "",
          'PayCode' => $item->mstrs ? $item->mstrs()->first()->code : "",
          'PayBasic' => $basic,
      ];
    }
}
