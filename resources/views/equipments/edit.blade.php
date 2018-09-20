@extends('layouts.custom')

@section('content')
  <div class="box-content equipmentPage">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h5>Equipment Inventory</h5>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="bs-example">
              <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                <li class="{{ $tab == 'auto' ? 'active' : '' }}">
                  <a href="#equipDetail" data-toggle="tab">Equipment Detail</a>
                </li>
                <li class="{{ $tab == 'history' ? 'active' : '' }}">
                  <a href="#equipHistory" data-toggle="tab">History</a>
                </li>
              </ul>
              <div class="tab-content editEquipment" style="padding: 1em;">
                @include('equipments.detail-tab')
                @include('equipments.history')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@include('equipments.script')