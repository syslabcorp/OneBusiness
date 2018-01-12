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
        <form class="form-horizontal" action="{{ route('stocks.update', [ $stock ,'corpID' => $corpID]) }}" method="POST" >
            {{ csrf_field() }}
            <input type="hidden" name="corpID" value="{{$corpID}}" >
            <input type="hidden" name="_method" value="PATCH">
          <div class="panel-body" style="margin: 30px 0px;">
            <div class="row" style="margin-bottom: 20px;">
                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="control-label col-sm-2">
                        P.O#:
                      </label>
                      <div class="col-sm-4">
                        <select name="status" class="form-control" {{ $stock->check_transfered() ? "disabled" : "" }}  >
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
                      <input type="text" class="form-control" name="RR_No" {{ $stock->check_transfered() ? "disabled" : "" }} >
                    </div>

                    <label class="control-label col-sm-1">
                      Date
                    </label>
                    <div class="col-sm-4">
                      <input type="date" class="form-control" name="RcvDate" id="" value="{{$stock->RcvDate->format("Y-m-d")}}" {{ $stock->check_transfered() ? "disabled" : "" }} >
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="col-sm-6">
                    <label class="control-label col-sm-2">
                      VENDOR
                    </label>
                    <div class="col-sm-10">
                      <select name="Supp_ID" class="form-control" {{ $stock->check_transfered() ? "disabled" : "" }} >
                        <option value=""></option>
                        @foreach($vendors as $vendor)
                          <option {{ $stock->Supp_ID == $vendor->Supp_ID ? "selected" : "" }}  value="{{$vendor->Supp_ID}}">{{$vendor->VendorName}}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-sm-6">
                    <a class="pull-right btn btn-success" id="pressF2" {{ $stock->check_transfered() ? "disabled" : "" }} >
                    Add Row
                    <br>
                    (F2)
                    </a>
                  </div>
                </div>
                
            </div>
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
                  <th style="min-width: 100px;">Action</th>
                </tr>

                @foreach($stock_details as $detail)
                  <tr>
                    <td>
                      <input type="text" name="ItemCode_Update[{{$detail->Movement_ID}}]" id="" class="form-control item_code" value="{{$detail->ItemCode}}" disabled>
                    </td>
                    <td>{{$detail->stock_item ? $detail->stock_item->product_line->Product : ""}}</td>
                    <td>{{$detail->stock_item ? $detail->stock_item->brand->Brand : ""}}</td>
                    <td>{{$detail->stock_item ? $detail->stock_item->Description : ""}}</td>
                    <td>{{$detail->Cost}}</td>
                    <td>{{$detail->ServedQty }}</td>
                    <td>{{$detail->Qty }}</td>
                    <td> {{ number_format( $detail->Cost * $detail->Qty , 2) }} </td>
                    <td>{{$detail->stock_item ? $detail->stock_item->Unit : ""}}</td>
                    <td class="text-center" >
                      <a class="btn btn-primary edit" {{ $stock->check_transfered() ? "disabled" : "" }}>
                        <i class="fa fa-pencil"></i>
                      </a>
                      <a href="{{route('stocks.destroy', [ $stock , 'corpID' => $corpID] )}}" class="btn btn-danger" {{ $stock->check_transfered() ? "disabled" : "" }}>
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach

                <tr class="" id="add-row" style="display: none;">
                  <input type="hidden" name="item_id" value="" class="input_item_id">
                  <td> <input type="text" name="ItemCode" class="form-control input_ItemCode"> </td>
                  <td> <input type="text" name="Prod_Line" class="form-control input_Prod_Line"> </td>
                  <td> <input type="text" name="Brand" class="form-control input_Brand"> </td>
                  <td> <input type="text" name="Description" id="" class="form-control input_Description"> </td>
                  <td></td>
                  <td> <input type="text" name="ServedQty" id="" class="form-control"> </td>
                  <td> <input type="text" name="Qty" id="" class="form-control"> </td>
                  <td></td>
                  <td> <input type="text" name="Unit" id="" class="form-control input_Unit"> </td>
                  <td></td>
                </tr>

              </tbody>
            </table>

            <table class="table table-bordered" id="recommend-table" style=" display: none; " >
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
                  <tr class="recommend_row" style="display:table;width:100%;table-layout:fixed;" >
                    <td class="recommend_item_id"> {{$stockitem->item_id}} </td>
                    <td class="recommend_itemcode" >{{$stockitem->ItemCode}}</td>
                    <td class="recommend_prod_line" > {{$stockitem->product_line->Product}} </td>
                    <td class="recommend_prod_line_id" style="display: none;" > {{$stockitem->Prod_Line}} </td>
                    <td class="recommend_brand"  >{{$stockitem->brand->Brand}}</td>
                    <td class="recommend_brand_id" style="display: none;" >{{$stockitem->Brand_ID}}</td>
                    <td class="recommend_description">{{$stockitem->Description}}</td>
                    <td class="recommend_unit">{{$stockitem->Unit}}</td>
                    <td class="recommend_cost">{{$stockitem->LastCost}}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            <div class="row" style="margin-top: 200px;">
              <div class="col-sm-3 pull-right">
                <h4>
                  <strong>TOTAL AMOUNT:</strong>
                  <span style="color:red">{{ number_format($stock->total_amount() , 2) }}</span>
                </h4>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <a type="button" class="btn btn-default" href="{{ URL('list_module') }}">
                  <i class="fa fa-reply"></i> Back
                </a>
              </div>
              <div class="col-md-6">
                <button type="button" data-toggle="modal" data-target="#confirm_save" class="btn btn-success pull-right save_button" {{ $stock->check_transfered() ? "disabled" : "" }}>
                  Save
                </button>
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
                          <button class="btn btn-primary " type="submit">Save</button>
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

@section('pageJS')
  <script>
    function check(){
      if( {{ $stock->check_transfered() }} )
      {
        $(window).on('load',function(){
          $('#alert').modal('show');
        });
      }
    }

    $('.edit').click(function()
    {
      $(this).parents('tr').find('.item_code').removeAttr('disabled');
    });

    $('.recommend_row').click(function(){
      $('.recommend_row').removeClass('row-highlight');
      $(this).addClass('row-highlight');
      $('.input_ItemCode').val($(this).find('.recommend_itemcode').text());
      $('.input_Prod_Line').val($(this).find('.recommend_prod_line').text());
      $('.input_Brand').val($(this).find('.recommend_brand').text());
      $('.input_Description').val($(this).find('.recommend_description').text());
      // $('.input_Cost').text($(this).find('.recommend_cost').text());
      $('.input_Unit').val($(this).find('.recommend_unit').text());
      $('.input_item_id').val($(this).find('.recommend_item_id').text());
    });

    $(document).keydown(function(e) {
      if(e.which == 113) {
        $('#add-row').css('display' , ''); 
        return false;
      }
    });

    $('#pressF2').click(function(){
      $('#add-row').css('display' , ''); 
    });

    $('.input_ItemCode ,.input_Prod_Line, .input_Brand').on('click', function(){
      $('#recommend-table').css('display', "");
    });

    $('.input_ItemCode ,.input_Prod_Line, .input_Brand').on( 'change paste keyup' ,function(){
      $('.recommend_row').css('display', 'table');
      $self = $(this);
      $parent = $self.parents('#add-row');
      // if ($self.hasClass('ItemCode'))
      // {
        $('.recommend_row').each(function()
        {
          if ( $(this).find('.recommend_itemcode').text().includes( $parent.find('.input_ItemCode').val()) 
          && $(this).find('.recommend_prod_line').text().includes( $parent.find(".input_Prod_Line").val()) 
          && $(this).find('.recommend_brand').text().includes( $parent.find(".input_Brand").val()) )
          {
          }
          else
          {
          $(this).css('display' , 'none');
          }
        });
      // }
    });
  </script>
@endsection