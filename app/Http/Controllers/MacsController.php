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
            'mac.*.Mac_Address' => 'required_with:is_modify|unique:t_rates,Mac_Address,*,txn_id|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/|nullable'
        ]);
        $macs = $request->get('mac');

        foreach($macs as $key => $macParams)
        {
            if(isset($macParams['is_modify']))
            {
                try
                {
                    $mac = Mac::find($key);
                    $macParams['StnType'] = isset($macParams['StnType']) ? $macParams['StnType'] : 0;
                    $macParams['LastChgMAC'] = \Auth::user()->UserID;
                    $macParams['LastChgMACDate'] = date('Y-m-d H:i:s');
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
            $temp = $mac->Mac_Address;
            $targetMac = Mac::find($request->get('target_id'));
            $mac->update(['Mac_Address' => $targetMac->Mac_Address]);
            $targetMac->update(['Mac_Address' => $temp]);
            \Session::flash('success', "Station {$mac->PC_No} has been swap to station {$targetMac->PC_No}.");
        }else
        {
            \Session::flash('error', "Station {$mac->PC_No} swap failed!");
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
            'Mac_Address' => 'unique:t_rates|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/|nullable'
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
                    'Mac_Address' => $mac->Mac_Address,
                    'LastChgMAC' => \Auth::user()->UserID,
                    'LastChgMACDate' => date('Y-m-d H:i:s'),
                ]);

                $mac->update([
                    'Mac_Address' => $request->get('Mac_Address'),
                    'LastChgMAC' => \Auth::user()->UserID,
                    'LastChgMACDate' => date('Y-m-d H:i:s'),
                ]);
                \Session::flash('success', "Station {$mac->PC_No} has been transferred to Branch {$targetMac->branch->ShortName}!");
            }else
            {
                \Session::flash('error', "Station {$mac->PC_No} transfer failed!");
            }
        }

        return redirect(route('branchs.edit', [$branch, '#mac'])); 
    }
}
