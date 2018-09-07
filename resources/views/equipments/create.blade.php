@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h5>Equipment Inventory</h5>
              </div>
              <div class="col-xs-3 text-right" style="margin-top: 10px;">
                <a href="{{ route('equipments.create') }}">Add Item</a>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="bs-example">
              <div  id="tablescroll" class="tablescroll">
                <div class="table-wrap">
                  <table class="stripe table table-bordered nowrap table-equipments" width="100%">
                    <thead>
                      <tr>
                        <th>Asset No.</th>
                        <th>Equipment</th>
                        <th>Type</th>
                        <th>Branch</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Qty</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody >
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection