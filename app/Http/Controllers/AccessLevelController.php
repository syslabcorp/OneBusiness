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
	
    public function list_feature()
    {   
        $detailfeature = DB::table('feature_masters')
            ->join('module_masters', 'feature_masters.module_id', '=', 'module_masters.module_id')
            ->select('feature_masters.*', 'module_masters.description')
            ->get();   
        return view('accesslevel.listfeature', ['detailfeature' => $detailfeature]);  
    }
	
    public function destroyfeature($feature_id)
    {
        $fid_array =array();
        $fid_push = array_push($fid_array, $feature_id);
        DB::table('feature_masters')->whereIn('feature_id', $fid_array)->delete();
        DB::table('rights_detail')->whereIn('feature_id', $fid_array)->delete();
        DB::table('rights_dave')->whereIn('feature_id', $fid_array)->delete();
        Request::session()->flash('flash_message', 'Feature has been Deleted.');
        Request::Session()->flash('alert-class', 'alert-success');
        return redirect('list_feature');  
    }

    public function add_template($template_id = NULL)
    {   
        $data =array();
        $data['corporation'] = DB::table('corporation_masters')->select('corp_name', 'corp_id')->get();
        if (Request::isMethod('post')) {
            $formData = Request::all();
			if(!isset($formData['module_id'])){
				Request::session()->flash('flash_message', 'Create a module and features first before you can create a template for this corporation.');
				return redirect('add_template');
			}
            $template_name  = $formData["temp_name"];
            $corporation_id = $formData["corporation_id"];
            $created_at   = date("Y-m-d H:i:s");
            $datatemplate = array(
                'description' => $template_name,
                'corp_id'     =>$corporation_id,
                "created_at"  => date("Y-m-d H:i:s")
            );
            if ($template_id == NULL) {
                $tid = DB::table('rights_template')->insertGetId($datatemplate);
                Request::session()->flash('flash_message', 'Template has been added.');
            }else{
                DB::table('rights_template')->where('template_id', $template_id)->update($datatemplate);
                $tid = $template_id;
                DB::table('rights_mstr')->where('template_id', $template_id)->delete();
                DB::table('rights_detail')->where('template_id', $template_id)->delete();
                DB::table('rights_dave')->where('template_id', $template_id)->delete();
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
                        $access_c = isset($formData['access_'.$module.'_'.$feature.'_c']) ? $formData['access_'.$module.'_'.$feature.'_c'] : '';
                        $access_u = isset($formData['access_'.$module.'_'.$feature.'_u']) ? $formData['access_'.$module.'_'.$feature.'_u'] : '';
                        $access_r = isset($formData['access_'.$module.'_'.$feature.'_r']) ? $formData['access_'.$module.'_'.$feature.'_r'] : '';
                        $access_d = isset($formData['access_'.$module.'_'.$feature.'_d']) ? $formData['access_'.$module.'_'.$feature.'_d'] : '';
                        
                        if($access_c == "" && $access_u == "" && $access_r == "" && $access_d == ""){
                            $access_type = 0;
                        }else{
                            $access_type = $access_c.$access_u.$access_r.$access_d;
                        }
                        $datafeature = array(
                            'module_id'   => $module,
                            'template_id' => $tid,
                            'feature_id'  => $feature,
                            'access_type' => $access_type,
                        );
                        DB::table('rights_detail')->insertGetId($datafeature);
                        $access_c = isset($formData['access_'.$module.'_'.$feature.'_c']) ? '1' : '0';
                        $access_u = isset($formData['access_'.$module.'_'.$feature.'_u']) ? '1' : '0';
                        $access_r = isset($formData['access_'.$module.'_'.$feature.'_r']) ? '1':  '0';
                        $access_d = isset($formData['access_'.$module.'_'.$feature.'_d']) ? '1':  '0';
                        $datadave = array(
                            'template_id'    => $tid,
                            'feature_id'     => $feature,
                            'access_delete'  => $access_d,
                            'access_add'     => $access_c,
                            'access_view'    => $access_r,
                            'access_edit'    => $access_u,
                        ); 
                        DB::table('rights_dave')->insertGetId($datadave);
                    }
                }
            }
            Request::Session()->flash('alert-class', 'alert-success');
            return redirect('list_template');
        }
        if ($template_id != NULL) {
            $data['detail_edit_template'] = DB::table('rights_template')->where('template_id', $template_id)->first();  
        }
        return view('accesslevel.addtemplate', $data);
    }
    public function template_module()
    { 
        $formData = Request::all(); 
        $corp_id     = $formData['corp_id'];
        $data['modules']=DB::table('module_masters')->where('corp_id', '=', $corp_id )->get();
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
        $listtemplate = DB::table('rights_template')
            ->join('corporation_masters', 'rights_template.corp_id', '=', 'corporation_masters.corp_id')
            ->select('rights_template.*', 'corporation_masters.corp_name')
            ->get();  
        return view('accesslevel.list_template', ['listtemp' => $listtemplate]);  
    }
    public function destroytemplate($template_id)
    {
        DB::table('rights_template')->where('template_id', $template_id)->delete();
        DB::table('rights_mstr')->where('template_id', $template_id)->delete();
        DB::table('rights_detail')->where('template_id', $template_id)->delete();
        DB::table('rights_dave')->where('template_id', $template_id)->delete();
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
}


