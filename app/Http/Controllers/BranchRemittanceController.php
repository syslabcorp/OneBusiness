<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\TRemittance;

class BranchRemittanceController extends Controller
{
  public function index(Request $request)
  {
    return view('t_remittances.index', [
      'remittances' => TRemittance::all()
    ]);
  }

  public function show($id)
  {
    return view('t_remittances.show', [
      'remittance' => TRemittance::where('txn_id', '=', $id)
    ]);
  }

}
