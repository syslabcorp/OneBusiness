<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Request;
use DB;
use Config;
use URL;
use PDF;
use Twilio;
use Nexmo;
use Hash;
use Datetime;
use App\UserArea;
use App\PoModel;
use Session;
use App\City;
use App\Branch;
use App\ProductLine;
use App\StockItem;
use App\Corporation;
use App\StockDetail;
class PurchaseOrderController extends Controller
{
	public function __construct()
    {
         $this->middleware('auth');
    }

    public function purchase_order($corp_id ,$city_id, $id = NULL){
        $data =array();
        $userId = Auth::id();
        $POTemplate = '\\App\\POTemplate'.$corp_id;
        $POTemplateDetail = '\\App\\POTemplateDetail'.$corp_id;
        if (Request::isMethod('post')) {
            $formData = Request::all();
            $active = isset($formData['active']) ? 1 : 0; 
            $temp_hdr = array(
                'po_tmpl8_desc' => $formData['po_tmpl8_desc'],
                'city_id'       => $city_id,
                'po_avg_cycle'  => $formData['po_avg_cycle'],
                'active'        => $active,
            );
            $branches = isset($formData['branch']) ? $formData['branch'] : array();
            $itemIds = isset($formData['item_id']) ? $formData['item_id'] :array();
            if(empty($branches) || empty($itemIds)){
                Request::session()->flash('flash_message', 'Select Branch or retail item before you can create this Purchase Order Template');
                return redirect('list_purchase_order/'.$corp_id.'/'.$city_id.'/'.(is_null($id) ? '' : $id) . '?corpID=' . $corp_id)->withInput();
            }else{
                if($id == NULL) {
                    if(!\Auth::user()->checkAccessByIdForCorp($corp_id, 31, "A"))
                    {
                        \Session::flash('error', "You don't have permission"); 
                        return redirect("/home"); 
                    }
                $temp_hdr['created_by'] = $userId;
                $po_tmpl8_hdr = $POTemplate::insertGetId($temp_hdr);
                Request::session()->flash('flash_message', 'Product Template has been added.');
                Request::Session()->flash('alert-class', 'alert-success');
                }else{
                    if(!\Auth::user()->checkAccessByIdForCorp($corp_id, 31, "E"))
                    {
                        \Session::flash('error', "You don't have permission"); 
                        return redirect("/home"); 
                    }
                    $POTemplateDetail::where('po_tmpl8_id', $id)->delete();
                    $POTemplate::where('po_tmpl8_id', $id)->update($temp_hdr);
                    
                    Request::session()->flash('flash_message', 'Product Template has been Updated.');
                    Request::Session()->flash('alert-class', 'alert-success');
                    $po_tmpl8_hdr = $id;
                }
                
                foreach($branches as $branch){
                    foreach($itemIds as $itemId){
                        $temp_hdr_detail = array(
                            'po_tmpl8_id'     => $po_tmpl8_hdr,
                            'po_tmpl8_branch' => $branch,
                            'po_tmpl8_item'   => $itemId,
                        );
                        $POTemplateDetail::insert($temp_hdr_detail);    
                    }
                }
            }
            Request::session()->put('city_id', $city_id);
            return redirect('list_purchase_order?corpID='.$corp_id);
        }
        $accessvariable = "A";
        if ($id != NULL) {
            $accessvariable = "E";
            $detail_edit_temp_hdr =  $POTemplate::where('po_tmpl8_id',$id)->first();
            $proitemsSelected = $POTemplateDetail::where('po_tmpl8_id',$id)->select('po_tmpl8_item', 'po_tmpl8_branch')->get();
            $data['detail_edit_temp_hdr'] = $detail_edit_temp_hdr;  
            $proretailitems_ids = array();
            $probranch_ids = array();
            foreach ($proitemsSelected as $proitemSelected) {
                array_push($proretailitems_ids, $proitemSelected->po_tmpl8_item);
                array_push($probranch_ids, $proitemSelected->po_tmpl8_branch);
            }
            $prolines  =  DB::table('s_invtry_hdr')->whereIn('item_id', $proretailitems_ids)->select('Prod_Line')->groupBy('Prod_Line')->get(); 
            $proline_ids = array();
            foreach ($prolines as $proline) {
                array_push($proline_ids, $proline->Prod_Line);
            }
            $data['proline_ids'] = $proline_ids;
            $branchdata['probranch_ids'] = $probranch_ids;
        }
        $user_area_data = DB::table('user_area')->where('user_ID',$userId)->first();
        $branchdata['branches'] = array();
        if(isset($user_area_data->branch) && !is_null($user_area_data->branch)){
            $branch_idss = explode(',', $user_area_data->branch);
            $branchdata['branches'] = DB::table('t_sysdata')->where('Active',1)->where('corp_id',$corp_id)->where('City_ID',$city_id)->whereIn('Branch',$branch_idss)->orderBy('ShortName')->get();
        }
        if(isset($user_area_data->city) && !is_null($user_area_data->city)){
            $city_idss = explode(',', $user_area_data->city);
            if(in_array($city_id,$city_idss)){
                $branchdata['branches'] = DB::table('t_sysdata')->where('Active',1)->where('corp_id',$corp_id)->where('City_ID',$city_id)->orderBy('ShortName')->get();
            }
            
        }
        if(isset($user_area_data->province) && !is_null($user_area_data->province)){
            $province_idss = explode(',', $user_area_data->province);
            $cities_get = DB::table('t_cities')->select('City_ID','City')->whereIn('Prov_ID',$province_idss)->get();
            if(!empty($cities_get)){
                $citi_ids = array();
               foreach ($cities_get as $value) {
                    $citi_ids[] = $value->City_ID;
                } 
                if(in_array($city_id,$citi_ids)){
                    $branchdata['branches'] = DB::table('t_sysdata')->where('Active',1)->where('corp_id',$corp_id)->where('City_ID',$city_id)->orderBy('ShortName')->get();
                }
            }  
        }
        $data['is_branch_exist'] = count($branchdata['branches']);
        $cities = DB::table('t_cities')->select('City_ID','City')->where('City_ID',$city_id)->orderBy('t_cities.City', 'asc')->first();
        $data['product_line'] = DB::table('s_prodline')->where('Active',1)->orderBy('Product')->get();
        $data['cities'] = $cities;
        $data['corp_id'] = $corp_id;
        if(!\Auth::user()->checkAccessByIdForCorp($corp_id,31, $accessvariable))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect("/home"); 
        }
        return view('accesslevel.purchase_order',$data)->nest('branchList', 'accesslevel.product_branches', $branchdata);
    }
    public function product_branch(){
        $data =array();
        if (Request::isMethod('post')) {
            $formData = Request::all();

            $city_id = isset($formData['city_id']) ? $formData['city_id'] : '';
            $data['branches'] = DB::table('t_sysdata')->where('City_ID',$city_id)->get();
            $POTemplateDetail = '\\App\\POTemplateDetail'.$corp_id;
            $branchesSelected = $POTemplateDetail::where('po_tmpl8_id',$formData['product_id'])->select('po_tmpl8_branch')->groupBy('po_tmpl8_branch')->get();
            $probranch_ids = array();
            foreach ($branchesSelected as $branchSelected) {
                array_push($probranch_ids, $branchSelected->po_tmpl8_branch);
            }
            $data['probranch_ids'] = $probranch_ids;
        }
        return view('accesslevel.product_branches',$data);
    }

    public function retail_items(){
        $data =array();
        if (Request::isMethod('post')) {
            $formData = Request::all();
            $p_id = isset($formData['ids']) ? $formData['ids'] : array();
            $retail_itemsArray = isset($formData['retail_itemsArray']) ? $formData['retail_itemsArray'] : array();
            $inventory = array();
            foreach($p_id AS $pid){
                $s_invtry_hdr = DB::table('s_invtry_hdr')->where('Prod_Line',$pid)->where('Active',1)->orderBy('ItemCode')->get();
                foreach($s_invtry_hdr AS $s_invtry_hd){
                    array_push($inventory, $s_invtry_hd);
                }
            }
            
            $brand_name = DB::table('s_brands')->get();
            foreach($brand_name as $key=>$det){
                $b_name[$det->Brand_ID] =$det->Brand;  
            }
            $data['brandname'] = $b_name;
            $data['s_invtry_hdr']=$inventory;
            $corp_id = $formData['corp_id'];
            $POTemplateDetail = '\\App\\POTemplateDetail'.$corp_id;
            $retailsSelected = $POTemplateDetail::where('po_tmpl8_id',$formData['product_id'])->select('po_tmpl8_item')->groupBy('po_tmpl8_item')->get();
            
            $proitems_ids = array();
            foreach ($retailsSelected as $retailSelected) {
                array_push($proitems_ids, $retailSelected->po_tmpl8_item);
            }
            $data['proitems_ids'] = $proitems_ids;
            $data['retail_itemsArray']=$retail_itemsArray;
        }
        return view('accesslevel.retail_items',$data);
    }
    
    public function list_purchase_order(){
        $corp_id = isset($_GET['corpID']) ? $_GET['corpID']: '' ;
        $city_id = session('city_id'); 
        // dd($city_id);
        $data = array();
         if (Request::isMethod('post')) {
            $formData = Request::all();
            $city_id = isset($formData['city_id']) ? $formData['city_id'] :'';

            $active = isset($formData['active']) ? $formData['active'] :'';
            $data['corp_id'] = isset($formData['corp_id']) ? $formData['corp_id'] :'';
            $db_namedata = DB::table('corporation_masters')->where('corp_id',$data['corp_id'])->select('database_name')->first();
            try {
                DB::connection("$db_namedata->database_name")->getPdo();
            }catch (\Exception $e) {
                die("<tr><td colspan=4><center>Could not connect to the database.  Please check your database name added for this corporation.</center></td></tr>");
            }
            $POTemplate = '\\App\\POTemplate'.$data['corp_id'];
            $s_po_tmpl8 = $POTemplate::where('city_id',$city_id)->where('Active',$active)->orderBy('po_tmpl8_desc', 'asc')->get();
            $data['s_po_tmpl8'] = $s_po_tmpl8; 
            return view('accesslevel.list_data_purchase_order',$data);
        }
        $permission = \Auth::user()->checkAccessByIdForCorp($corp_id, 31, "V");
        if(!$permission)
        {
            \Session::flash('error', "You doncities't have permission"); 
            return redirect("/home"); 
        }elseif ($permission === 501) {
            return redirect("/501");
        }
        $user_area = UserArea::where('user_ID', Auth::id())->first();
        if(!empty($user_area->city)){
            $user_cities = explode(",", $user_area->city);
            $cities = DB::table('t_cities')->select('City_ID','City')->whereIn('City_ID', $user_cities)->orderBy('t_cities.City', 'asc')->get();
        }else if(!empty($user_area->province)){
            $user_prov = explode(",", $user_area->province);
            $cities = DB::table('t_cities')->select('City_ID','City')->whereIn('Prov_ID', $user_prov)->orderBy('t_cities.City', 'asc')->get();
        }else{
            $cities = DB::table('t_cities')->select('City_ID','City')->orderBy('t_cities.City', 'asc')->get();
        }
        $data['cities'] = $cities;
        $data['city_id'] = $city_id;
        $data['corp_id'] = $corp_id;
        return view('accesslevel.list_purchase_order',$data);
    }

    public function module_not_found(){
        return view('accesslevel.501');
    }

    public function manual(){
      if(!\Auth::user()->checkAccessByIdForCorp(Request::all()['corpID'], 40, 'V')) {
        \Session::flash('error', "You don't have permission"); 
        return redirect("/home"); 
      }
      $branch_type = false;
      $company = Corporation::findOrFail(Request::all()['corpID']);
      $stockModel = new \App\Stock;
      $stockModel->setConnection($company->database_name);
      $branchs_ID = [];
      if(\Auth::user()->isAdmin())
      {
        $cities = City::orderBy('City')->get();
        $branchs_ID = Branch::all();
        $branchs_ID = $branchs_ID->map(function($item) {
          return $item['Branch'];
        });
        $branchs_ID = $branchs_ID->toArray();
      }
      else
      {
        if((\Auth::user()->area))
        {
          $cities = [];
          if((\Auth::user()->area->branch))
          {
            $branch_type = true;
            $branchs_ID = explode( ',' ,\Auth::user()->area->branch );
            $citi_list = Branch::whereIn('Branch', $branchs_ID)->select('City_ID')->distinct()->get();
            $citi_list = $citi_list->map(function($item) {
              return $item['City_ID'];
            });
            $cities = City::whereIn('City_ID', $citi_list)->orderBy('City')->get();
          }

          if((\Auth::user()->area->province))
          {
            $provinces_ID = explode( ',' ,\Auth::user()->area->province );
            $cities = City::WhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();

            $cities_ID = $cities->map(function($item) {
              return $item['City_ID'];
            });

            $branchs_list = Branch::whereIn('City_ID', $cities_ID)->get();

            $branchs_ID = $branchs_list->map(function($item) {
              return $item['Branch'];
            });

            $branchs_ID = $branchs_ID->toArray();
          }

          if((\Auth::user()->area->city))
          {
            $cities_ID = explode( ',' ,\Auth::user()->area->city );
            $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();

            $branchs_list = Branch::whereIn('City_ID', $cities_ID)->get();

            $branchs_ID = $branchs_list->map(function($item) {
              return $item['Branch'];
            });

            $branchs_ID = $branchs_ID->toArray();
          }
          
          //$cities = City::whereIn('City_ID', $citi_list)->orWhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();
          // $cities_ID = explode( ',' ,\Auth::user()->area->city );
          // $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();
        }
        else
        {
          $cities = [];
        }
      }
      
      $prodlines = ProductLine::where('Active', 1)->orderBy('Product')->get();
      if(count($cities) > 0)
      {

        $total_branchs = Branch::whereIn('Branch', $branchs_ID)->where('corp_id', Request::all()['corpID'] )->where('Active', 1)->count();
        $branchs = Branch::where( 'City_ID', $cities->first()->City_ID )->whereIn('Branch', $branchs_ID)->where('corp_id', Request::all()['corpID'] )->where('Active', 1)->orderBy('ShortName')->get(['Branch', 'ShortName']);
      }
      else
      {
        $total_branchs = 0;
        $branchs = [];
      }
      // dd($total_branchs);
      if($company->corp_type == "INN")
      {
        $checkINN = true;
      }
      else
      {
        $checkINN = false;
      }
      return view('purchase_order.manual',
        [
          'checkINN' => $checkINN,
          'branchs' => $branchs,
          'cities' => $cities,
          'prodlines' => $prodlines,
          'corpID' => Request::all()['corpID'],
          'total_branchs' => $total_branchs,
          'branch_type' => $branch_type
        ]
      );
    }

    public function automate(){
      ini_set('max_execution_time', 300);
      if(!\Auth::user()->checkAccessByIdForCorp(Request::all()['corpID'], 41, 'V')) {
        \Session::flash('error', "You don't have permission"); 
        return redirect("/home"); 
      }
      $company = Corporation::findOrFail(Request::all()['corpID']);
      $stockModel = new \App\Stock;
      $stockModel->setConnection($company->database_name);
      
      if(\Auth::user()->isAdmin())
      {
        $cities = City::orderBy('City')->get();
      }
      else
      {
        if((\Auth::user()->area))
        {
          $cities = [];
          if((\Auth::user()->area->branch))
          {
            $branchs_ID = explode( ',' ,\Auth::user()->area->branch );
            $citi_list = Branch::whereIn('Branch', $branchs_ID)->select('City_ID')->distinct()->get();
            $citi_list = $citi_list->map(function($item) {
              return $item['City_ID'];
            });
            $cities = City::whereIn('City_ID', $citi_list)->orderBy('City')->get();
          }

          if((\Auth::user()->area->province))
          {
            $provinces_ID = explode( ',' ,\Auth::user()->area->province );
            $cities = City::WhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();
          }

          if((\Auth::user()->area->city))
          {
            $cities_ID = explode( ',' ,\Auth::user()->area->city );
            $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();
          }
          
          //$cities = City::whereIn('City_ID', $citi_list)->orWhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();
          // $cities_ID = explode( ',' ,\Auth::user()->area->city );
          // $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();
        }
        else
        {
          $cities = [];
        }
      }

      $POTemplateModel = new \App\POTemplate;
      $POTemplateModel->setConnection($company->database_name);
      
      if (count($cities) > 0)
      {
        $cities_list = $cities->map(function($item) {
          return $item['City_ID'];
        });
        $POTemplates = $POTemplateModel->where('Active', 1)->whereIn('city_id', $cities_list)->where('city_id', $cities->first()->City_ID)->orderBy('po_tmpl8_desc')->get();
      }
      else
      {
        $POTemplates = [];
      }      
      return view('purchase_order.automate',
      [
        'POTemplates' => $POTemplates,
        'cities' => $cities,
        'corpID' => Request::all()['corpID']
      ]);
    }

    public function manual_suggest()
    {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 600);
      if(!\Auth::user()->checkAccessByIdForCorp(Request::all()['corpID'], 40, 'A')) {
        \Session::flash('error', "You don't have permission"); 
        return redirect("/home"); 
      }
      $company = Corporation::findOrFail(Request::all()['corpID']);
      $stockModel = new \App\Stock;
      $stockModel->setConnection($company->database_name);

      $from_date = new Datetime(Request::all()['from_date']);
      $to_date = new Datetime(Request::all()['to_date']);

      // No Of Date
      $no_of_date = (strtotime(Request::all()['to_date']) - strtotime(Request::all()['from_date']))/86400;

      $items = array();
      $total_amount = 0;
      $header_branch = array();

      if(Request::all()['branchs'] && count(Request::all()['branchs']) )
      {
        foreach(Request::all()['branchs'] as $branch)
        {
          $header_branch[$branch] = Branch::find($branch)->ShortName; 
        }
      }

      if(Request::all()['ItemCode'] && count(Request::all()['ItemCode']) )
      {
        // dd(Config::get('database.connections.mysql.database'));
        $total_pieces = 0;
        foreach(Request::all()['ItemCode'] as $item_id)
        {
          if(Request::all()['branchs'] && count(Request::all()['branchs']) )
          {
            $branchs_by_items = array();
            $total = 0;
            foreach(Request::all()['branchs'] as $branch)
            {
              // Total Quantity Sold
              if( Request::all()['corpID'] == 7 )
              {
                $total_sold = DB::connection($company->database_name)->select("SELECT SUM(Qty) as SoldQty 
                FROM s_hdr LEFT JOIN s_detail ON s_hdr.Sales_ID = s_detail.Sales_ID AND s_hdr.Branch = s_detail.Branch 
                LEFT JOIN shifts ON s_hdr.Shift_ID = shifts.Shift_ID AND s_hdr.Branch = shifts.Branch 
                WHERE s_hdr.Branch = ? AND s_detail.item_id = ? AND shifts.shift_start >= ? AND shifts.shift_start <= ?
                GROUP BY item_id", [ $branch, $item_id, $from_date, $to_date ]);
              }
              else
              {
                $total_sold = DB::connection($company->database_name)->select("SELECT SUM(Qty) as SoldQty 
                FROM s_hdr LEFT JOIN s_detail ON s_hdr.Sales_ID = s_detail.Sales_ID AND s_hdr.Branch = s_detail.Branch 
                LEFT JOIN t_shifts ON s_hdr.Shift_ID = t_shifts.Shift_ID AND s_hdr.Branch = t_shifts.Branch 
                WHERE s_hdr.Branch = ? AND s_detail.item_id = ? AND t_shifts.ShiftDate >= ? AND t_shifts.ShiftDate <= ? 
                GROUP BY item_id", [ $branch, $item_id, $from_date, $to_date ]);
              }

              //Total Quantity of Stock
        
              $total_stock = DB::connection($company->database_name)->select("SELECT s_txfr_detail.item_id, 
              SUM(IF(NOT s_txfr_hdr.Rcvd, s_txfr_detail.Qty, 0)) AS Txit_Qty,
              SUM(IF(s_txfr_hdr.Rcvd, s_txfr_detail.Bal, 0)) AS Txfr_Bal 
              FROM s_txfr_hdr, s_txfr_detail, ".Config::get('database.connections.mysql.database').".s_invtry_hdr 
              WHERE s_txfr_hdr.Txfr_ID = s_txfr_detail.Txfr_ID AND s_txfr_hdr.Txfr_To_Branch = ?
              AND ".Config::get('database.connections.mysql.database').".s_invtry_hdr.item_id = s_txfr_detail.item_id AND s_txfr_detail.item_id = ?
              GROUP BY s_txfr_detail.item_id", [  $branch, $item_id]);
        
              //Pending PO
              $pending = DB::connection($company->database_name)->select("SELECT SUM(Qty-ServedQty) as PendingQty FROM s_po_detail 
              WHERE Branch = ? AND s_po_detail.item_id = ? AND ServedQty < Qty", [$branch, $item_id]);
              
              // process ... 

              if($total_sold && ($total_sold > 0))
              {
                $total_sold = $total_sold[0]->SoldQty;
                $daily_sold_qty = $no_of_date / $total_sold;
                if ($daily_sold_qty > 0 && is_float($daily_sold_qty))
                {
                  $daily_sold_qty = intval($daily_sold_qty) + 1;
                }
              }
              else
              {
                $daily_sold_qty = 0;
              }

              if($total_stock && ($total_stock[0]->Txit_Qty))
              {
                $quantity_stock =  $total_stock[0]->Txit_Qty + $total_stock[0]->Txfr_Bal;
              }
              else
              {
                $quantity_stock = 0;
              }

              if($pending && $pending[0]->PendingQty)
              {
                $pending_value = $pending[0]->PendingQty;
              }
              else
              {
                $pending_value = 0;
              }

              // Qty for PO
              $multiolier = StockItem::find($item_id)->Multiplier;
              // $multiolier = Request::all()['multiolier'];

              $QtyPO = ($daily_sold_qty * $multiolier) - $pending_value;
              if($QtyPO < 0)
              {
                $QtyPO = 0;
              }
              $item_packaging = StockItem::find($item_id)->Packaging;
              if ( is_float($QtyPO / $item_packaging) )
              {
                $QtyPO = (intval($QtyPO / $item_packaging) + 1 ) * $item_packaging;
              }
              $total += $QtyPO;
              
              if (StockItem::find($item_id)->LastCost)
              {
                $last_cost = StockItem::find($item_id)->LastCost;
                $itemcost = $QtyPO * StockItem::find($item_id)->LastCost;
              }
              else
              {
                $itemcost = 0;
                $last_cost = 0;
              }
              $total_amount += $itemcost;

              $item = array("item_id" => $item_id, "daily_sold_qty" => $daily_sold_qty, "Mult" => Request::all()['multiolier'],
               "stock" => $quantity_stock, "pending" => $pending_value, "QtyPO" => $QtyPO , 'cost' => $last_cost);
              $branchs_by_items[$branch] = $item;
            }

            $bal = DB::connection($company->database_name)->select(" SELECT SUM(Bal) as Bal from s_rcv_detail
                                                                     where item_id = ? GROUP BY item_id ", [$item_id] );
            if(isset($bal[0]->Bal))
            {
              $bal_val = $bal[0]->Bal;
            }
            else
            {
              $bal_val = 0;
            }
            array_push($items, ["items" => $branchs_by_items, "ItemCode" => StockItem::find($item_id)->ItemCode, "Bal" => intval($bal_val),
            'total' => $total, 'item_id' => $item_id]);
            $total_pieces += $total;
            // $branchs_by_items["ItemCode"] = StockItem::find($item_id)->ItemCode;
            // $items[$item_id] = $branchs_by_items;
            // array_push($items, [$item_id => $branchs_by_items]);
          }
        }
      }
      
      return view('purchase_order.manual_suggest',[
        'corpID' => Request::all()['corpID'],
        'items' => $items,
        'num_branch' => count(Request::all()['branchs']),
        'header_branch' => $header_branch,
        'total_amount' => $total_amount,
        'total_pieces' => $total_pieces
      ]);
    }

    public function manual_save()
    {
      if(!\Auth::user()->checkAccessByIdForCorp(Request::all()['corpID'], 40, 'A')) {
        \Session::flash('error', "You don't have permission"); 
        return redirect("/home"); 
      }

      if(array_key_exists('po_no' ,Request::all()))
      {
        $company = Corporation::findOrFail(Request::all()['corpID']);

        $PurchaseOrderModel = new \App\PurchaseOrder;
        $PurchaseOrderModel->setConnection($company->database_name);
        $PurchaseOrderDetailModel = new \App\PurchaseOrderDetail;
        $PurchaseOrderDetailModel->setConnection($company->database_name);

        $PurchaseOrderDetailModel->where('po_no', Request::all()['po_no'])->delete();
        $PurchaseOrderModel->where('po_no', Request::all()['po_no'] )->delete();
        
        $PurchaseOrderModel = new \App\PurchaseOrder;
        $PurchaseOrderModel->setConnection($company->database_name);

        $PurchaseOrderModel->po_no = Request::all()['po_no'];
        $PurchaseOrderModel->po_date = Date('Y-m-d H:i:s');
        $PurchaseOrderModel->tot_pcs = Request::all()['total_pieces'];
        $PurchaseOrderModel->total_amt = Request::all()['total_amount'];
        
        if($PurchaseOrderModel->save())
        {
          foreach( Request::all()['ItemCode'] as $item_id => $item_with_branch )
          {
            foreach($item_with_branch as $branch => $item_code)
            {
              if(Request::all()['QtyPO'][$item_id][$branch] > 0)
              {
                $PurchaseOrderDetailModel = new \App\PurchaseOrderDetail;
                $PurchaseOrderDetailModel->setConnection($company->database_name);
                $PurchaseOrderDetailModel->po_no = $PurchaseOrderModel->po_no;
                $PurchaseOrderDetailModel->Branch = $branch;
                $PurchaseOrderDetailModel->item_id = $item_id;
                $PurchaseOrderDetailModel->ItemCode = $item_code;
                $PurchaseOrderDetailModel->Qty = Request::all()['QtyPO'][$item_id][$branch];
                $PurchaseOrderDetailModel->cost = Request::all()['cost'][$item_id][$branch];
                $PurchaseOrderDetailModel->save();
              }
            }
          }
        }
      }
      else
      {
        $company = Corporation::findOrFail(Request::all()['corpID']);
        $PurchaseOrderModel = new \App\PurchaseOrder;
        $PurchaseOrderModel->setConnection($company->database_name);
        
        $PurchaseOrderModel->po_date = Date('Y-m-d H:i:s');
        $PurchaseOrderModel->tot_pcs = Request::all()['total_pieces'];
        $PurchaseOrderModel->total_amt = Request::all()['total_amount'];
        
        if($PurchaseOrderModel->save())
        {
          foreach( Request::all()['ItemCode'] as $item_id => $item_with_branch )
          {
            foreach($item_with_branch as $branch => $item_code)
            {
              if(Request::all()['QtyPO'][$item_id][$branch] > 0)
              {
                $PurchaseOrderDetailModel = new \App\PurchaseOrderDetail;
                $PurchaseOrderDetailModel->setConnection($company->database_name);
                $PurchaseOrderDetailModel->po_no = $PurchaseOrderModel->po_no;
                $PurchaseOrderDetailModel->Branch = $branch;
                $PurchaseOrderDetailModel->item_id = $item_id;
                $PurchaseOrderDetailModel->ItemCode = $item_code;
                $PurchaseOrderDetailModel->Qty = Request::all()['QtyPO'][$item_id][$branch];
                $PurchaseOrderDetailModel->cost = Request::all()['cost'][$item_id][$branch];
                $PurchaseOrderDetailModel->save();
              }
            }
          }
        }
      }
      

      // return redirect()->route('purchase_order.create_manual', [ 'corpID' => (Request::all()['corpID']) ]);

      return response()->json([
        'url' => route('purchase_order.pdf', ['id'=> $PurchaseOrderModel->po_no, 'corpID' => Request::all()['corpID']]),
        'po_no' => $PurchaseOrderModel->po_no
      ]);
      
      
      // return view('purchase_order.auto_process');
      // return redirect('purchase_order/auto_process');
    }


    public function auto_save()
    {
      ini_set('memory_limit', '-1');
      ini_set('max_execution_time', 600);
      if(!\Auth::user()->checkAccessByIdForCorp(Request::all()['corpID'], 41, 'A')) {
        \Session::flash('error', "You don't have permission"); 
        return redirect("/home"); 
      }
      if ( isset( Request::all()["temp_id"] ) )
      {
        $company = Corporation::findOrFail(Request::all()['corpID']);
        $stockModel = new \App\Stock;
        $stockModel->setConnection($company->database_name);
  
        $POTempModel = new \App\POTemplate;
        $POTempModel->setConnection($company->database_name);
        
        $temp_id = Request::all()['temp_id'];
  
        $POTemp = $POTempModel->where('po_tmpl8_id', $temp_id)->get()->first();
        $no_of_date = $POTemp->po_avg_cycle;
        
        $details = $POTemp->details;
  
        $PurchaseOrderModel = new \App\PurchaseOrder;
        $PurchaseOrderModel->setConnection($company->database_name);
      
        $PurchaseOrderModel->po_date = Date('Y-m-d H:i:s');
        $PurchaseOrderModel->po_tmpl8_id = $POTemp->po_tmpl8_id;
        $PurchaseOrderModel->save();
  
        $total_amount = 0;
        $total_pieces = 0;
        $errors = array();
        $array_today = [];
        $array_fromday = [];
        
        foreach($details as $detail)
        {
          $branch = $detail->po_tmpl8_branch;
          $item_id = $detail->po_tmpl8_item;
          $branchs_ID = [];
          if(\Auth::user()->isAdmin())
          {
            $cities = City::all();
            $branchs_ID = Branch::all();
            $branchs_ID = $branchs_ID->map(function($item) {
              return $item['Branch'];
            });
            $branchs_ID = $branchs_ID->toArray();
          }
          else
          {
            if((\Auth::user()->area))
            {
              $cities = [];
              if((\Auth::user()->area->branch))
              {
                $branchs_ID = explode( ',' ,\Auth::user()->area->branch );
                $citi_list = Branch::whereIn('Branch', $branchs_ID)->select('City_ID')->distinct()->get();
                $citi_list = $citi_list->map(function($item) {
                  return $item['City_ID'];
                });
                $cities = City::whereIn('City_ID', $citi_list)->orderBy('City')->get();
              }
    
              if((\Auth::user()->area->province))
              {
                $provinces_ID = explode( ',' ,\Auth::user()->area->province );
                $cities = City::WhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();

                $cities_ID = $cities->map(function($item) {
                  return $item['City_ID'];
                });

                $branchs_list = Branch::whereIn('City_ID', $cities_ID)->get();

                $branchs_ID = $branchs_list->map(function($item) {
                  return $item['Branch'];
                });

                $branchs_ID = $branchs_ID->toArray();
              }

              if((\Auth::user()->area->city))
              {
                $cities_ID = explode( ',' ,\Auth::user()->area->city );
                $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();

                $branchs_list = Branch::whereIn('City_ID', $cities_ID)->get();

                $branchs_ID = $branchs_list->map(function($item) {
                  return $item['Branch'];
                });

                $branchs_ID = $branchs_ID->toArray();
              }
              
              //$cities = City::whereIn('City_ID', $citi_list)->orWhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();
              // $cities_ID = explode( ',' ,\Auth::user()->area->city );
              // $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();
            }
            else
            {
              $cities = [];
            }
          }
          $cities = $cities->map(function($item) {
            return $item['City_ID'];
          });

          // $branchs = Branch::whereIn( 'City_ID', $cities )->get();
          // $branchs_ID = $branchs->map(function($item) {
          //   return $item['Branch'];
          // });

          if(!in_array($branch, $branchs_ID))
          {
            if (Branch::where('Branch', $branch)->get()->first())
            {
              array_push($errors, Branch::where('Branch', $branch)->get()->first()->ShortName ) ;
            }
            continue;
          } 
  
          $SaleDetailModel = new \App\SaleDetail;
          $SaleDetailModel->setConnection($company->database_name);
          $SaleDetail = $SaleDetailModel->where('item_id', $item_id)->where('Branch', $branch)->distinct()->get();
  
          // $test = DB::connection($company->database_name)->select( " SELECT * from s_hdr order by s_hdr.DateSold DESC LIMIT 1");
          
          // if(count($test) > 0)
          // {
          //   $test = $test[0]->DateSold;
          //   $to_date = DateTime::createFromFormat('Y-m-d H:i:s', $test);
          //   $from_date = new DateTime( date( "Y-m-d", strtotime("-".$no_of_date." day", strtotime($test))));
          // }
            $to_date = date("Y-m-d H:i:s");
            $from_date = new DateTime( date( "Y-m-d", strtotime("-".$no_of_date." day", strtotime($to_date))));
          
          // if( count($SaleDetail) > 0 )
          // {
          //   if($SaleDetail->first()->sale)
          //   {
          //     $to_date = $SaleDetail->first()->sale()->first()->DateSold;
          //     $from_date = new DateTime( date( "Y-m-d", strtotime("-".$no_of_date." day", strtotime($to_date))));
              
          //     foreach($SaleDetail as $detail)
          //     {
          //       if( $detail->sale )
          //       {
          //         if( $detail->sale()->first()->DateSold > $to_date )
          //         {
          //           $to_date = $detail->sale()->first()->DateSold;
          //           $from_date = new DateTime( date( "Y-m-d", strtotime("-".$no_of_date." day", strtotime($to_date))));
          //         }
          //       }
          //     }
          //   }
          // }

          if(isset($from_date) && isset($to_date))
          {
            array_push($array_today, $to_date);
            array_push($array_fromday, $from_date);
            if( Request::all()['corpID'] == 7 )
            {
              $total_sold = DB::connection($company->database_name)->select("SELECT SUM(Qty) as SoldQty 
              FROM s_hdr LEFT JOIN s_detail ON s_hdr.Sales_ID = s_detail.Sales_ID AND s_hdr.Branch = s_detail.Branch 
              LEFT JOIN shifts ON s_hdr.Shift_ID = shifts.Shift_ID AND s_hdr.Branch = shifts.Branch 
              WHERE s_hdr.Branch = ? AND s_detail.item_id = ? AND shifts.shift_start >= ? AND shifts.shift_start <= ?
              GROUP BY item_id", [ $branch, $item_id,  $from_date, $to_date  ]);
            }
            else
            {
              $total_sold = DB::connection($company->database_name)->select("SELECT SUM(Qty) as SoldQty 
              FROM s_hdr LEFT JOIN s_detail ON s_hdr.Sales_ID = s_detail.Sales_ID AND s_hdr.Branch = s_detail.Branch 
              LEFT JOIN t_shifts ON s_hdr.Shift_ID = t_shifts.Shift_ID AND s_hdr.Branch = t_shifts.Branch 
              WHERE s_hdr.Branch = ? AND s_detail.item_id = ? AND t_shifts.ShiftDate >= ? AND t_shifts.ShiftDate <= ?
              GROUP BY item_id", [ $branch, $item_id,  $from_date, $to_date  ]);
            }
          }
          else
          {
            $total_sold = 0;
          }

          //Total Quantity of Stock
    
          $total_stock = DB::connection($company->database_name)->select("SELECT s_txfr_detail.item_id, 
          SUM(IF(NOT s_txfr_hdr.Rcvd, s_txfr_detail.Qty, 0)) AS Txit_Qty,
          SUM(IF(s_txfr_hdr.Rcvd, s_txfr_detail.Bal, 0)) AS Txfr_Bal 
          FROM s_txfr_hdr, s_txfr_detail, ".Config::get('database.connections.mysql.database').".s_invtry_hdr 
          WHERE s_txfr_hdr.Txfr_ID = s_txfr_detail.Txfr_ID AND s_txfr_hdr.Txfr_To_Branch = ?
          AND ".Config::get('database.connections.mysql.database').".s_invtry_hdr.item_id = s_txfr_detail.item_id AND s_txfr_detail.item_id = ?
          GROUP BY s_txfr_detail.item_id", [  $branch, $item_id]);
    
          //Pending PO
          $pending = DB::connection($company->database_name)->select("SELECT SUM(Qty-ServedQty) as PendingQty FROM s_po_detail 
          WHERE Branch = ? AND s_po_detail.item_id = ? AND ServedQty < Qty", [$branch, $item_id]);
          
          // process ... 
  
          if($total_sold && ($total_sold > 0))
          {
            $total_sold = $total_sold[0]->SoldQty;
            $daily_sold_qty = $no_of_date / $total_sold;
            if ($daily_sold_qty > 0 && is_float($daily_sold_qty))
            {
              $daily_sold_qty = intval($daily_sold_qty) + 1;
            }
          }
          else
          {
            $daily_sold_qty = 0;
          }
  
          if($total_stock && ($total_stock[0]->Txit_Qty))
          {
            $quantity_stock =  $total_stock[0]->Txit_Qty + $total_stock[0]->Txfr_Bal;
          }
          else
          {
            $quantity_stock = 0;
          }
  
          if($pending && $pending[0]->PendingQty)
          {
            $pending_value = $pending[0]->PendingQty;
          }
          else
          {
            $pending_value = 0;
          }

          // Qty for PO

          if( !StockItem::find($item_id) )
          {
            $PurchaseOrderModel->delete();
            return response()->json([
              'num_details' => 0,
              'to_date' => $array_today,
              'from_date' => $array_fromday
              ]
            );
          }
          $item_packaging = StockItem::find($item_id)->Packaging;
          $item_code = StockItem::find($item_id)->ItemCode;
          $multiolier = StockItem::find($item_id)->Multiplier;

          $QtyPO = ($daily_sold_qty * $multiolier) - $pending_value;
          if($QtyPO < 0)
          {
            $QtyPO = 0;
          }
          if ( is_float($QtyPO / $item_packaging) )
          {
            $QtyPO = (intval($QtyPO / $item_packaging) + 1 ) * $item_packaging;
          }
          $total_pieces += $QtyPO;
          
          if (StockItem::find($item_id)->LastCost)
          {
            $last_cost = StockItem::find($item_id)->LastCost;
            $itemcost = $QtyPO * StockItem::find($item_id)->LastCost;
          }
          else
          {
            $itemcost = 0;
            $last_cost = 0;
          }
          $total_amount += $itemcost;
  
          if($QtyPO > 0)
          {
            $PurchaseOrderDetailModel = new \App\PurchaseOrderDetail;
            $PurchaseOrderDetailModel->setConnection($company->database_name);
            $PurchaseOrderDetailModel->po_no = $PurchaseOrderModel->po_no;
            $PurchaseOrderDetailModel->Branch = $branch;
            $PurchaseOrderDetailModel->item_id = $item_id;
            $PurchaseOrderDetailModel->ItemCode = $item_code;
            $PurchaseOrderDetailModel->Qty = $QtyPO;
            $PurchaseOrderDetailModel->cost = $last_cost;
            $PurchaseOrderDetailModel->save();
          }
        }
        

        $PurchaseOrderModel->tot_pcs = $total_pieces;
        $PurchaseOrderModel->total_amt = $total_amount;
        $PurchaseOrderModel->save();
        if ( $PurchaseOrderModel->purchase_order_details()->count() == 0 || $PurchaseOrderModel->tot_pcs == 0 || $PurchaseOrderModel->total_amt == 0 || $PurchaseOrderModel->total_amt == null  )
        {
          $PurchaseOrderModel->delete();
          return response()->json([
            'num_details' => 0,
            'to_date' => $array_today,
            'from_date' => $array_fromday
            ]
          );
        }
  
        return response()->json([
          'url' => route('purchase_order.pdf', ['id'=> $PurchaseOrderModel->po_no, 'corpID' => Request::all()['corpID']]),
          'po_no' => $PurchaseOrderModel->po_no,
          'num_details' => ($PurchaseOrderModel->purchase_order_details()->count() ),
          'total_sold' => $total_sold,
          'pending' => $pending,
          'error' => $errors,
          'to_date' => $array_today,
          'from_date' => $array_fromday
          ]
          
        );
      }

    }

    public function pdf($id)
    {
      $company = Corporation::findOrFail(Request::all()['corpID']);
      
      $PurchaseOrderModel = new \App\PurchaseOrder;
      $PurchaseOrderModel->setConnection($company->database_name);
      $purchase_order = $PurchaseOrderModel->where('po_no' , $id )->get()->first();
    
      $PurchaseOrderDetailModel = new \App\PurchaseOrderDetail;
      $PurchaseOrderDetailModel->setConnection($company->database_name);
    
      $purchase_order_details = $PurchaseOrderDetailModel->where('po_no', $id )->get()->groupBy('item_id');
      $total_qty = array();
      foreach($purchase_order_details as $key => $details)
      {
        $total = 0;
        foreach($details as $detail)
        {
          $total += $detail->Qty;
        }
        $total_qty[$key] = $total;
      }
      $branchs = $PurchaseOrderDetailModel->where('po_no', $id )->get()->groupBy('Branch');


      $file_name = $company->corp_name."_".$purchase_order->po_no."_".$purchase_order->po_date->format("Ymd");
      $index = 1;
      $quantity = 5;
      if(is_float(count($branchs) / $quantity))
      {
        $num_page = intval(count($branchs) / $quantity) + 1;
      }
      else
      {
        $num_page = intval(count($branchs) / $quantity);
      }
      $pdf = PDF::loadView('purchase_order/pdf', compact('purchase_order', 'purchase_order_details', 'branchs', 'num_page', 'index', 'quantity', 'total_qty'));
      $pdf->setPaper('A4', 'landscape');
      return $pdf->stream($file_name.".pdf");
    }

    public function auto_process(){

      return view('purchase_order.auto_process');
    }

    public function ajax_render_branch_by_city()
    {
      $branchs_ID = [];
      if(\Auth::user()->isAdmin())
      {
        $cities = City::all();
        $branchs_ID = Branch::all();
        $branchs_ID = $branchs_ID->map(function($item) {
          return $item['Branch'];
        });
        $branchs_ID = $branchs_ID->toArray();
      }
      else
      {
        if((\Auth::user()->area))
        {
          $cities = [];
          if((\Auth::user()->area->branch))
          {
            $branchs_ID = explode( ',' ,\Auth::user()->area->branch );
            $citi_list = Branch::whereIn('Branch', $branchs_ID)->select('City_ID')->distinct()->get();
            $citi_list = $citi_list->map(function($item) {
              return $item['City_ID'];
            });
            $cities = City::whereIn('City_ID', $citi_list)->orderBy('City')->get();
          }

          if((\Auth::user()->area->province))
          {
            $provinces_ID = explode( ',' ,\Auth::user()->area->province );
            $cities = City::WhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();

            $cities_ID = $cities->map(function($item) {
              return $item['City_ID'];
            });

            $branchs_list = Branch::whereIn('City_ID', $cities_ID)->get();

            $branchs_ID = $branchs_list->map(function($item) {
              return $item['Branch'];
            });

            $branchs_ID = $branchs_ID->toArray();
          }

          if((\Auth::user()->area->city))
          {
            $cities_ID = explode( ',' ,\Auth::user()->area->city );
            $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();

            $branchs_list = Branch::whereIn('City_ID', $cities_ID)->get();

            $branchs_ID = $branchs_list->map(function($item) {
              return $item['Branch'];
            });

            $branchs_ID = $branchs_ID->toArray();
          }
          
          //$cities = City::whereIn('City_ID', $citi_list)->orWhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();
          // $cities_ID = explode( ',' ,\Auth::user()->area->city );
          // $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();
        }
        else
        {
          $cities = [];
        }
      }
      $cities = $cities->map(function($item) {
        return $item['City_ID'];
      });

      $branchs = Branch::whereIn( 'City_ID', $cities )->whereIn('Branch', $branchs_ID)->where( 'City_ID', (Request::all()['City_ID']) )->where('Active', 1)->where('corp_id',(Request::all()['Corp_ID']) )->orderBy('ShortName')->get(['Branch', 'ShortName']);
      return response()->json([
        'branchs' => $branchs
      ], 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function ajax_render_branch_by_all_cities()
    { 
      $branchs_ID = [];
      if(\Auth::user()->isAdmin())
      {
        $cities = City::all();
        $branchs_ID = Branch::all();
        $branchs_ID = $branchs_ID->map(function($item) {
          return $item['Branch'];
        });
        $branchs_ID = $branchs_ID->toArray();
      }
      else
      {
        if((\Auth::user()->area))
        {
          $cities = [];
          if((\Auth::user()->area->branch))
          {
            $branchs_ID = explode( ',' ,\Auth::user()->area->branch );
            $citi_list = Branch::whereIn('Branch', $branchs_ID)->select('City_ID')->distinct()->get();
            $citi_list = $citi_list->map(function($item) {
              return $item['City_ID'];
            });
            $cities = City::whereIn('City_ID', $citi_list)->orderBy('City')->get();
          }

          if((\Auth::user()->area->province))
          {
            $provinces_ID = explode( ',' ,\Auth::user()->area->province );
            $cities = City::WhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();

            $cities_ID = $cities->map(function($item) {
              return $item['City_ID'];
            });

            $branchs_list = Branch::whereIn('City_ID', $cities_ID)->get();

            $branchs_ID = $branchs_list->map(function($item) {
              return $item['Branch'];
            });

            $branchs_ID = $branchs_ID->toArray();
          }

          if((\Auth::user()->area->city))
          {
            $cities_ID = explode( ',' ,\Auth::user()->area->city );
            $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();

            $branchs_list = Branch::whereIn('City_ID', $cities_ID)->get();

            $branchs_ID = $branchs_list->map(function($item) {
              return $item['Branch'];
            });

            $branchs_ID = $branchs_ID->toArray();
          }
          
          //$cities = City::whereIn('City_ID', $citi_list)->orWhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();
          // $cities_ID = explode( ',' ,\Auth::user()->area->city );
          // $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();
        }
        else
        {
          $cities = [];
        }
      }
      $cities = $cities->map(function($item) {
        return $item['City_ID'];
      });

      $branchs = Branch::whereIn( 'City_ID', $cities )->whereIn('Branch', $branchs_ID)->where('Active', 1)->where('corp_id',(Request::all()['Corp_ID']) )->orderBy('ShortName')->get(['Branch', 'ShortName']);
      return response()->json([
        'branchs' => $branchs
      ], 200, ['Content-type'=> 'application/json; charset=utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function ajax_render_template_by_city()
    {
      if(\Auth::user()->isAdmin())
      {
        $cities = City::all();
      }
      else
      {
        if((\Auth::user()->area))
        {
          $cities = [];
          if((\Auth::user()->area->branch))
          {
            $branchs_ID = explode( ',' ,\Auth::user()->area->branch );
            $citi_list = Branch::whereIn('Branch', $branchs_ID)->select('City_ID')->distinct()->get();
            $citi_list = $citi_list->map(function($item) {
              return $item['City_ID'];
            });
            $cities = City::whereIn('City_ID', $citi_list)->orderBy('City')->get();
          }

          if((\Auth::user()->area->province))
          {
            $provinces_ID = explode( ',' ,\Auth::user()->area->province );
            $cities = City::WhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();
          }

          if((\Auth::user()->area->city))
          {
            $cities_ID = explode( ',' ,\Auth::user()->area->city );
            $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();
          }
          
          //$cities = City::whereIn('City_ID', $citi_list)->orWhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();
          // $cities_ID = explode( ',' ,\Auth::user()->area->city );
          // $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();
        }
        else
        {
          $cities = [];
        }
      }
      $cities = $cities->map(function($item) {
        return $item['City_ID'];
      });
      $company = Corporation::findOrFail(Request::all()['corpID']);
      $POTemplateModel = new \App\POTemplate;
      $POTemplateModel->setConnection($company->database_name);
      
      $POTemplates = $POTemplateModel->where('Active', 1)->whereIn('city_id', $cities)->where('city_id', Request::all()['City_ID'])->orderBy('po_tmpl8_desc')->get();
      return response()->json([
        'POTemplates' => $POTemplates
      ]);
    }

    public function ajax_render_template_by_all_cities()
    {
      if(\Auth::user()->isAdmin())
      {
        $cities = City::all();
      }
      else
      {
        if((\Auth::user()->area))
        {
          $cities = [];
          if((\Auth::user()->area->branch))
          {
            $branchs_ID = explode( ',' ,\Auth::user()->area->branch );
            $citi_list = Branch::whereIn('Branch', $branchs_ID)->select('City_ID')->distinct()->get();
            $citi_list = $citi_list->map(function($item) {
              return $item['City_ID'];
            });
            $cities = City::whereIn('City_ID', $citi_list)->orderBy('City')->get();
          }

          if((\Auth::user()->area->province))
          {
            $provinces_ID = explode( ',' ,\Auth::user()->area->province );
            $cities = City::WhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();
          }

          if((\Auth::user()->area->city))
          {
            $cities_ID = explode( ',' ,\Auth::user()->area->city );
            $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();
          }
          
          //$cities = City::whereIn('City_ID', $citi_list)->orWhereIn('Prov_ID', $provinces_ID)->orderBy('City')->get();
          // $cities_ID = explode( ',' ,\Auth::user()->area->city );
          // $cities = City::whereIn('City_ID', $cities_ID)->orderBy('City')->get();
        }
        else
        {
          $cities = [];
        }
      }
      $cities = $cities->map(function($item) {
        return $item['City_ID'];
      });

      $company = Corporation::findOrFail(Request::all()['corpID']);
      $POTemplateModel = new \App\POTemplate;
      $POTemplateModel->setConnection($company->database_name);

      $POTemplates = $POTemplateModel->where('Active', 1)->whereIn('city_id', $cities)->orderBy('po_tmpl8_desc')->get();
      return response()->json([
        'POTemplates' => $POTemplates
      ]);
    }

    public function ajax_render_item_by_prodline()
    {
      $items = StockItem::where( 'Prod_Line', Request::all()['ProdLine'] )->where('Active', 1)->orderBy('ItemCode')->get();
      return response()->json([
        'items' => $items
      ]);
    }
}


