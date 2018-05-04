<?php
use App\Spodetail;
use App\Srcvdetail;
?>
@extends('layouts.app')

@section('header_styles')
	<link href="{{ asset('css/my.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
<div class="container-fluid">
<div class="row">
    
<div id="togle-sidebar-sec" class="active">
      
      <!-- Sidebar -->
       <div id="sidebar-togle-sidebar-sec">
      <ul id="sidebar_menu" class="sidebar-nav">
           <li class="sidebar-brand"><a id="menu-toggle" href="#">Menu<span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
      </ul>
        <div class="sidebar-nav" id="sidebar">     
          <div id="treeview_json"></div>
        </div>
      </div>
          
      <!-- Page content -->
      <div id="page-content-togle-sidebar-sec">
		@if(Session::has('alert-class'))
			<div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@elseif(Session::has('flash_message'))
			<div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@endif
             <div class="col-md-12">
			 <h3 class="text-center">Stock Transfer</h3>
    	<div class="row">    
        <div class="panel panel-default">
          <div class="panel-heading">
            Stock Transfer
          </div>
          <div class="panel-body">
            <section class="content">
              <div class="row">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-body">
                        <div class="bs-example">
                            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                              <li class="active">
                                <a href="#access" data-toggle="tab">Auto stock transfer</a>
                              </li>
                              <li>
                                <a href="#tasks" data-toggle="tab">Stock Delivery</a>
                              </li>
                            </ul>
                            <div  class="tab-content">
                              <div class="tab-pane fade active in" id="access" >
                                <div class="col-md-12">
                                  <div class="row" style="border:1px solid lightgray;padding: 7px 7px 0px 7px;">
                                    <div class="col-md-4">
                                      <p> P.O.#:
                                        <span> {{ $stockItem->po_no}} </span>
                                      </p>
                                      <p> P.O.Template: 
                                        <span>
                                          {{ $stockItem->template ? $stockItem->template->po_tmpl8_desc : '' }}
                                        </span>
                                      </p>
                                    </div>
                                    <div class="col-md-4">
                                      <p>P.O.Date: <span> {{ $stockItem->po_date }} </span></p>
                                      <p>Total Pieces: 
                                        <span style="color:red;">
                                          {{ $stockItem->items()->whereIn('Branch', $branches->pluck('Branch'))->sum('Qty') }}
                                        </span>
                                      </p>
                                    </div>
                                    <div class="col-md-4" style=" padding-top: 10px;">
                                      <button class="btn btn-default" style="width:9em;float:right;">Print</button>
                                    </div>
                                  </div>
                                </div>
                                    <form class="form-horizontal has-validation-callback" action="#" id="formTable" method="POST" >
                                      {{ csrf_field() }}
                                    <div class="col-md-12" style="margin-top: 10px;">
                                      <div class="row">
                                          <div class="table-responsive">
                                            <table id="table_editable_1" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                              <thead>
                                                <tr>
                                                  <th>Item Code</th>
                                                  @foreach($branches as $branch)
                                                    <th>{{ $branch->ShortName }}</th>
                                                  @endforeach
                                                  <th  style="color:blue; width:15%;">TOTAL</th>
                                                  <th style="color:red;">STOCK</th>
                                                </tr>
                                              </thead>
                                              <tbody>
                                                @foreach($itemRows as $row)
                                                <tr>
                                                  <td>{{ $row->first()->ItemCode }}</td>
                                                  @foreach($branches as $branch)
                                                    <td>{{ $row->where('Branch', $branch->Branch)->first()['Qty'] }}</td>
                                                  @endforeach
                                                  <td>{{ $row->sum('Qty') }}</td>
                                                  <td>{{ $row->first()->rcvDetails()->sum('Bal') }}</td>
                                                </tr>
                                                @endforeach
                                              </tbody>
                                            </table>
                                          </div>
                                      </div>

                                    </div>
                                  </div>
                                    </form>

                                    <div class="col-md-12">
                                      <div class="row">
                                        <div class="col-md-6">
                                          <button class="btn btn-default" style="width:8em;"  onclick="goBack()"><span style="    margin-right: 7px;" class="glyphicon glyphicon-arrow-left"></span>Back</button>
                                        </div>

                                        <div class="col-md-6">
                                            <button onclick="myFunction()" class="btn btn-info" style="background-color:green;width:9em;float:right;" >Transfer Stocks</button>
                                        </div>
                                      </div>
                                    </div>



                                    <!-- second product tab -->
                                  <div class="tab-pane fade " id="tasks">

                                            <!--  -->
                                                <div class="row">
                                                    <div class="table-responsive">
                                                    <table id="list_menu_delivery" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                                        <thead>
                                                            <tr>
                                                                <th>D.R.No</th>
                                                                <th>Date</th>
                                                                <th>Destination</th>
                                                                <th>Rcvd</th>
                                                                <th>Uploaded</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                    
                                                        <tbody >
                                                                
                                                        
                                                                
                                                        </tbody>

                                                        </table>
                                                    </div>   
                                                </div>
                                        </div>
                                            <!--  -->

                                    </div>
                                  
                                

                            </div>
                                    <!-- 4 tabs end -->

                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
</div>
</div>
</div>
</div>
</div>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/table-edits.min.js"></script>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/momentjs.min.js"></script>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/bootstrap-datetimepicker.min.js"></script>
<script>
 changedRowArr = [];


$(document).ready(function() {

    $('#stockDetail').DataTable({
    "dom": '<"m-t-10"B><"m-t-10 pull-left"><"m-t-10 pull-right">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
   
    });

            // $("table tr").editable({

            //     // enable keyboard support
            //     keyboard: true,

            //     // double click to start editing
            //     dblclick: true,

            //     // enable edit buttons
            //     button: true,

            //     // CSS selector for edit buttons
            //     buttonSelector: ".edit",

            //     // uses select dropdown instead of input field
            //     dropdowns: {},

            //     // maintains column width when editing
            //     maintainWidth: true,

            //     // callbacks for edit, save and cancel actions

            //     edit: function(values) {
            //         // alert('edit');
            //     },
            //     save: function(values) {
            //         alert('save');
            //     },
            //     cancel: function(values) {
            //         // alert('cancel');
            //     }

            // });

});


function keyupInput(rowNo,count){

    for( var i = 1; i <=count ; i ++) {
     if( !( $('#item_'+rowNo+'_'+i).find('.input_Cost').val().match(/^-?\d+(?:[.]\d*?)?$/) ) ){

        if($('#item_'+rowNo+'_'+i).find('.input_Cost').val()!=''){
            $('#item_'+rowNo+'_'+i).find('.error'+i).show();
         }
       
        $('#editIcon'+rowNo).find('.edit').attr('disabled', "");
        }
        else{
            if($('#item_'+rowNo+'_'+i).find('.input_Cost').val()!=''){
                $('#item_'+rowNo+'_'+i).find('.error'+i).hide();
            }
        // $('#item_'+rowNo+'_'+i).find('.edit').removeAttr('disabled');
        $('#editIcon'+rowNo).find('.edit').removeAttr('disabled');
        }
    }
}

function editRow(rowNo,count,BranchID){

    if($('#item_'+rowNo+'_1').parents('.editable').find('.glyphicon').hasClass('glyphicon-pencil'))
    {
        $('#item_'+rowNo+'_1').parents('.editable').find('.glyphicon').removeClass('glyphicon-pencil').addClass('glyphicon-ok');
    
        for ( var i = 1; i < count ; i ++){
            if($('#item_'+rowNo+'_'+i).find('span:first').text()!=''){
                console.log($('#item_'+rowNo+'_'+i).find('span:first').text());
            $('#item_'+rowNo+'_'+i).find( ".input_Cost" ).val($('#item_'+rowNo+'_'+i).find('.value_Cost').text().replace(',', '')).attr("type", "text") ;
            $('#item_'+rowNo+'_'+i).find('.value_Cost').text("");
            }

        }
    }
    else
    {
        $('#item_'+rowNo+'_1').parents('.editable').find('.glyphicon').removeClass('glyphicon-ok').addClass('glyphicon-pencil');
        
        for ( var i = 1; i <count ; i ++)
        {
            rowVal = [];
            branchID = [];

            if($('#item_'+rowNo+'_'+i).find( ".input_Cost" ).val()!="")
                {
                    console.log($('#item_'+rowNo+'_'+i).find( ".input_Cost" ).val());
                    branchID[i] = $('#item_'+rowNo+'_'+i).parents('.editable').find(".branchID_"+i).text();
                  
                    itemCode = $('#item_'+rowNo+'_1').parents('.editable').find(".input_ItemCode_"+rowNo ).text();

                    itemID = $('#item_'+rowNo+'_1').parents('.editable').find(".itemID_"+rowNo).text();
                      
                    rowVal[i] = parseFloat($('#item_'+rowNo+'_'+i).find( ".input_Cost" ).val());

                    $('#item_'+rowNo+'_'+i).find('.value_Cost').text( rowVal[i]);

                    changedRowArr.push({
                                rowVal:rowVal[i],
                                branchID:branchID[i],
                                itemCode:itemCode,
                                itemID:itemID
                    });

                }
            else
            {
                if($('#item_'+rowNo+'_'+i).find('span:first').text()!=''){
                    $('#item_'+rowNo+'_'+i).find('.value_Cost').text('0');
                }
            }

            $('#item_'+rowNo+'_'+i).find( ".input_Cost" ).attr("type", "hidden");
        }

    }


}

function goBack() {
    window.history.back();
}



function myFunction() {

    var j = 0;
    for(var i = 0;i<changedRowArr.length;i++){
        console.log(changedRowArr[i].itemCode)

        if(changedRowArr.slice(i + 1 - j).filter(f=>f.itemCode == changedRowArr[i].itemCode && f.branchID == changedRowArr[i].branchID ).length > 0)
            changedRowArr.splice(i - j ++, 1);
    }
 
    var r = confirm("Are you sure you want to transfer these items");
    if (r == true) {
        if(changedRowArr.length==0){
            alert('there is no item to transfer')
        }
        else {
                $.ajax({
            url: ajax_url+'/saveRowPO',
            data: {changeRowArr:changedRowArr},
            type: "POST",
            async: false,
            success: function(response){
                alert(response.success)
            }
        });
        }
    } else {
       return false;
    }

    
    


}



</script>



@endsection


