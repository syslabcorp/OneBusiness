@extends('layouts.custom')

@section('content')

<!-- Page content -->
<section class="content">
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <div class="row">
          <div class="col-xs-9">
            <h4>Retail Order Template</h4>
            
          </div>
          <div class="col-xs-3">

          </div>
        </div>
      </div>
      <div class="panel-body manual" style="margin: 30px 0px;">
        <div class="row purchase_menu" style="margin-bottom: 20px;">
            <ul class="purchase_order_style navbar-nav" >
                <li class="active">
                    <a>Manual P.O.</a>
                </li>

                <li class="">
                    <a href="{{route('purchase_order.create_automate',['corpID' => $corpID]) }}">Auto-generate P.O.</a>
                </li>
                <li class="last_item"></li>
            </ul>
        </div>

        <div class="row purchase_header_form">
          <div class="row">
            <div class="col-md-12">
              <form class="form-inline" >
                <div id="city-list" class="form-group">
                  <label>City</label>
                  <select class="form-control" style="width: 300px;" name="" id="dropdown_city_list">
                    @foreach($cities as $city)
                      <option value="{{$city->City_ID}}">{{$city->City}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="checkbox">
                  <label><input type="checkbox" id="all_cities_checkbox"> All Cities</label>
                </div>
              </form>
            </div>
          </div>
        </div>
        <div class="row purchase_choose">
          <form action="{{ route('purchase_order.manual_suggest',['corpID' => $corpID]) }}" id="manualform">
            <input type="hidden" name="corpID" value="{{$corpID}}">
            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h5>Branch</h5>
                </div>

                <div class="panel-body first">
                  <div>
                    <ul class="selectable" id="branch">
                      
                    </ul>

                  </div>

                </div>
              </div>

                <div class="form-group">
                  <div class="col-md-4">
                    <label class="checkbox-inline">
                      <input type="checkbox" id="NX" name="branch_type" value="NX" checked >NetExpress
                    </label>
                  </div>
                  <div class="col-md-4">
                    <label class="checkbox-inline">
                      <input type="checkbox" id="SQ" name="branch_type" value="SQ" checked>Sequel
                    </label>
                  </div>
                  <div class="col-md-4">
                    <label class="checkbox-inline">
                      <input type="checkbox" id="IS" name="branch_type" value="IS" checked>iSing
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label>Date Range</label>
                  
                <div class="row">
                  <div class="col-md-6">
                    <label for="">From</label>
                    <input type="date" name="from_date" id="from_date" class="datepicker" >
                  </div>

                  <div class="col-md-6">
                    <label for="">To</label>
                    <input type="date" name="to_date" id="to_date" class="datepicker">
                  </div>
                </div>

                </div>
                <div class="form-group">
                  <div class="col-md-3">
                    <label class="">Multiplier</label>
                  </div>
                  <div class="col-md-9">
                    <input class="form-control" name="multiolier" id="multiolier" type="text">
                  </div>
                </div>
            </div>

            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h5>Product Line</h5>
                </div>

                <div class="panel-body">
                  <ul>
                    @foreach($prodlines as $prodline)
                      <li class="ui-widget-content">
                        <div class="col-xs-2 text-center" style="border-right: 1px solid #aaaaaa">
                          <input type="checkbox" class="prodline_item" value="{{$prodline->ProdLine_ID}}">
                        </div>
                        <div class="col-xs-10" style="white-space:nowrap;">
                          <label class="label-control">{{ $prodline->Product }}</label> 
                        </div>
                      </li>
                    @endforeach
                  </ul>
                </div>
              </div>
            </div>

            <div class="col-md-4">
              <div class="panel panel-default">
                <div class="panel-heading">
                  <h5>Item Code</h5>
                </div>

                <div class="panel-body">
                  <ul class="selectable" id="item_code">
                  </ul>
                </div>
              </div>
            </div>
          </form>
        </div>

      </div>

      <div class="panel-footer">
        <div class="row">
        <div class="pull-right">
          <button id="manual_generate" class="btn btn-success">Generate PO</button>
        </div>

        </div>

      </div>
    
    </div>
  </div>
</div>

</section>

@endsection

@section('pageJS')

  <script>

    // $('.datepicker').datepicker();

    jQuery.validator.addMethod("greaterThan", 
      function(value, element, params) {

          if (!/Invalid|NaN/.test(new Date(value))) {
              return new Date(value) > new Date($(params).val());
          }

          return isNaN(value) && isNaN($(params).val()) 
              || (Number(value) > Number($(params).val())); 
      },'Must be greater than {0}.');

    $("#manualform").validate({
      rules: {
        multiolier: {
          required: true,
          number: true
        },
        from_date: {
          required: true,
        },
        to_date: {
          required: true,
          greaterThan: "#from_date"
        }
      },
      messages:{
        multiolier:{
          required: "Multilier is required"
        },
        from_date:{
          required: "From Date is required",
        },
        to_date:{
          required: "To Date is required",
          greaterThan: "To Date must be greater than From Date"
        }
      }
    });
    
  </script>

@endsection
  