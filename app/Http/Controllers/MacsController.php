<?php

namespace App\Http\Controllers;

use App\Mac;
use App\Branch;
use Illuminate\Http\Request;
use Validator;

class MacsController extends Controller
{

    public function store(Request $request, Branch $branch)
    {
        if(!\Auth::user()->checkAccess("MAC Addresses", "E"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect(route('branchs.index')); 
        }

        $this->validate($request, [
            'mac.*.mac_address' => 'required_with:is_modify|unique:t_rates,mac_address,*|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/|nullable'
        ]);
        $macs = $request->get('mac');

        foreach($macs as $key => $macParams)
        {
            if(isset($macParams['is_modify']))
            {
                try
                {
                    $mac = Mac::find($key);
                    $macParams['stn_type'] = isset($macParams['stn_type']) ? $macParams['stn_type'] : 0;
                    $macParams['last_changed_by'] = \Auth::user()->UserID;
                    $macParams['last_changed_at'] = date('Y-m-d H:i:s');
                    $mac->update($macParams);
                }catch(Exception $e)
                {
                    continue;
                }
            }
        }

        \Session::flash('success', "MAC Addresses has been updated.");
        return redirect(route('branchs.edit', [$branch, '#mac']));
    }

    public function swap(Request $request, Branch $branch)
    {
        if(!\Auth::user()->checkAccess("Swap", "E"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect(route('branchs.index')); 
        }
        
        $mac = Mac::find($request->get('mac_id'));
        if(!empty($request->get('target_id')))
        {
            $temp = $mac->mac_address;
            $targetMac = Mac::find($request->get('target_id'));
            $mac->update(['mac_address' => $targetMac->mac_address]);
            $targetMac->update(['mac_address' => $temp]);
            \Session::flash('success', "Station {$mac->pc_no} has been swap to station {$targetMac->pc_no}.");
        }else
        {
            \Session::flash('error', "Station {$mac->pc_no} swap failed!");
        }
        
        return redirect(route('branchs.edit', [$branch, '#mac'])); 
    }

    public function transfer(Request $request, Branch $branch)
    {
        if(!\Auth::user()->checkAccess("Transfer", "E"))
        {
            \Session::flash('error', "You don't have permission"); 
            return redirect(route('branchs.index')); 
        }

        $validator = Validator::make($request->all(), [
            'mac_address' => 'unique:t_rates|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/|nullable'
        ]);

        if($validator->fails())
        {
            \Session::flash('error', "Mac Address format is invalid or already been taken");
        }else
        {
            $mac = Mac::find($request->get('mac_id'));
            if($mac && !empty($request->get('branch_id')))
            {
                $targetMac  = Mac::find($request->get('target_id'));
                $targetMac->update([
                    'mac_address' => $mac->mac_address,
                    'last_changed_by' => \Auth::user()->UserID,
                    'last_changed_at' => date('Y-m-d H:i:s'),
                ]);

                $mac->update([
                    'mac_address' => $request->get('mac_address'),
                    'last_changed_by' => \Auth::user()->UserID,
                    'last_changed_at' => date('Y-m-d H:i:s'),
                ]);
                \Session::flash('success', "Station {$mac->pc_no} has been transferred to Branch {$targetMac->branch->branch_name}!");
            }else
            {
                \Session::flash('error', "Station {$mac->pc_no} transfer failed!");
            }
        }

        return redirect(route('branchs.edit', [$branch, '#mac'])); 
    }
}
