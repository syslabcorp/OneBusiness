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
        $get_template_id = DB::table('rights_template')->select('template_id')->where('corp_id', $corp_id)->get();
            foreach ($get_template_id as $temp_id) {
                $templat_id=$temp_id->template_id;
                $temp_array =array();
                $temp_push =array_push($temp_array, $templat_id);
                DB::table('rights_template')->whereIn('template_id', $temp_array)->delete();
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

    public function add_feature($feature_id = NULL)
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
            if ($feature_id == NULL) {
                DB::table('feature_masters')->insertGetId($data);
                Request::session()->flash('flash_message', 'Feature has been added.');
            }else{
                DB::table('feature_masters')->where('feature_id', $feature_id)->update($data);
                Request::session()->flash('flash_message', 'Feature has been updated.');
            }
            Request::Session()->flash('alert-class', 'alert-success');
            return redirect('list_feature');
        }
        if ($feature_id != NULL) {
            $data['detail_edit_feature'] = DB::table('feature_masters')->where('feature_id', $feature_id)->first();    
        }
        return view('accesslevel.addfeature', $data);
    }
	
    public function list_feature($module_id = NULL)
    {
		if($module_id == NULL){
			$detailfeature = DB::table('feature_masters')->join('module_masters', 'feature_masters.module_id', '=', 'module_masters.module_id')->select('feature_masters.*', 'module_masters.description')->get();   
		}else{
			$detailfeature = DB::table('feature_masters')->join('module_masters', 'feature_masters.module_id', '=', 'module_masters.module_id')->select('feature_masters.*', 'module_masters.description')->where('module_masters.module_id', $module_id)->get();   
		}
        return view('accesslevel.listfeature', ['detailfeature' => $detailfeature]);  
    }
	
    public function destroyfeature($feature_id)
    {
        $fid_array =array();
        $fid_push = array_push($fid_array, $feature_id);
        DB::table('feature_masters')->whereIn('feature_id', $fid_array)->delete();
        DB::table('rights_detail')->whereIn('feature_id', $fid_array)->delete();
        Request::session()->flash('flash_message', 'Feature has been Deleted.');
        Request::Session()->flash('alert-class', 'alert-success');
        return redirect('list_feature');  
    }

    public function add_template($template_id = NULL)
    {   
        $data =array();
        $data['menus'] = DB::table('menus')->select('id','title')->get();
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
        $data['modules']=DB::table('module_masters')->get();
        foreach ($data['modules'] as $modul) {
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
	
    public function list_menu($parent_id = 0)
    {   
        if (Request::isMethod('post')){
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
}


