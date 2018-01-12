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

          <div class="panel-body" style="margin: 30px 0px;">
            <div class="row" style="margin-bottom: 20px;">
              <form class="form-horizontal">
                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="control-label col-sm-2">
                        P.O#:
                      </label>
                      <div class="col-sm-4">
                        <select name="status" class="form-control" >
                          @foreach($pos as $po)
                            <option value="{{$po->po_no}}" >{{$po->po_no}}</option>
                          @endforeach
                        </select>
                      </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="control-label col-sm-2">
                      D.R#:
                    </label>
                    <div class="col-sm-5">
                      <select name="status" class="form-control" >
                        <option value="all">All</option>
                      </select>
                    </div>

                    <label class="control-label col-sm-1">
                      Date
                    </label>
                    <div class="col-sm-4">
                      <input type="date" class="form-control" name="" id="">
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="control-label col-sm-2">
                      VENDOR
                    </label>
                    <div class="col-sm-10">
                      <select name="status" class="form-control" >
                        <option value=""></option>
                        @foreach($vendors as $vendor)
                          <option  value="{{$vendor->Supp_ID}}">{{$vendor->VendorName}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <a href="{{ route('stocks.create') }}" class="pull-right btn btn-success">
                    Add Row
                    <br>
                    (F2)
                    </a>
                  </div>
                </div>
                
              </form>
            </div>
            <form action="#"></form>
            <table class="table table-striped table-bordered">
              <tbody>
                <tr>
                  <th>Item Code</th>
                  <th>Product Line</th>
                  <th>Brand</th>
                  <th>Description</th>
                  <th>Cost/Unit</th>
                  <th>Served</th>
                  <th>Qty</th>
                  <th>Subtotal</th>
                  <th>Unit</th>
                  <th>Action</th>
                </tr>
                <tr>
                  <td> <input type="text" name="ItemCode" class="form-control"> </td>
                  <td> <input type="text" name="ItemCode" class="form-control"> </td>
                  <td> <input type="text" name="ItemCode" class="form-control"> </td>
                  <td> <input type="text" name="ItemCode" class="form-control"> </td>
                  <td> <input type="text" name="ItemCode" class="form-control"> </td>
                  <td> <input type="text" name="ItemCode" class="form-control"> </td>
                  <td> <input type="text" name="ItemCode" class="form-control"> </td>
                  <td> <input type="text" name="ItemCode" class="form-control"> </td>
                  <td> <input type="text" name="ItemCode" class="form-control"> </td>
                  <td> <input type="text" name="ItemCode" class="form-control"> </td>
                </tr>

              </tbody>
            </table>

            <table class="table table-bordered" style=" " >
              <thead>
                <tr style="display:table;width:99%;table-layout:fixed; background:  #f27b82" >
                  <th>Item Code</th>
                  <th>Product Line</th>
                  <th>Brand</th>
                  <th>Description</th>
                  <th>Unit</th>
                  <th>Unit Cost</th>
                </tr>
              </thead>
              <tbody style="display:block; max-height:300px; overflow-y:scroll; background: #f4b2b6;">
                @foreach($stockitems as $stockitem )
                  <tr style="display:table;width:100%;table-layout:fixed;" >
                    <td>{{$stockitem->ItemCode}}</td>
                    <td>{{$stockitem->product_line->Product}}</td>
                    <td>{{$stockitem->brand->Brand}}</td>
                    <td>{{$stockitem->Description}}</td>
                    <td>{{$stockitem->Unit}}</td>
                    <td>{{$stockitem->LastCost}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <div class="row" style="margin-top: 200px;">
              <div class="col-sm-3 pull-right">
                <h4>
                  <strong>TOTAL AMOUNT:</strong>
                  <span style="color:red">3,700,000</span>
                </h4>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <a type="button" class="btn btn-default" href="{{ route('stocks.show' , [ 'corpID' => $corpID ]) }}">
                  <i class="fa fa-reply"></i> Back
                </a>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-success pull-right save_button">
                  Save
                </button>
              </div>
            </div>

          </div>
        </div>
      </div>
    </div>
</section>
@endsection
