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

    public function transform(User $item) {
      $empHist = $item->empHistories($this->database_name);

        $activeColumn =  $item->Active ? 'Active' : 'Inactive';
      $branch = "";
      $datehired = "";
      $base_rate = 0;
      $code = "";
      $department = "";
      $benfItems = [];
      $expItems = [];
      $deductItems = [];

        if ($item->SQ_Branch) {
            $activeColumn = $item->SQ_Active ? 'All' : 'Inactive';
        }

      $branchs_list = Branch::where('Branch', $item->Branch)->orWhere('Branch', $item->SQ_Branch)->get();
      if ($branchs_list->first())
      {
        $branch = $branchs_list->first()->ShortName;
      }

      $branch = $item->level_id > 9 ? 'NON-BRANCH' : $branch;

      // dd(Branch::where('Branch', $item->Branch)->orWhere('Branch', $item->SQ_Branch)->get());
      if ($empHist->first())
      {
        // $datehired = $empHist->first()->StartDate ? $empHist->first()->StartDate->format('d/m/Y') : "";
        // if ($empHist->pluck('Branch')->first())
        // {
        //   // $branch = Branch::whereIn('Branch', $empHist->pluck('Branch'))->toSql();

        //   $branchs = Branch::whereIn('Branch', $empHist->pluck('Branch'))->pluck('ShortName')->toArray();
        //   if(sizeof($branchs) > 0)
        //   {
        //     $branch = implode( ' ', $branchs);
        //   }
        // }

        $template = DB::connection($this->database_name)
            ->table('py_emp_rate')->join('py_emp_hist', 'py_emp_hist.txn_id', '=', 'py_emp_rate.txn_id')
            ->join('wage_tmpl8_mstr', 'py_emp_rate.wage_tmpl8_id', '=', 'wage_tmpl8_mstr.wage_tmpl8_id')
            ->whereIn('py_emp_hist.txn_id', $empHist->get()->pluck('txn_id'))
            ->first();

        $templateModel = new \App\Models\WageTmpl8\Mstr;
        $templateModel->setConnection($this->database_name);

        if($template) {
            $template = $templateModel->find($template->wage_tmpl8_id);
            $base_rate = $template->base_rate;
            $code = $template->code;
        
          $department = DB::connection($this->database_name)->table('t_depts')->where('dept_id',  $template->dept_id)->first();
          
          $department = $department ? $department->department : '';

          $exp_ID =  $template->details()->where('pay_db', 'exp_mstr')->pluck('ID');
          $benf_ID =  $template->details()->where('pay_db', 'benf_mstr')->pluck('ID');
          $deduct_ID = $template->details()->where('pay_db', 'deduct_mstr')->pluck('ID');

          $expItems = DB::connection($this->database_name)->table('py_exp_mstr')
                        ->whereIn('ID_exp', $exp_ID)->pluck('description');

          $benfItems = DB::connection($this->database_name)->table('py_benf_mstr')
                        ->whereIn('ID_benf', $exp_ID)->pluck('description');
          $deductItems = DB::connection($this->database_name)->table('py_deduct_mstr')
                        ->whereIn('ID_deduct', $deduct_ID)->pluck('description');
        }
      }

      return [
          'UserID' => (int) $item->UserID,
          'UserName' => $item->UserName,
          'Address' => $item->Address,
          'BDay' => $item->Bday ? $item->Bday->format('d/m/Y') : "",
          'Age' => $item->Bday ? date_diff($item->Bday, date_create(date("Y-m-d")))->format('%y') : "",
          'Sex' => $item->Sex == "Male" ? 'M' : 'F',
          'Branch' => $branch,
          'Department' => $department,
          'Position' => $item->Position,
          'DateHired' => $item->Hired ? (new \DateTime($item->Hired))->format('d/m/Y') : '',
          'BaseSalary' => $base_rate,
          'PayCode' => $code,
          'SSS' => $item->SSS,
          'PHCI' => $item->PHIC,
          'HDMF' => $item->Pagibig,
          'Account' => $item->acct_no,
          'Type' =>$item->split_type,
          'active' => $activeColumn,
          'Benf' => $benfItems,
          'Exp' => $expItems,
          'Deduct' => $deductItems,
      ];
    }
}
