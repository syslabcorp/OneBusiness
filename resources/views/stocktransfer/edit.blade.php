@extends('layouts.custom')

@section('content')
<section class="content">
  <div class="row">
    <div class="col-md-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-9">
              <h4>Edit Stock Transfer</h4>
            </div>
          </div>
        </div>
      <form class="submit_form form-horizontal" action="{{ route('stocktransfer.update', [$hdrItem, 'corpID' => $corpID]) }}" method="POST">
        <input type="hidden" name="_method" value="PUT">
          {{ csrf_field() }}
          <input type="hidden" name="corpID" value="{{$corpID}}" >

          <div class="panel-body" style="margin: 30px 0px;">
              <div class="row" style="border:1px solid lightgray;padding: 7px 7px 0px 7px;">
              <div class="col-xs-4" style="padding-left: 0;">
                <label for="sort" class="col-sm-3 control-label"  style="padding-left: 0;">
                  <strong>Transfer to</strong>
                </label>
                <div class="col-sm-6">
                  <select class="form-control" name="Txfr_To_Branch" onchange="branchChange()">
                    @foreach($branches as $branch)
                    <option value="{{ $branch->Branch }}"
                      {{ $branch->Branch == $hdrItem->Txfr_To_Branch ? 'selected' : '' }}
                      >{{ $branch->ShortName }}</option>
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
                    <input type="date" class="form-control" name="Txfr_Date" value="{{ $hdrItem->Txfr_Date->format('Y-m-d') }}" >
                  </div>
                </div>
              </div>

              <div class="col-xs-4">
                <div class="form-group">
                  <label class="control-label col-sm-3"   style="padding-left: 0;">
                    <strong>D.R#</strong>
                  </label>
                  <div class="col-xs-8">
                    <input type="text" class="form-control" value="{{ $hdrItem->Txfr_ID }}" readonly>
                  </div>
                </div>
              </div>
              </div>

              <div class="form-group">
                <div class="col-sm-6 text-right col-sm-offset-6" style="margin-top: 10px;">
                  <a class="btnEditRow btn btn-success {{ \Auth::user()->checkAccessByIdForCorp($corpID, 42, 'E') ? "" : "disabled" }} " onclick="openTableStocktransfer(event)" >
                  Add Row
                  <br>
                  (F2)
                  </a>
                </div>
              </div>

          @include('stocktransfer.stocktransfer-item')
          <div class="row">
            <div class="col-md-6">
              <a type="button" class="btn btn-default" href="{{ route('stocktransfer.index', [ 'corpID' => $corpID, 'tab' => 'stock', 'stockStatus' => $stockStatus]) }}">
              <span style="margin-right: 7px;" class="glyphicon glyphicon-arrow-left"></span>Back
              </a>
            </div>
            <div class="col-md-6">
              <button type="button" data-toggle="modal" class="save_button btn btn-success pull-right btn-save {{ \Auth::user()->checkAccessByIdForCorp($corpID, 42, 'E') ? "" : "disabled" }} " >
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

@endsection
@include('stocktransfer.script')