@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h5>Add Equipment</h5>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="bs-example">
              <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                <li class="{{ $tab == 'auto' ? 'active' : '' }}">
                  <a href="#personInfo" data-toggle="tab">Equipment Detail</a>
                </li>
                <li class="{{ $tab == 'doc' ? 'active' : '' }}">
                  <a href="#document" data-toggle="tab">Request</a>
                </li>
                <li class="{{ $tab == 'shortages' ? 'active' : '' }}">
                  <a href="#shortages" data-toggle="tab">History</a>
                </li>
                <li class="{{ $tab == 'tardiness' ? 'active' : '' }}">
                  <a href="#tardiness" data-toggle="tab">Financials</a>
                </li>
              </ul>
              <div class="tab-content" style="padding: 1em;">
                @include('equipments.detail-tab')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection