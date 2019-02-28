@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h5><strong>Purchase Request {{ $purchase->flag == 2 ? '( Requests )': $purchase->flag == 4 ? '( Disapproved )' : $purchase->flag == 6 ? '( Approved )' : '' }}</strong></h5>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="bs-example">
              <div class="tab-content" style="padding: 1em;">
                @include('purchases.detailMarkForPO')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

@endsection

@include('purchases.script')