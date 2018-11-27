@extends('layouts.custom')

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-9">
              <h4>New Stock Transfer</h4>
            </div>
          </div>
        </div>
      <form class="form-horizontal submit_form" action="{{ route('stocktransfer.store', [ 'corpID' => $corpID]) }}" method="POST" >
          {{ csrf_field() }}
          <input type="hidden" name="corpID" value="{{$corpID}}" >

          <div class="panel-body" style="margin: 30px 0px;">
              <div class="row" style="border:1px solid lightgray;padding: 7px 7px 0px 7px;">
              <div class="col-xs-4" style="padding-left: 0;">
                <label for="sort" class="col-sm-3 control-label"  style="padding-left: 0;">
                  <strong>Transfer to</strong>
                </label>
                <div class="col-sm-6">
                  <select class="form-control Txfr_To_Branch" name="Txfr_To_Branch" onchange="branchChange()">
                    @foreach($branches as $branch)
                    <option value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
                    @endforeach
                  </select>  
                </div>
              </div>
            
              <div class="col-xs-4">
                <div class="form-group">
                  <label class="control-label col-sm-3"  style="padding-left: 0;">
                    <strong>Date</strong>
                  </label>
                  <div class="col-xs-8">
                    <input type="date" class="form-control" name="Txfr_Date" value="{{date('Y-m-d')}}" >
                  </div>
                </div>
              </div>

              <div class="col-xs-4">
                <div class="form-group">
                  <label class="control-label col-sm-3"   style="padding-left: 0;">
                    <strong>D.R#</strong>
                  </label>
                  <div class="col-xs-8">
                    <input type="text" class="form-control" value="NEW" readonly>
                  </div>
                </div>
              </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6 text-right col-sm-offset-6" style="margin-top: 10px;">
                  <a class="btnAddRow  btn btn-success {{ \Auth::user()->checkAccessByIdForCorp($corpID, 42, 'A') ? "" : "disabled" }} " onclick="openTableStocktransfer(event)" >
                  Add Row
                  <br>
                  (F2)
                  </a>
                </div>
              </div>
              
          @include('stocktransfer.stocktransfer-item')

          <div class="row">
            <div class="col-md-6">
              <a type="button" class="btn btn-default" href="{{ route('stocktransfer.index', [ 'corpID' => $corpID, 'tab' => 'stock']) }}">
              <span style="margin-right: 7px;" class="glyphicon glyphicon-arrow-left"></span>Back
              </a>
            </div>
            <div class="col-md-6">
              <button type="button" data-toggle="modal" class="save_button btn btn-success pull-right btn-save {{ \Auth::user()->checkAccessByIdForCorp($corpID, 42, 'A') ? "" : "disabled" }} " >
                Save
              </button>
            </div>
          </div>
        </div>
      </form>
        
      </div>
    </div>
  </div>
</section>

<div class="modal fade" id="alert" role="dialog" >
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">
          <strong>EDIT DR</strong>
        </h4>
      </div>
      <div class="modal-body">
        <p>Some or all of the items on this DR have been transferred already. You cannot edit or delete this anymore...</p>
      </div>
      <div class="modal-footer" style="margin-top: 100px;">
        <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
      </div>
    </div>
  </div>
</div>
@endsection

@include('stocktransfer.script')