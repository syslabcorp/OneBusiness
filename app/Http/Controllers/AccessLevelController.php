<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Request;
use DB;
use URL;
use Twilio;
use Nexmo;
use Hash;

class AccessLevelController extends Controller
{
	public function __construct()
    {
         $this->middleware('auth');
    }

    public function add_corporation($corp_id = NULL)
    {
    	if (Request::isMethod('post')) {
			$formData = Request::all();
			$created_at   = date("Y-m-d H:i:s");
        	$data = array('corp_name' => $formData["corporation_title"],"created_at" => date("Y-m-d H:i:s"));
        	if ($corp_id == NULL) {
	        	DB::table('corporation_masters')->insertGetId($data);
	        	Request::session()->flash('flash_message', 'Corporation has been added.');
	        }else{
	        	DB::table('corporation_masters')->where('corp_id', $corp_id)->update($data);
	        	Request::session()->flash('flash_message', 'Corporation has been updated.');
	        }
			Request::Session()->flash('alert-class', 'alert-success');
        	return redirect('list_corporation');
		}
		$data =array();
		if ($corp_id != NULL) {
			$data['detail_edit'] = DB::table('corporation_masters')->where('corp_id', $corp_id)->first();	
		}
		return view('accesslevel.addcorporation',$data);
    }
	
	public function list_corporation()
    {
    	$detail = DB::table('corporation_masters')->get();
        return view('accesslevel.listcorporation', ['detail' => $detail]);	
    }
	
    public function destroycorporation($corp_id)
    {
        DB::table('corporation_masters')->where('corp_id', $corp_id)->delete();
        $get_module_id  = DB::table('module_masters')->select('module_id')->where('corp_id', $corp_id)->get();
            foreach ($get_module_id as $modul_id) {
                $module_id=$modul_id->module_id;
                $mid_array =array();
                $mid_push =array_push($mid_array, $module_id);
                $this->destroymodule($mid_array);
            }
       
        Request::session()->flash('flash_message', 'Corporation has been Deleted.');
        Request::Session()->flash('alert-class', 'alert-success');
        return redirect('list_corporation');  
    }
	
	public function add_module($module_id = NULL)
    {   
        $data =array();
        $data['corporation'] = DB::table('corporation_masters')->select('corp_name', 'corp_id')->get();
        if (Request::isMethod('post')) {
            $formData = Request::all();
            $created_at   = date("Y-m-d H:i:s");
            $data = array('corp_id' => $formData["corp_id"],'description' =>$formData["module_name"],"created_at" => date("Y-m-d H:i:s"));
            if ($module_id == NULL) {
                DB::table('module_masters')->insertGetId($data);
                Request::session()->flash('flash_message', 'Module has been added.');
            }else{
                DB::table('module_masters')->where('module_id', $module_id)->update($data);
                Request::session()->flash('flash_message', 'Module has been updated.');
            }
            Request::Session()->flash('alert-class', 'alert-success');
            return redirect('list_module');
        }
        if ($module_id != NULL) {
            $data['detail_edit_module'] = DB::table('module_masters')->where('module_id', $module_id)->first();   
        }
        return view('accesslevel.addmodule', $data);
    }
	
    public function list_module()
    {   
        $detailmodule = DB::table('module_masters')
            ->join('corporation_masters', 'module_masters.corp_id', '=', 'corporation_masters.corp_id')
            ->select('module_masters.*', 'corporation_masters.corp_name')
            ->get();   
        return view('accesslevel.listmodule', ['detail' => $detailmodule]);  
    }
    public function destroymodule($module_id)
    {
        DB::table('module_masters')->where('module_id', $module_id)->delete();
        $get_feature_id  = DB::table('feature_masters')->select('feature_id')->where('module_id', $module_id)->get();
        foreach ($get_feature_id as $fetur_id) {
                $fet_id=$fetur_id->feature_id;
                $fet_array =array();
                $fet_push =array_push($fet_array, $fet_id);
                $this->destroyfeature($fet_array);
            }
            $mod_array =array();
            $mod_push =array_push($mod_array, $module_id);
            DB::table('rights_mstr')->whereIn('module_id', $mod_array)->delete();
            Request::session()->flash('flash_message', 'Module has been Deleted.');
            Request::Session()->flash('alert-class', 'alert-success');
            return redirect('list_module');    
    }

    public function add_feature($feature_id = NULL, $module_id = NULL)
    {   
        $data =array();
        $data['module'] = DB::table('module_masters')->select('description', 'module_id')->get();
        if (Request::isMethod('post')) {
            $formData = Request::all();
            $created_at   = date("Y-m-d H:i:s");
            $data = array(
                'module_id' => $formData["module_id"],
                'feature' =>$formData["feature_name"],
                "created_at" => date("Y-m-d H:i:s")
            );
            if ($feature_id == NULL || $feature_id == 0) {
                DB::table('feature_masters')->insertGetId($data);
                Request::session()->flash('flash_message', 'Feature has been added.');
            }else{
                DB::table('feature_masters')->where('feature_id', $feature_id)->update($data);
                Request::session()->flash('flash_message', 'Feature has been updated.');
            }
            Request::Session()->flash('alert-class', 'alert-success');
			if($module_id == NULL){
				return redirect('list_feature');
			}else{
				return redirect('list_feature/'.$module_id);
			}
        }
        if ($feature_id != NULL && $feature_id != 0) {
            $data['detail_edit_feature'] = DB::table('feature_masters')->where('feature_id', $feature_id)->first();    
        }else if($module_id != NULL){
			$default_module['module_id'] = $module_id;
			$data['detail_edit_feature'] = (object) $default_module;
		}
        return view('accesslevel.addfeature', $data);
    }
	
    public function list_feature($module_id = NULL)
    {   
        $data['module_id'] = $module_id;
		if($module_id == NULL){
			$data['detailfeature'] = DB::table('feature_masters')->join('module_masters', 'feature_masters.module_id', '=', 'module_masters.module_id')->select('feature_masters.*', 'module_masters.description')->get();   
		}else{
			$data['detailfeature'] = DB::table('feature_masters')->join('module_masters', 'feature_masters.module_id', '=', 'module_masters.module_id')->select('feature_masters.*', 'module_masters.description')->where('module_masters.module_id', $module_id)->get(); 
            $data['module_desc'] =   DB::table('module_masters')->select('description')->where('module_id', $module_id)->first(); 
		}
        return view('accesslevel.listfeature', $data);  
    }
	
    public function destroyfeature($feature_id,$module_id = NULL)
    {
        $fid_array =array();
        $fid_push = array_push($fid_array, $feature_id);
        DB::table('feature_masters')->whereIn('feature_id', $fid_array)->delete();
        DB::table('rights_detail')->whereIn('feature_id', $fid_array)->delete();
        Request::session()->flash('flash_message', 'Feature has been Deleted.');
        Request::Session()->flash('alert-class', 'alert-success');
        if($module_id == NULL){
            return redirect('list_feature');
        }else{
            return redirect('list_feature/'.$module_id);
        }
    }

    public function add_template($template_id = NULL)
    {   
        $data =array();
        if (Request::isMethod('post')) {
            $formData = Request::all();
			if(!isset($formData['module_id'])){
				Request::session()->flash('flash_message', 'Create a module and features first before you can create a template for this corporation.');
				return redirect('add_template');
			}
            $menus =isset($formData['menu']) ? implode(",", $formData['menu']) : NULL;
            $template_name  = $formData["temp_name"];
            $created_at   = date("Y-m-d H:i:s");
            $datatemplate = array(
                'description' => $template_name,
                "created_at"  => date("Y-m-d H:i:s"),
                "template_menus" =>$menus
            );
            if ($template_id == NULL) {
                $tid = DB::table('rights_template')->insertGetId($datatemplate);
                Request::session()->flash('flash_message', 'Template has been added.');
            }else{
                DB::table('rights_template')->where('template_id', $template_id)->update($datatemplate);
                $tid = $template_id;
                DB::table('rights_mstr')->where('template_id', $template_id)->delete();
                DB::table('rights_detail')->where('template_id', $template_id)->delete();
                Request::session()->flash('flash_message', 'Template has been updated.');
            }
            foreach ($formData['module_id'] as $module) {
                $features =isset($formData['feature_'.$module]) ? $formData['feature_'.$module] : array();
                $datamodule = array(
                    'template_id' => $tid,
                    'module_id'   =>$module,
                );
                DB::table('rights_mstr')->insertGetId($datamodule);
                // Insert into  rights_mstr
                if(!empty($features)){
                    foreach ($features as $feature) {
                        $access_a = isset($formData['access_'.$module.'_'.$feature.'_a']) ? $formData['access_'.$module.'_'.$feature.'_a'] : '';
                        $access_e = isset($formData['access_'.$module.'_'.$feature.'_e']) ? $formData['access_'.$module.'_'.$feature.'_e'] : '';
                        $access_v = isset($formData['access_'.$module.'_'.$feature.'_v']) ? $formData['access_'.$module.'_'.$feature.'_v'] : '';
                        $access_d = isset($formData['access_'.$module.'_'.$feature.'_d']) ? $formData['access_'.$module.'_'.$feature.'_d'] : '';
                        
                        if($access_a == "" && $access_e == "" && $access_v == "" && $access_d == ""){
                            $access_type = 0;
                        }else{
                            $access_type = $access_d.$access_a.$access_v.$access_e;
                        }
                        $datafeature = array(
                            'module_id'   => $module,
                            'template_id' => $tid,
                            'feature_id'  => $feature,
                            'access_type' => $access_type,
                        );
                        DB::table('rights_detail')->insertGetId($datafeature);
                    }
                }
            }
            Request::Session()->flash('alert-class', 'alert-success');
            return redirect('list_template');
        }
        if ($template_id != NULL) {
            $template_menu_ids = DB::table('rights_template')->where('template_id', $template_id)->first();  
            $menu_id_data = explode(",", $template_menu_ids->template_menus);
            $data['menu_ids'] = $menu_id_data;
            $data['detail_edit_template'] = $template_menu_ids;  
        }
        return view('accesslevel.addtemplate', $data);
    }
    public function template_module()
    { 
        $formData = Request::all(); 
        $modules=DB::table('module_masters')->LeftJoin('corporation_masters', 'corporation_masters.corp_id', '=', 'module_masters.corp_id')->select('module_masters.*', 'corporation_masters.corp_name')->get();
		$data['modules'] = array();
        foreach ($modules as $modul) {
			$data['modules'][$modul->corp_name][] = $modul;
            $mid=$modul->module_id;
            $data['features'][$mid]=DB::table('feature_masters')->where('module_id', '=', $mid)->get();
        }
		
        if($formData['template_id'] != 0){
            $template_module_ids = DB::table('rights_mstr')->select('module_id')->where('template_id', $formData['template_id'])->get();
            $module_ids = array();
            foreach ($template_module_ids as $template_module_id) {
                array_push($module_ids, $template_module_id->module_id);
            }
            $data['module_ids'] = $module_ids;
            $feature_access = DB::table('rights_detail')->select('feature_id','access_type')->where('template_id', '=', $formData['template_id'])->get();
            $fet_access =array();
            foreach ($feature_access as $featureaccess) {

                $fet_access[$featureaccess->feature_id] = $featureaccess->access_type;  
            }
            $data['fet_access'] = $fet_access;  
        } 
        return view('accesslevel.template_module',$data);
    }
	
    public function list_template()
    {   
        $listtemplate = DB::table('rights_template')->get();  
        return view('accesslevel.list_template', ['listtemp' => $listtemplate]);  
    }
    public function destroytemplate($template_id)
    {
        DB::table('rights_template')->where('template_id', $template_id)->delete();
        DB::table('rights_mstr')->where('template_id', $template_id)->delete();
        DB::table('rights_detail')->where('template_id', $template_id)->delete();
        Request::session()->flash('flash_message', 'Template has been Deleted.');
        Request::Session()->flash('alert-class', 'alert-success');
        return redirect('list_template');  
    }
	
	public function template_exist(){
		$formData = Request::all(); 
		$is_exist = DB::table('rights_template')->where('template_id', '!=', $formData['unique_temp_id'])->where('description', $formData['temp_name'])->count();
		if($is_exist){
			echo "false";
		}else{
			echo "true";
		}
	}
	
	public function add_menu($parent_id = 0,$id = NULL)
    {   
        if (Request::isMethod('post')){
            $formData = Request::all();
            $created_at   = date("Y-m-d H:i:s");
            $data = array('parent_id' => $parent_id,'title' => $formData["title"],'icon' => $formData["icon"],'url' => $formData["url"],"created_at" => date("Y-m-d H:i:s"));
            if ($id == NULL) {
                DB::table('menus')->insertGetId($data);
                Request::session()->flash('flash_message', 'Menu has been added.');
            }else{
                DB::table('menus')->where('id', $id)->update($data);
                Request::session()->flash('flash_message', 'Menu has been updated.');
            }
            Request::Session()->flash('alert-class', 'alert-success');
            return redirect('list_menu/'.$parent_id);
        }
        $data =array();
        if ($id != NULL) {
            $data['detail_edit'] = DB::table('menus')->where('id', $id)->first();   
        }

        $data['parent_id'] = $parent_id;
        return view('accesslevel.add_menu',$data);
    }

    public function get_child_menu(){
        $formData = Request::all();
        $menus = DB::table('menus')->where('parent_id', $formData['id'])->get();
		$temp_menu = isset($formData['menu_ids']) ? $formData['menu_ids'] : array();
		$child_menu = '<ul class="remove-append-'.$formData['id'].'">';
        foreach ($menus as $key => $menu) {
            $child_menu .=  '<li class="appen-sub-'.$menu->id.'"><input type="checkbox" '.(in_array($menu->id, $temp_menu) ? "checked" : '').' name="menu[]" id="click-by-'.$menu->id.'" class="append-child-menu" value="'.$menu->id.'" style="margin-right: 10px;" />'.$menu->title.'</li>';
        }
		$child_menu .= '</ul>';
		echo $child_menu;
		die;
    }
	
    public function list_menu($parent_id = 0)
    {   
        if (Request::isMethod('post')){
			$menus  =  DB::table('menus')->select('id', 'parent_id', 'title', 'url','icon')->get(); 
			$datamenu = array();
			$data = array();
			foreach ($menus AS $menu){
				$datamenu['id'] = $menu->id;
				$datamenu['text'] = $menu->title;
				$datamenu['parent_id'] = $menu->parent_id;
				$datamenu['href'] = $menu->url;
				$datamenu['icon'] = ($menu->icon!='')?$menu->icon:'glyphicon glyphicon-record visibility-hidden';
				array_push($data, $datamenu); 
			}
			$itemsByReference = array();
			// Build array of item references:
			 foreach($data as $key => &$item) {
				$itemsByReference[$item['id']] = &$item;
			 }
			  // Set items as children of the relevant parent item.
			 foreach($data as $key => &$item)  {
				if($item['parent_id'] && isset($itemsByReference[$item['parent_id']])) {
				   $itemsByReference [$item['parent_id']]['nodes'][] = &$item;
				}
			 } 
			 $dataaaray =array();
			 foreach ($data as $key => $value) {
				if($value['parent_id'] == 0){
				   array_push($dataaaray, $data[$key]); 
				}
			 }
			echo json_encode($dataaaray);  
			die;
        }
        /*Menus Tree View End */
        /*List of Menus Start */
        
        $data['parent_id'] = $parent_id;
        $menu_detail = DB::table('menus')->where('parent_id', $parent_id)->get();
        $menu_ids = array();
        $child_count = array();
        $url = url('/list_menu/');
        foreach ($menu_detail AS $menudetail){
            array_push($menu_ids, $menudetail->id);
        }
        if($parent_id != 0){
            $crumbarray =array();
            $crumbs = $this->createPath($parent_id);
            $excrumbs = explode(">>", $crumbs);
            foreach ($excrumbs as $value) {
                $exarray = explode("|", $value);
                $anchor = "<a href ='$url/$exarray[0]'>$exarray[1]</a>";   
                array_push($crumbarray, $anchor);
                $implodecrumb = implode(" >> ", $crumbarray); 
            }
            $data['parentcrumb']=$implodecrumb;
                
        }else{
            $data['parentcrumb'] = "0";
        }

        $child_menus = DB::table('menus')->whereIn('parent_id', $menu_ids)->get();
        foreach ($child_menus as $child_menu) {
            $child_count[$child_menu->parent_id][] =  $child_menu;
        }
        $data["submenu_count"] = $child_count;
        $data["detail"] =  $menu_detail;
        return view('accesslevel.list_menu', $data);  
    }
    
    public function delete_menu($id) {
        //get get all data from data base
        $menus  =  DB::table('menus')->select('id', 'parent_id', 'title', 'url')->get(); 
        $datamenu = array();
        $data = array();
        foreach ($menus AS $menu){
            $datamenu['id'] = $menu->id;
            $datamenu['text'] = $menu->title;
            $datamenu['parent_id'] = $menu->parent_id;
            $datamenu['href'] = $menu->url;
            array_push($data, $datamenu); 
        }
        $itemsByReference = array();
        // Build array of item references:
         foreach($data as $key => &$item) {
            $itemsByReference[$item['id']] = &$item;
        }
        // Set items as children of the relevant parent item.
        foreach($data as $key => &$item)  {
            if($item['parent_id'] && isset($itemsByReference[$item['parent_id']])) {
               $itemsByReference [$item['parent_id']]['nodes'][] = &$item;
            }
        } 
        $mynewarray = collect($data)->map(function ($array) use ($id) {
            if($array['id'] == $id)
            {
            return $array;
            }
        });
        $newtest=array();
        foreach($mynewarray as $row)
        {
            if(is_array($row))
            {
                $newtest=$row;
            }
        }
        array_walk_recursive($newtest, function ($v, $k) { 
           if($k=='id')
           {
            DB::table('menus')->where('id', $v)->delete(); 
           }
        });
        DB::table('menus')->where('id', $id)->delete(); 
        Request::session()->flash('flash_message', 'Menu has been Deleted.');
        Request::Session()->flash('alert-class', 'alert-success');
        return redirect('list_menu');  
    }
    public function createPath($id) {
        $query = DB::table('menus')->select('id', 'title','parent_id')->where('id','=',$id)->first();
        $query =(array)$query;
        if($query["parent_id"] == 0) {
            return $query["parent_id"].'|'.$query["title"];
        } else {
            return $this->createPath($query["parent_id"]).'>>'.$query["parent_id"].'|'.$query["title"];
        }
    }
    public function add_group($id = NULL)
    {   
        $data =array();
        
        if (Request::isMethod('post')) {
            $formData = Request::all();
            $branchids = isset($formData['branch']) ? implode(",", $formData['branch']) : "";
            $active_group = isset($formData['active_group']) ? 1 : 0;
            $data = array('desc' => $formData["group_desc"],'status' => $active_group,'branch' => $branchids);
            if ($id == NULL) {
                DB::table('Remit_group')->insertGetId($data);
                Request::session()->flash('flash_message', 'Group has been added.');
            }else{
                DB::table('Remit_group')->where('group_ID', $id)->update($data);
                Request::session()->flash('flash_message', 'Group has been updated.');
            }
            Request::Session()->flash('alert-class', 'alert-success');
            return redirect('list_group');
        }
        if ($id != NULL) {
            $detail_edit_group = DB::table('Remit_group')->where('group_ID', $id)->first(); 
            $branch_id_data = explode(",", $detail_edit_group->branch);
            $data['branch_ids'] = $branch_id_data;
            $data['detail_edit'] = $detail_edit_group;  
        }
        $data['branches']  =  DB::table('t_sysdata')->get(); 
        return view('accesslevel.add_group', $data);
    }

    public function list_group()
    {
        $detail = DB::table('Remit_group')->get();
        return view('accesslevel.list_group', ['group_detail' => $detail]);  
    }

    public function delete_group($id)
    {
        DB::table('Remit_group')->where('group_ID', $id)->delete();
        Request::session()->flash('flash_message', 'Group has been Deleted.');
        Request::Session()->flash('alert-class', 'alert-success');
        return redirect('list_group');  
    }
    public function update_active_group()
    {  
        if (Request::isMethod('post')) {
            $formData = Request::all();
            unset($formData['_token']);
            $data = array('status' => $formData['activevalue']);
            DB::table('Remit_group')->where('group_ID', $formData['id'])->update($data);
        }
    }
    public function list_user()
    {   
        $group  = DB::table('Remit_group')->get();
        foreach($group as $key=>$det){
            $grp_IDs[$det->group_ID]= $det->desc;     
        }
        $data['grp_IDs'] = $grp_IDs;
        
        $detail = DB::table('t_users')->get();
        $data['user_detail'] = $detail;
        return view('accesslevel.list_user',$data);  
    }
    public function add_user($id = NULL)
    {   
        $data =array();
        if (Request::isMethod('post')) {
            $formData      = Request::all();
            if(isset($formData['area_type']) && $formData['area_type'] == "PR"){
                if(!isset($formData['provience_id'])){
                    Request::session()->flash('flash_message', 'Please select atleast one Provience.');
                    return redirect('add_user/'.$id);
                }
            }
            if(isset($formData['area_type']) && $formData['area_type'] == "CT"){
                if(!isset($formData['city_id'])){
                    Request::session()->flash('flash_message', 'Please select atleast one City.');
                    
                    return redirect('add_user/'.$id);
                }
            }
            if(isset($formData['area_type']) && $formData['area_type'] == "BR"){
                if(!isset($formData['branch_id'])){
                    Request::session()->flash('flash_message', 'Please select atleast one Branch.');
                    return redirect('add_user/'.$id);
                }
            }
            $template_ID   = $formData['temp_id'];
            $Area_type     = isset($formData['area_type']) ? $formData['area_type'] : "";
            $groupids      = isset($formData['group']) ? implode(",", $formData['group']) : "";
            $branch_id     = isset($formData['branch_id']) ? implode(",", $formData['branch_id']) : "";
            $city_id       = isset($formData['city_id']) ? implode(",", $formData['city_id']) : "";
            $provience_id  = isset($formData['provience_id']) ? implode(",", $formData['provience_id']) : "";
            
            $data_user_area = array('user_ID' => $id ,'branch' => $branch_id ,'city' => $city_id,'province' => $provience_id);
            $data_sysusers = array('rights_template_id' => $template_ID ,'Area_type' => $Area_type,'group_ID' => $groupids);
            $user_exists = DB::table('user_area')->where('user_ID', $id)->first(); 
            if($user_exists){
                DB::table('t_users')->where('UserID', $id)->update($data_sysusers);
                DB::table('user_area')->where('user_ID', $id)->update($data_user_area);
                Request::session()->flash('flash_message', 'Users has been added.');
            }else{   
                DB::table('t_users')->where('UserID', $id)->update($data_sysusers);
                DB::table('user_area')->insert($data_user_area);
                Request::session()->flash('flash_message', 'Users has been added.');
            }
            Request::Session()->flash('alert-class', 'alert-success');
            return redirect('list_user');
        }
        if ($id != NULL) {
            $detail_edit_sysuser = DB::table('t_users')->where('UserID', $id)->first(); 
            $data['group_ids'] = explode(",", $detail_edit_sysuser->group_ID);
            $data['detail_edit_sysuser'] = $detail_edit_sysuser;  
        }
        $data['group'] = DB::table('Remit_group')->where('status', 1)->get();
        $data['template'] = DB::table('rights_template')->select('template_id', 'description')->get();
        return view('accesslevel.add_user', $data);
    }
    public function provinces($user_id = NULL)
    {   
        $data['province'] = DB::table('t_provinces')->get();
        $detail_edit_user_area = DB::table('user_area')->where('user_ID', $user_id)->first();
        if(isset($detail_edit_user_area->province)){
            $data['province_ids'] = explode(",", $detail_edit_user_area->province);
        }
        return view('accesslevel.provinces', $data);
    }

    public function city($user_id = NULL)
    { 
        $province = DB::table('t_provinces')->get();
        foreach($province as $key=>$det){
            $Prov_IDs[$det->Prov_ID]= $det->Province;     
        }
        $cities = DB::table('t_cities')->orderBy('Prov_ID', 'asc')->get();
        foreach($cities as $key=>$det){
            $c_array[$det->Prov_ID][] =$det->City;  
        } 
        $data['cp_aaray'] =$c_array; 
        $data['province'] = $Prov_IDs;
        $data['cities'] = $cities;
        $detail_edit_user_area = DB::table('user_area')->where('user_ID', $user_id)->first();
        if(isset($detail_edit_user_area->city)){
            $data['city_ids'] = explode(",", $detail_edit_user_area->city);
        }
        return view('accesslevel.city', $data);
    }

    public function branch($user_id = NULL)
    {  
        $branches = DB::table('t_sysdata')->join('t_cities', 't_sysdata.City_ID', '=', 't_cities.City_ID')
            ->join('t_provinces', 't_cities.Prov_ID', '=', 't_provinces.Prov_ID')
            ->select('t_sysdata.Branch','t_sysdata.ShortName', 't_cities.city', 't_provinces.Province', 't_cities.City_ID', 't_provinces.Prov_ID')->orderBy('t_cities.Prov_ID')->orderBy('t_cities.City_ID', 'asc')->get();
        foreach($branches as $key=>$det_branch){
            $city_b_array[$det_branch->City_ID][] =$det_branch->ShortName; 
            $prov_b_array[$det_branch->Prov_ID][] =$det_branch->ShortName; 
        }
        $data['city_b_array'] =$city_b_array;
        $data['prov_b_array'] =$prov_b_array; 
        $data['branches'] = $branches;
        $detail_edit_user_area = DB::table('user_area')->where('user_ID', $user_id)->first();
        if(isset($detail_edit_user_area->branch)){
            $data['Branch_ids'] = explode(",", $detail_edit_user_area->branch);
        }
        return view('accesslevel.branch', $data);
    }
    
}


