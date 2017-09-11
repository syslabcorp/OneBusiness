<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Request;
use App\Branch;
use DB;
use URL;


class LocationsController extends Controller
{
    //
public function __construct()
    {
         $this->middleware('auth');
    }


 public function index(Request $request)
    {

        if(!\Auth::user()->checkAccess("Locations", "V"))
        {
            \Session::flash('error', "You don't have permission..."); 
        
            return redirect("/home"); 
        }

        return self::list_provinces(); //just call the function that displays stuff
    }

//--------------------------------------ADDING PROVINCE TO DB
 public function add_province(Request $request,$prov_id = NULL)
    {
      if (Request::isMethod('post')) {

			  $formData = Request::all();
			 // $created_at   = date("Y-m-d H:i:s");
				  $data = array('Province' => $formData["Province_name"]);
				  if ($prov_id == NULL) {
					DB::table('t_provinces')->insertGetId($data);
					 \Session::flash('flash_message', 'Province has been added.');
				  }
				  else{
					DB::table('t_provinces')->where('Prov_id', $prov_id)->update($data);
					\Session::flash('flash_message', 'Province has been updated.');
				  }
				   \Session::flash('alert-class', 'alert-success');
				  return redirect('settings');
				}

    $data =array();

    if ($prov_id != NULL) {
      $data['detail_edit'] = DB::table('t_provinces')->where('Prov_ID', $prov_id)->first(); 
    }
    return view('pages_settings.form_add_province',$data);
    }





//-----------------------LISTING QUERIES
   public function list_provinces()
    {
      	$detail = DB::table('t_provinces')->get();
        return view('pages_settings.list_provinces', ['provs' => $detail]); 

    }

   public function list_cities($prov_id = NULL){
    
	    if($prov_id == NULL){
		   $res_cities = DB::table('t_cities')->get();

	    }else{
	       $res_cities = DB::table('t_cities')->select('t_cities.*')->where('t_cities.Prov_ID',$prov_id)->get();
	    }
	
	   return view('pages_settings.list_cities',['cities' => $res_cities,'prov_id' => $prov_id]);
		//return view('pages_settings.list_cities', ['cities' => $res_cities, 'prov_id' => $prov_id]);  
   }
   
    public function add_city($city_id = NULL, $prov_id=NULL)
    {
		$data = array();
		$data['province'] = DB::table('t_provinces')->select('Prov_ID','Province')->get();
		  if (Request::isMethod('post')) {
			  
			$formData = Request::all();
            $created_at   = date("Y-m-d H:i:s");
            $data = array(
                'Prov_ID' => $formData["Prov_ID"],
                'City' =>$formData["city_name"],
                
            );
            if ($city_id == NULL || $city_id == 0) {
                DB::table('t_cities')->insertGetId($data);
                Request::session()->flash('flash_message', 'City has been added.');
            }else{
                DB::table('t_cities')->where('City_ID', $city_id)->update($data);
                Request::session()->flash('flash_message', 'City has been updated.');
            }
            Request::Session()->flash('alert-class', 'alert-success');
			
			if($prov_id == NULL){
				return redirect('view_cities');
			}else{
				return redirect('view_cities/'.$prov_id);
			}
			 
		  }
		  
		  
		  if ($city_id != NULL && $city_id != 0) {
            $data['detail_edit_city'] = DB::table('t_cities')->where('City_ID', $city_id)->first();    
			}else if($prov_id != NULL){
				$default_prov['Prov_ID'] = $prov_id;
				$data['detail_edit_city'] = (object) $default_prov;
			}
		    return view('pages_settings.form_add_city', $data);
    }


}

