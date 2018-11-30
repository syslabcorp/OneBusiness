@extends('layouts.custom')

@section('content')
<section class="content">
    <div class="row">
      <div class="col-md-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h4>Stock Receiving</h4>
              </div>
              <div class="col-xs-3">

              </div>
            </div>
          </div>
        <form class="form-horizontal submit_form" action="{{ route('stocks.store', [ 'corpID' => $corpID]) }}" method="POST" >
            {{ csrf_field() }}
          <input type="hidden" name="corpID" value="{{$corpID}}" >
          <div class="panel-body" style="margin: 30px 0px;">
            <div class="row" style="margin-bottom: 20px;">
                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="control-label col-sm-2">
                        P.O#:
                      </label>
                      <div class="col-sm-4 error_PO">
                        <select name="po" id="PO" class="form-control PO"  >
                          <option value=""></option>
                          @foreach($pos as $po)
                            <option value="{{$po->po_no}}" >{{$po->po_no}}</option>
                          @endforeach
                        </select>
                      </div>
                  </div>
                </div>

                <div class="form-group ">
                  <div class="col-sm-6">
                    <label class="control-label col-sm-2">
                      D.R#:
                    </label>
                    <div class="col-sm-5 error_DR">
                      <input type="text" data-validation="required,length" data-validation-length="max12" data-validation-error-msg-length="D.R.# should not exceed 12 characters" class="form-control DR" name="RR_No"   >
                    </div>

                    <label class="control-label col-sm-1">
                      Date
                    </label>
                    <div class="col-sm-4">
                      <input type="date" class="form-control" name="RcvDate" id="" value="{{date('Y-m-d')}}" >
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="control-label col-sm-2">
                      VENDOR
                    </label>
                    <div class="col-sm-10">
                      <select name="Supp_ID" class="form-control" >
                        @foreach($vendors as $vendor)
                          <option value="{{$vendor->Supp_ID}}">{{$vendor->VendorName}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <a href="javascript:void(0)" class="addHere btnAddRow pull-right btn btn-success {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'A') ? "" : "disabled" }} " onclick="openTableStock(event)">
                    Add Row
                    <br>
                    (F2)
                    </a>
                  </div>
                </div>
                
            </div>
            

            @include('stocks.stocks-item')
            
            <div class="row" style="margin-top: 200px;">
              <div class="col-sm-3 pull-right">
                <h4>
                  <strong>TOTAL AMOUNT:</strong>
                  <span id="total_amount" style="color:red">0.00</span>
                  <input type="hidden" name="total_amt" id="total_amt">
                </h4>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <a type="button" class="btn btn-default" href="{{ route('stocks.index', [ 'corpID' => $corpID]) }}">
                  <i class="fa fa-reply"></i> Back
                </a>
              </div>
              <div class="col-md-6">
                <button type="button" data-toggle="modal" class="btn btn-success pull-right save_button {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'A') ? "" : "disabled" }} " >
                  Save
                </button>
              </div>
            </div>
            
            
            </div>
                <!-- Modal alert -->
                <div class="modal fade" id="confirm_save" role="dialog">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">
                          <strong>Confirm Save</strong>
                        </h4>
                      </div>
                      <div class="modal-body">
                        <p> Are you sure you want to save? </p>
                        <div class="checkbox">
                          <label> <input type="checkbox" name="PrintRR" id=""> Print RR Stub </label>
                        </div>
                      </div>
                      <div class="modal-footer" style="margin-top: 100px;">
                        <div class="col-md-6">
                          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                            <i class="fa fa-reply"></i> Back  
                          </button>
                        </div>
                        <div class="col-md-6">
                          <button class="btn btn-primary" id="submit-form" type="submit">Save</button>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- End modal alert -->
          </div>
        </form>
          
        </div>
      </div>
    </div>
</section>

<!-- Modal alert -->
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
<!-- End modal alert -->

@endsection

@include('stocks.script')