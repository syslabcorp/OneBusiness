<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Request;
use DB;
use URL;
use Twilio;
use Nexmo;
use Hash;
use App\UserArea;
use App\PoModel;
use Session;
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
                return redirect('purchase_order/'.$corp_id.'/'.$city_id.'/'.(is_null($id) ? '' : $id))->withInput();
            }else{
                if($id == NULL) {
                    if(!\Auth::user()->checkAccessByPoId([$corp_id],31, "A"))
                    {
                        \Session::flash('error', "You don't have permission"); 
                        return redirect("/home"); 
                    }
                $po_tmpl8_hdr = $POTemplate::insertGetId($temp_hdr);
                Request::session()->flash('flash_message', 'Product Template has been added.');
                Request::Session()->flash('alert-class', 'alert-success');
                }else{
                    if(!\Auth::user()->checkAccessByPoId([$corp_id],31, "E"))
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
        if(!\Auth::user()->checkAccessByPoId([$corp_id],31, $accessvariable))
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
        $permission = \Auth::user()->checkAccessByPoId([$corp_id],31, "V");
        if(!$permission)
        {
            \Session::flash('error', "You don't have permission"); 
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
}


