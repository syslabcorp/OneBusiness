<?php

namespace App\Http\Controllers;

use App\Tmaster;

use App\Delivery;

use DB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class StocktransferController extends Controller
{
    public function index()
    {
        // echo "string";

        if(!isset($_GET['status']))
        {
            $status=1;

            
        }
        else{
            $status = $_GET['status'];
        }

    if($status==1)
        {
            $tmaster_data = Tmaster::where('served',0)->limit(100)->get();
        }
        else if($status==2)
        {
            $tmaster_data = Tmaster::where('served',1)->limit(100)->get();
        }
        else if($status==3)
        {
            $tmaster_data = Tmaster::limit(100)->get();
        }


        $delivery_data = Delivery::limit(1000)->get();


        return view('stocktransfer/index',compact(array('tmaster_data','delivery_data','status')));
        
    }

    

    public function  tmasterDetail($id)
    {
        $tmaster = Tmaster::find($id);    

        $tmaster_template = $tmaster->getSpotmpl8hdrData;

        $tmaster_detail  = $tmaster->getTmasterDetailData;    

    //    dd($tmaster_detail);die;

    return view('stocktransfer/show',compact('tmaster','tmaster_detail','tmaster_template'));
   
    }


    public function markToserved($id){

        $tmaster = Tmaster::where( 'po_no',$id)->update([
            'served'=>1
        ]);   

        
        return response()->json(array('msg'=>'success'), 200);   

    }




}
