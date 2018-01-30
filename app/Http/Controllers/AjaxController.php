<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class AjaxController extends Controller
{
    public function fetchBrances ($corp_id = null) {
    	$branches = [];
    	//if(request()->ajax()) {
    	
		if($corp_id) {
			$branches = \App\Branch::where('corp_id', $corp_id)->orderBy('ShortName', 'ASC')->get(['Branch', 'ShortName', 'Active']);
		} else {
			$branches = \App\Branch::orderBy('ShortName', 'ASC')->where('Active', 1)->get(['Branch', 'ShortName']);
		}
    		
    	//}
    	return response()->json($branches);
    }

    public function fetchServicesByBranches ($branch_ids = null) {
    	$branches = [];
    	$services = [];
    	//if(request()->ajax()) {
    	
    	$branchIdsArr = explode(",", $branch_ids);

		if(count($branchIdsArr)) {
			/*$branches = \App\Branch::with(['services' => function($query) {
				return $query->where('Active', 1);
			}])->whereIn('Branch', $branchIdsArr)->get(['Branch', 'ShortName']);*/
			$servicesMaster = DB::table('srv_item_cfg')
						->join('services', 'srv_item_cfg.Serv_ID', '=', 'services.Serv_ID')
						->whereIn('srv_item_cfg.Branch', $branchIdsArr)
						->select('services.Serv_Code','srv_item_cfg.Serv_ID','srv_item_cfg.Branch','srv_item_cfg.Active')
						->get();
			/*$branches = \App\Branch::with('services')->whereIn('Branch', $branchIdsArr)->get(['Branch', 'ShortName']);*/
		} else {
			//$branches = \App\Branch::orderBy('ShortName', 'ASC')->get(['Branch', 'ShortName']);
		}

		$tempServiceIds = [];
		foreach($servicesMaster as $key => $service) {
    		//foreach($branch->services as $key => $service) {
    			if(!in_array($service->Serv_ID, $tempServiceIds)) {
    				$tempServiceIds[] = $service->Serv_ID;

    				$services[$key]['id'] = $service->Serv_ID;
    				$services[$key]['code'] = $service->Serv_Code;
    				$services[$key]['isActive'] = $service->Active;
    			}
    		//}
		}
    	//}
    	return response()->json($services);
    }

    public function fetchServices ($service_id_csv = null) {
        $services = [];
        $serviceIdsArr = [];
        //if(request()->ajax()) {
        
        if($service_id_csv) $serviceIdsArr = explode(",", $service_id_csv);

        if(count($serviceIdsArr)) {
            $servicesList = \App\Service::whereIn('Serv_ID', $serviceIdsArr)->orderBy('Serv_Code', 'ASC')->get(['Serv_ID', 'Serv_Code', 'Description', 'Active']);
            
        } else {
            $servicesList = \App\Service::orderBy('Serv_Code', 'ASC')->get(['Serv_ID', 'Serv_Code', 'Description', 'Active']);
        }

        $activeCounter = 0;
        $inactiveCounter = 0;
        foreach($servicesList as $key => $service) {
            if($service->Active == 1) $activeCounter++;
            else $inactiveCounter++;
        }

        foreach($servicesList as $key => $service) {
            $services[$key]['id'] = $service->Serv_ID;
            $services[$key]['code'] = $service->Serv_Code;
            $services[$key]['description'] = $service->Description;
            $services[$key]['isActive'] = $service->Active;
            $services[$key]['activeCounter'] = $activeCounter;
            $services[$key]['inactiveCounter'] = $inactiveCounter;
        }
        //}
        return response()->json($services);
    }
    
    public function fetchRetailItems($product_id_csv = null) {
        $retailItems = [];
        $productIdsArr = [];
        // if(request()->ajax()) {
            if($product_id_csv) $productIdsArr = explode(",", $product_id_csv);

            if(count($productIdsArr)) {
                $retailItemsList = \App\Inventory::whereIn('Prod_Line', $productIdsArr)->orderBy('ItemCode', 'ASC')->get(['item_id', 'ItemCode', 'Description', 'Active']);
                
            } else {
                $retailItemsList = \App\Inventory::orderBy('ItemCode', 'ASC')->get(['item_id', 'ItemCode', 'Description', 'Active']);
            }

            $activeCounter = 0;
            $inactiveCounter = 0;
            foreach($retailItemsList as $key => $retailItem) {
                if($retailItem->Active == 1) $activeCounter++;
                else $inactiveCounter++;
            }

            foreach($retailItemsList as $key => $retailItem) {
                $retailItems[$key]['id'] = $retailItem->item_id;
                $retailItems[$key]['code'] = $retailItem->ItemCode;
                $retailItems[$key]['description'] = $retailItem->Description;
                $retailItems[$key]['isActive'] = $retailItem->Active;
                $retailItems[$key]['activeCounter'] = $activeCounter;
                $retailItems[$key]['inactiveCounter'] = $inactiveCounter;
            }
        // }
        return response()->json($retailItems);
    }
    
}
