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
        <div id="sidebar_menu" class="sidebar-nav">
          <ul></ul>
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
                                      	</ul>
                                        <div  class="tab-content">
                                          <div class="tab-pane fade active in" id="access" >
                                            <div class="col-md-12">
                                              <div class="row" style="border:1px solid lightgray;padding: 7px 7px 0px 7px;">
                                                <div class="col-md-4">
                                                    <p>P.O.#: <span>{{$stockItem->po_no}}</span></p>
                                                    <p>P.O.Template: 
                                                    <span>
                                                    {{ $stockItem->template ? $stockItem->template->po_tmpl8_desc : ''}}
                                                    </span></p>
                                                    <p>P.O.Date: <span>{{ $stockItem->po_date }}</span></p>
                                                </div>
                                                <div class="col-md-4">
                                                  <p style="color:red;">*THIS IS ORIGINAL P.O.</p>
                                                  <p>Total Pieces: 
                                                    <span style="color:red;">
                                                      {{ $stockItem->tot_pcs }}
                                                    </span>
                                                  </p>
                                                  <p>Total Amount: 
                                                    <span style="color:red;">
                                                      {{ number_format($stockItem->total_amt, 2) }}
                                                    </span>
                                                  </p>
                                                </div>
                                                <div class="col-md-4" style="    padding-top: 10px;">
                                                    <button class="btn btn-default" style="width:9em;float:right;">Print</button>
                                                </div>
                                              </div>
                                            </div>
                                            <form class="form-horizontal has-validation-callback" action="#" method="POST" >
                                                 {{ csrf_field() }}
                                                <div class="col-md-12" style="margin-top: 10px;">
                                                        <div class="row">
                                                            <div class="table-responsive">
                                                                 <table id="table_editable_1" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                                                    <thead>
                                                                      <tr>
                                                                        <th style="min-width: 150px;">Item Code</th>
                                                                        @foreach($branches as $branch)
                                                                          <th class="text-center" style="width: 250px; min-width: 150px">
                                                                            {{$branch->ShortName}}
                                                                          </th>
                                                                        @endforeach
                                                                        <th class="text-center" style="color:blue;min-width: 100px;">TOTAL</th>
                                                                        <th class="text-center" style="color:red;min-width: 100px;">STOCK</th>
                                                                      </tr>
                                                                    </thead>

                                                                        
                                                                    <tbody> 
                                                                      @foreach($itemRows as $row)
                                                                      <tr class="editable">
                                                                        <td  class="input_ItemCode">{{ $row->first()->ItemCode }}</td>
                                                                        @foreach($branches as $branch)
                                                                        <td class="text-center">
                                                                          {{ $row->where('Branch', $branch->Branch)->first()['Qty'] }}
                                                                        </td>
                                                                        @endforeach
                                                                        <td class="text-center">{{ $row->sum('Qty') }}</td>
                                                                        <td class="text-center">
                                                                          {{ $row->first()->rcvDetails()->sum('Bal') }}
                                                                        </td>
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
                                                  <div class="rown">
                                                    <div class="col-md-6">
                                                      <a class="btn btn-default" style="width:8em;"
                                                        href="{{ route('stocktransfer.index', ['corpID' => $corpID, 'tab' => 'auto']) }}">
                                                        <span style="margin-right: 7px;" class="glyphicon glyphicon-arrow-left"></span>
                                                        Back
                                                      </a>
                                                    </div>
                                                  </div>
                                                </div>

	                                            <div class="tab-pane fade " id="tasks">
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
  $('table tbody tr td').each(function(el) {
    if($.isNumeric($(this).text()) && parseInt($(this).text()) == 0) {
      $(this).css('color', '#f44336');
    }
  });

  function goBack() {
    window.history.back();
  }
});

</script>



@endsection


