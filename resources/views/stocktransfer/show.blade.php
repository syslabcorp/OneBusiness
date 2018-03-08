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


               <!-- www -->
               <section class="content">
                      <!--main content-->
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
                                        <!-- 4 tabs start -->
                                        <div  class="tab-content" style="padding: 1em;">
                                            <!-- first order tab  -->
	                                            <div class="tab-pane fade active in" id="access" >

                                                <div class="col-md-12">

                                                    <div class="row" style="border:1px solid lightgray;padding: 7px 7px 0px 7px;">
                                                        <div class="col-md-4">
                                                            <p>P.O.#: <span>{{$tmaster->po_no}}</span></p>
                                                            <p>P.O.Template: 
                                                            <span>
                                                            @if($tmaster_template)
                                                            {{$tmaster_template->po_tmpl8_desc}}
                                                            @endif
                                                            </span></p>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <p>P.O.Date: <span>{{$tmaster->po_date}}</span></p>
                                                            <p>Total Pieces: <span style="color:red;">dvo-PO1</span></p>
                                                        </div>
                                                        <div class="col-md-4" style="    padding-top: 10px;">
                                                            <button class="btn btn-default" style="width:9em;float:right;">Print</button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12">
                                                        <div class="row">
                                                            <div class="table-responsive">
                                                            <table id="stockDetail" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                                                    <thead>
                                                                        <tr >

                                                                            <th>Item Code</th>
                                                                            <th>Branch_1</th>
                                                                            <th>Branch_2</th>
                                                                            <th  style="color:blue;">TOTAL</th>
                                                                            <th style="color:red;">STOCK</th>
                                                                   
                                                                        </tr>
                                                                    </thead>

                                                                        @foreach($tmaster_detail as $item)
                                                                        <tr  class="editable"  data-id="{{$item->po_no}}">
                                                                            <td  class="edit_ItemCode"  data-field="ItemCode" >{{$item->ItemCode}}</td>
                                                                            <td  class="edit_ItemCode"  data-field="Branch_1" ></td>
                                                                            <td  class="edit_ItemCode"  data-field="Branch_2" ></td>
                                                                            <td>{{$item->Qty}}</td>
                                                                            <td></td>
                                                                            
                                                                        </tr>
                                                                        @endforeach
                                                                    <tbody>
                                                                    
                                                                        
                                                                    </tbody>
                                                                </table>
                                                            </div>   
                                                        </div>

                                                    </div>
	                                            </div>


                                                <div class="col-md-12">
                                                    <div class="row" style="border:1px solid lightgray;padding: 10px 0;">
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
              
                <!-- www -->



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

$(document).ready(function() {

    $('#stockDetail').DataTable({
    "dom": '<"m-t-10"B><"m-t-10 pull-left"><"m-t-10 pull-right">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
   
    });

            $("table tr").editable({

                // enable keyboard support
                keyboard: true,

                // double click to start editing
                dblclick: true,

                // enable edit buttons
                button: true,

                // CSS selector for edit buttons
                buttonSelector: ".edit",

                // uses select dropdown instead of input field
                dropdowns: {},

                // maintains column width when editing
                maintainWidth: true,

                // callbacks for edit, save and cancel actions

                edit: function(values) {
                    // alert('edit');
                },
                save: function(values) {
                    alert('save');
                },
                cancel: function(values) {
                    // alert('cancel');
                }

            });

});


function goBack() {
    window.history.back();
}



function myFunction() {
    confirm("Are you sure you want to transfer these items");
}



</script>



@endsection


