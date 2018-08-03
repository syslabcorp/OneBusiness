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
        $benfItems = [];
        $expItems = [];
        $deductItems = [];

        if($item->mstr) {
            $exp_ID =  $item->mstr->details()->where('pay_db', 'exp_mstr')->pluck('ID');
            $benf_ID =  $item->mstr->details()->where('pay_db', 'benf_mstr')->pluck('ID');
            $deduct_ID = $item->mstr->details()->where('pay_db', 'deduct_mstr')->pluck('ID');

            $expItems = DB::connection($this->database_name)->table('py_exp_mstr')
                            ->whereIn('ID_exp', $exp_ID)->pluck('description');

            $benfItems = DB::connection($this->database_name)->table('py_benf_mstr')
                            ->whereIn('ID_benf', $exp_ID)->pluck('description');
            $deductItems = DB::connection($this->database_name)->table('py_deduct_mstr')
                            ->whereIn('ID_deduct', $deduct_ID)->pluck('description');
        }

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
            'BaseRate' => $item->mstr ? $item->mstr->base_rate : "",
            'PayCode' => $item->mstr ? $item->mstr->code : "",
            'PayBasic' => $basic,
            'exps' => $expItems,
            'benfs' => $benfItems,
            'deducts' => $deductItems
        ];
    }
}
