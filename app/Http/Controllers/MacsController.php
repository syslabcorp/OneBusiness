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
            'mac.*.Mac_Address' => 'required_with:is_modify|unique:t_rates,Mac_Address,*,nKey|regex:/^([0-9A-Fa-f]{2}[:-]){5}([0-9A-Fa-f]{2})$/|nullable',
            'mac.*.IP_Addr' => 'required_with:is_modify|regex:/^\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3}\z/|nullable',
            'mac.*.PC_No' => 'max:5'
        ]);
        $macs = $request->get('mac');

        foreach($macs as $key => $macParams)
        {
            if(isset($macParams['is_modify']))
            {
                try
                {
                    $mac = $branch->macs()->where('nKey', '=', $key)->first();
                    $mac->StnType = isset($macParams['StnType']) ? $macParams['StnType'] : 0;
                    $mac->LastChgMAC = \Auth::user()->UserID;
                    $mac->LastChgMACDate = date('Y-m-d H:i:s');
                    $mac->Mac_Address = $macParams['Mac_Address'];
                    $mac->IP_Addr = $macParams['IP_Addr'];
                    $mac->PC_No = $macParams['PC_No'];
                    $mac->save();
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
        
        $mac = $branch->macs()->where('nKey', '=', $request->get('mac_id'))->first();
        if(!empty($request->get('target_id')))
        {
            $temp = $mac->Mac_Address;
            $targetMac = Mac::where('nKey', '=', $request->get('target_id'))->where('Branch', '=', $request->get('branch'))->first();
            $mac->Mac_Address = $targetMac->Mac_Address;
            $mac->save();
            $targetMac->Mac_Address = $temp;
            $targetMac->save();
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
            $mac = $branch->macs()->where('nKey', '=', $request->get('mac_id'))->first();
            if($mac && !empty($request->get('branch_id')))
            {
                $targetMac = Mac::where('nKey', '=', $request->get('target_id'))->where('Branch', '=', $request->get('branch_id'))->first();

                $targetMac->Mac_Address = $mac->Mac_Address;
                $targetMac->LastChgMAC = \Auth::user()->UserID;
                $targetMac->LastChgMACDate = date('Y-m-d H:i:s');
                $targetMac->save();

                $mac->Mac_Address = $request->get('Mac_Address');
                $mac->LastChgMAC = \Auth::user()->UserID;
                $mac->LastChgMACDate = date('Y-m-d H:i:s');
                $mac->save();

                \Session::flash('success', "Station {$mac->PC_No} has been transferred to Branch {$targetMac->branch->ShortName}!");
            }else
            {
                \Session::flash('error', "Station {$mac->PC_No} transfer failed!");
            }
        }

        return redirect(route('branchs.edit', [$branch, '#mac'])); 
    }
}
