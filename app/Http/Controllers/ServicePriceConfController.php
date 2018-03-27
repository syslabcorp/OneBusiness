<?php

namespace App\Http\Controllers;

use App\Corporation;
use Illuminate\Http\Request;

class ServicePriceConfController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      if(!\Auth::user()->checkAccessById(37, "V")) {
        \Session::flash('error', "You don't have permission");
        return redirect("/home");
      }

      //get services list
      $corporations = Corporation::orderBy('corp_name', 'ASC')->get(['corp_id', 'corp_name', 'database_name']);

      return view('services-price-conf.index', compact(['corporations']));
    }

    public function create (Request $request) {
      if(!\Auth::user()->checkAccessById(37, "V")) {
        \Session::flash('error', "You don't have permission");
        return redirect("/home");
      }

      $company = Corporation::findOrFail($request->corpID);
      $itemModel = new \App\SrvItemCfg;
      $itemModel->setConnection($company->database_name);

      $services = \App\Service::whereIn('Serv_ID', explode(',', $request->service_ids))->orderBy('Serv_Code', 'ASC')->get();
      $branchs = \App\Branch::whereIn('Branch', explode(',', $request->branch_ids))->get();

      return view('services-price-conf.new', [
        'services' => $services,
        'branchs' => $branchs,
        'itemModel' => $itemModel,
        'corpID' => $request->corpID,
        'branch_ids' => $request->branch_ids,
        'service_ids' => $request->service_ids
      ]);
    }

    public function store(Request $request) {
      $company = Corporation::findOrFail($request->corpID);
      $itemModel = new \App\SrvItemCfg;
      $itemModel->setConnection($company->database_name);

      foreach($request->items as $Serv_ID => $items) {
        foreach($items as $Branch => $item) {
          $status = $itemModel->updateOrCreate([
            'Serv_ID' => $Serv_ID,
            'Branch' => $Branch
          ], $item);
        }
      }

      \Session::flash('success', "Settings successfully saved");

      return redirect(route('services-price-conf.create', [
        'corpID' => $request->corpID,
        'branch_ids' => $request->branch_ids,
        'service_ids' => $request->service_ids
      ]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
      $branches = \App\Branch::whereIn('Branch', $request->branch_ids)->get();
      $company = Corporation::findOrFail($request->corpID);
      $itemModel = new \App\SrvItemCfg;
      $itemModel->setConnection($company->database_name);

      $items = $itemModel->where('Branch', '=', $request->branch_id)->get();

      foreach($branches as $branch) {
        if($branch->Branch == $request->branch_id) continue;

        $itemModel->setConnection($branch->company->database_name);

        foreach($items as $item) {
          $itemModel->updateOrCreate([
            'Serv_ID' => $item->Serv_ID,
            'Branch' => $branch->Branch
          ], [
            'Amount' => $item->Amount,
            'Active' => $item->Active
          ]);
        }
      }

      \Session::flash('success', "Service items are successfully copied");

      return redirect(route('services-price-conf.index', [
      ])); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
