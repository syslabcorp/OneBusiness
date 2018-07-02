<?php
namespace App\Transformers\Py;

use League\Fractal;
use App\Models\Py\EmpHistory;
use App\Models\Py\EmpRate;
use App\User;
use App\Corporation;
use App\Branch;
use DB;

class EmpTransformer extends Fractal\TransformerAbstract
{
    private $database_name;

    public function __construct($database_name) {
      $this->database_name = $database_name;
    }

    public function transform(User $item)
    {
      $empHist = $item->empHistories($this->database_name);

      $branch = "";
      $datehired = "";
      $base_rate = 0;
      $code = "";
      $department = "";
      $benf = "";
      $exp = "";
      $deduct = "";
      if ($empHist->first())
      {
        $datehired = $empHist->first()->StartDate ? $empHist->first()->StartDate->format('d/m/Y') : "";
        if ($empHist->pluck('Branch')->first())
        {
          // $branch = Branch::whereIn('Branch', $empHist->pluck('Branch'))->toSql();

          $branchs = Branch::whereIn('Branch', $empHist->pluck('Branch'))->pluck('ShortName')->toArray();
          if(sizeof($branchs) > 0)
          {
            $branch = implode( ' ', $branchs);
          }
        }

        $mstrs = DB::connection($this->database_name)
            ->table('py_emp_rate')->join('py_emp_hist', 'py_emp_hist.txn_id', '=', 'py_emp_rate.txn_id')
            ->join('wage_tmpl8_mstr', 'py_emp_rate.wage_tmpl8_id', '=', 'wage_tmpl8_mstr.wage_tmpl8_id')
            ->whereIn('py_emp_hist.txn_id', $empHist->get()->pluck('txn_id'));
        if($mstrs->first())
        {
          $base_rate = $mstrs->first()->base_rate;
          $code = $mstrs->first()->code;
          $department = DB::connection($this->database_name)->table('t_depts')->where('dept_id', $mstrs->first()->dept_id)->select('department')->department;
          if ($mstrs->details) {
            $exp_ID = $mstrs->details()->exp_mstrs()->get()->pluck('ID');
            $benf_ID = $mstrs->details()->benf_mstrs()->get()->pluck('ID');
            $deduct_ID = $mstrs->details()->deduct_mstrs()->get()->pluck('ID');

            $exp_list = DB::connection($this->database_name)->table('py_exp_mstrs')
                          ->whereIn('ID_exp', $exp_ID);
            if(sizeof($emp_list) > 0)
            {
              $exp = implode( ' ', $emp_list);
            }

            $benf_list = DB::connection($this->database_name)->table('py_benf_mstrs')
                          ->whereIn('ID_benf', $exp_ID);
            if(sizeof($benf_list) > 0)
            {
              $benf = implode( ' ', $benf_list);
            }

            $deduct_list = DB::connection($this->database_name)->table('py_deduct_mstrs')
                          ->whereIn('ID_exp', $deduct_ID);
            if(sizeof($deduct_list) > 0)
            {
              $deduct = implode( ' ', $deduct_list);
            }

          }
        }
      }

      return [
          'UserID' => (int) $item->UserID,
          'UserName' => $item->UserName,
          'Address' => (int) $item->Address,
          'BDay' => $item->Bday ? $item->Bday->format('d/m/Y') : "",
          'Age' => $item->Bday ? date_diff($item->Bday, date_create(date("Y-m-d")))->format('%y') : "",
          'Sex' => $item->Sex == "Male" ? 'M' : 'F',
          'Branch' => $branch,
          'Department' => $department,
          'Position' => $item->Position,
          'DateHired' => $datehired,
          'BaseSalary' => $base_rate,
          'PayCode' => $code,
          'SSS' => $item->SSS,
          'PHCI' => $item->PHIC,
          'HDMF' => $item->Pagibig,
          'Account' => $item->acct_no,
          'Type' =>$item->split_type,
          'Active' =>$item->Active,
          'Benf' => $benf,
          'Exp' => $exp,
          'Deduct' => $deduct,
      ];
    }
}
