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
                        <select name="po" id="PO" class="form-control" disabled >
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
                      <input type="text" class="form-control" name="RR_No" value="{{$stock->RR_No}}" disabled >
                      <input type="hidden" id="RR_No_hidden" class="form-control" name="RR_No" value="{{$stock->RR_No}}" >
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
                    <a class="pull-right btn btn-success {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'A') ? "" : "disabled" }} " id="pressF2" {{ $stock->check_transfered() ? "disabled" : "" }} >
                    Add Row
                    <br>
                    (F2)
                    </a>
                  </div>
                </div>
                
            </div>
            <div class="table-responsive">
              <table id="table_editable" class="table table-bordered" style="width: 100% !important; dispaly: table;" >
                <tbody>
                  <tr>
                    <th style="max-width: 100px;margin: 0px;box-sizing: border-box;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;">Item Code</th>
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
                  @if( $stock_details->count() > 0 )
                  @foreach($stock_details as $detail)
                    <tr class="editable" data-id="{{$detail->Movement_ID}}">
                      <td class="edit_ItemCode"  data-field="ItemCode" >
                        <span class="value_ItemCode">{{$detail->stock_item ? $detail->stock_item->ItemCode : $detail->ItemCode}}</span>
                        <input type="hidden" name="old_item_id" value="{{$detail->item_id}}" >
                        <input type="hidden" name="item_id" value="{{$detail->item_id}}" >
                        <input class="show_suggest" type="hidden" name="ItemCode" id="" value="{{$detail->stock_item ? $detail->stock_item->ItemCode : ""}}" >
                      </td>
                      <td class="edit_Prod_Line" data-field="Prod_Line" >
                        <span class="value_Prod_Line">{{$detail->stock_item ? $detail->stock_item->product_line->Product : ""}}</span>
                        <input class="show_suggest" type="hidden" name="Prod_Line" value="{{$detail->stock_item ? $detail->stock_item->product_line->Product : ""}}" >
                      </td>
                      <td class="edit_Brand" data-field="Brand" >
                        <span class="value_Brand">{{$detail->stock_item ? $detail->stock_item->brand->Brand : ""}}</span>
                        <input class="show_suggest" type="hidden" name="Brand" id="" value="{{$detail->stock_item ? $detail->stock_item->brand->Brand : ""}}" >
                      </td>
                      <td class="edit_Description" >
                        <span class="value_Description">{{$detail->stock_item ? $detail->stock_item->Description : ""}}</span>
                      </td>
                      <td class="edit_Cost text-right" data-field="Cost" >
                      <span class="value_Cost">{{number_format($detail->Cost,2)}}</span>
                        <input type="hidden" name="Cost" id="" value="{{number_format($detail->Cost,2)}}" >
                      </td>
                      <td class="edit_ServedQty text-right" >
                        <span class="value_ServedQty">{{$detail->ServedQty }}</span>
                      </td>
                      <td class="edit_Qty text-right" data-field="Qty" >
                        <span class="value_Qty">{{$detail->Qty }}</span>
                        <input type="hidden" name="Qty" id="" value="{{$detail->Qty }}" >
                      </td>
                      <td class="edit_Sub text-right" >
                        {{ number_format( $detail->Cost * $detail->Qty , 2) }} 
                      </td>
                      <td class="edit_Unit" >
                        <span class="value_Unit">{{$detail->stock_item ? $detail->stock_item->Unit : ""}}</span>
                      </td>
                      <td class="text-center" >
                        <a class="btn btn-primary edit {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'E') ? "" : "disabled" }} " {{ $stock->check_transfered() ? "disabled" : "" }}>
                          <i class="fa fa-pencil"></i>
                        </a>
                        <a href="{{route('stocks.delete_detail', [ $stock , $detail , 'corpID' => $corpID] )}}" class="btn btn-danger {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'D') ? "" : "disabled" }} " {{ $stock->check_transfered() ? "disabled" : "" }}>
                          <i class="fa fa-trash"></i>
                        </a>
                      </td>
                    </tr>
                  @endforeach
                  @else
                    <tr id="no-item">
                      <td colspan="10" style="color: red;" class="text-center" >
                        No items
                      </td>
                    </tr>
                  @endif

                  <tr class="" id="add-row" style="display: none;">
                    <input type="hidden" name="item_id" value="" class="input_item_id">
                    <td> <input type="text" name="ItemCode" class="form-control check_focus input_ItemCode"> </td>
                    <td> <input type="text" name="Prod_Line" class="form-control check_focus input_Prod_Line"> </td>
                    <td> <input type="text" name="Brand" class="form-control check_focus input_Brand"> </td>
                    <td> <input type="text" name="Description" id="" class="form-control input_Description"> </td>
                    <td> <input type="text" name="Cost" id="" class="form-control input_Cost"> </td>
                    <td> <input type="text" name="ServedQty" id="" class="form-control input_ServedQty"> </td>
                    <td> <input type="text" name="Qty" id="" value="1" class="input_Qty form-control"> </td>
                    <td> <input type="text" name="Sub" id="" class="input_Sub form-control"> </td>
                    <td class="input_Unit" ></td>
                    <td class="text-center" >
                      <a data-href="#"  class="btn btn-success add_detail {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'A') ? "" : "disabled" }}" >
                        <i class="fa fa-check"></i>
                      </a>
                      <a data-href="#"  class="btn btn-danger delete_add_detail {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'D') ? "" : "disabled" }}" >
                        <i class="fa fa-trash"></i>
                      </a>
                    </td>
                  </tr>

                </tbody>
              </table>
            </div>


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
                    <td class="recommend_item_id" style="display: none;">{{$stockitem->item_id}} </td>
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
                  <span id="total_amount" style="color:red">{{ number_format($stock->total_amount() , 2) }}</span>
                </h4>
              </div>
            </div>

            <div class="row">
              <div class="col-md-6">
                <a type="button" class="btn btn-default" href="{{ URL::previous() }}">
                  <i class="fa fa-reply"></i> Back
                </a>
              </div>
              <div class="col-md-6">
                <button type="button" data-toggle="modal" data-target="#confirm_save" class="btn btn-success pull-right save_button {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'A') ? "" : "disabled" }} " {{ $stock->check_transfered() ? "disabled" : "" }}>
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

    $('.add_detail').on('click', function()
    {
      var self = $(this);
      $.ajax({
        url: "{{ route('stocks.save_new_row_ajax', [ $stock ,'corpID' => $corpID]) }}",
        type: "POST",
        data: {
          "_token": "{{ csrf_token() }}",
          "item_id": $('#add-row').find(".input_item_id" ).val(),
          "ItemCode": $('#add-row').find(".input_ItemCode" ).val(),
          "Cost": $('#add-row').find('.input_Cost').val(),
          "ServedQty": $('#add-row').find('.input_ServedQty').val(),
          "Qty": $('#add-row').find('.input_Qty').val(),
          "RcvDate": $('input[name="RcvDate"]').val(),
          "RR_No": $('#RR_No_hidden').val()
        },
        success: function(res){
          if(res.status)
          {
            $('.editable').last().after('<tr class="editable" data-id="'+res.Movement_ID+'">\
            <td class="edit_ItemCode"  data-field="ItemCode" >\
              <span class="value_ItemCode">'+res.ItemCode+'</span>\
              <input type="hidden" name="old_item_id" value="'+res.item_id+'" >\
              <input type="hidden" name="item_id" value="'+res.item_id+'" >\
              <input class="show_suggest" type="hidden" name="ItemCode" id="" value="'+res.ItemCode+'" >\
            </td>\
            <td class="edit_Prod_Line" data-field="Prod_Line" >\
              <span class="value_Prod_Line">'+res.Prod_Line+'</span>\
              <input class="show_suggest" type="hidden" name="Prod_Line" value="'+res.Prod_Line+'" >\
            </td>\
            <td class="edit_Brand" data-field="Brand" >\
              <span class="value_Brand">'+res.Brand+'</span>\
              <input class="show_suggest" type="hidden" name="Brand" id="" value="'+res.Brand+'" >\
            </td>\
            <td class="edit_Description" >\
              <span class="value_Description">'+res.Description+'</span>\
            </td>\
            <td class="edit_Cost text-right" data-field="Cost" >\
            <span class="value_Cost">'+res.Cost+'</span>\
              <input type="hidden" name="Cost" id="" value="'+res.Cost+'" >\
            </td>\
            <td class="edit_ServedQty text-right" >\
              <span class="value_ServedQty">'+res.ServedQty+'</span>\
            </td>\
            <td class="edit_Qty text-right" data-field="Qty" >\
              <span class="value_Qty">'+res.Qty+'</span>\
              <input type="hidden" name="Qty" id="" value="'+res.Qty+'" >\
            </td>\
            <td class="edit_Sub text-right" >\
              '+res.Sub_view+' \
            </td>\
            <td class="edit_Unit" >\
              <span class="value_Unit">'+res.Unit+'</span>\
            </td>\
            <td class="text-center" >\
              <a class="btn btn-primary edit">\
                <i class="fa fa-pencil"></i>\
              </a>\
              <a href="'+res.route+'" class="btn btn-danger " >\
                <i class="fa fa-trash"></i>\
              </a>\
            </td>\
          </tr>');

          $('#add-row').find('input').val('');
          $('#add-row').find('.input_Unit').text('');
          $('#add-row').css('display', 'none');
          }
        }
      });
    });

    @if($stock->check_transfered())
      $(window).on('load',function(){
        $('#alert').modal('show');
      });
    @endif

    $('body').on('focus', '.editable input' , function()
    {
      $('.last_focus').removeClass('last_focus');
      $(this).addClass('last_focus');
    }
    );

    $('body').on('focus', '.check_focus' , function()
    {
      $('.last_focus').removeClass('last_focus');
      $(this).addClass('last_focus');
    }
    );

    $('.show_suggest').on('change paste keyup', function()
    {
      if($(this).val() == "")
      {
        $('#recommend-table').css('display', "");
        $('.recommend_row').css('display', "table");
        $('.recommend_row').removeClass('row-highlight');
      }
    });


    $('.recommend_row').click(function(){
      $('.recommend_row').removeClass('row-highlight');

      if($('.last_focus').hasClass('check_focus'))
      {
        $(this).addClass('row-highlight');
        $('.input_ItemCode').val($(this).find('.recommend_itemcode').text());
        $('.input_Prod_Line').val($(this).find('.recommend_prod_line').text());
        $('.input_Brand').val($(this).find('.recommend_brand').text());
        $('.input_Description').val($(this).find('.recommend_description').text());
        if($('.input_Cost').val() == "")
        {
          $('.input_Cost').val($(this).find('.recommend_cost').text());
        }
        $('.input_Unit').text($(this).find('.recommend_unit').text());
        $('.input_item_id').val($(this).find('.recommend_item_id').text());
        $('#recommend-table').css('display', "none");
      }
      else
      {
        $(this).addClass('row-highlight');
        $('.last_focus').parents('.editable').find('.edit_ItemCode').find("input[name='ItemCode']").val($(this).find('.recommend_itemcode').text());
        $('.last_focus').parents('.editable').find('.edit_ItemCode').find("input[name='item_id']").val($(this).find('.recommend_item_id').text());
        $('.last_focus').parents('.editable').find('.edit_Brand').find("input[name='Brand']").val($(this).find('.recommend_brand').text());
        $('.last_focus').parents('.editable').find('.edit_Prod_Line').find("input[name='Prod_Line']").val($(this).find('.recommend_prod_line').text());
        $('.last_focus').parents('.editable').find('.edit_Description').find(".value_Description").text($(this).find('.recommend_description').text());
        $('.last_focus').parents('.editable').find('.edit_Unit').find(".value_Unit").text($(this).find('.recommend_unit').text());
        $('#recommend-table').css('display', "none");
      }
    });

    $('body').on('click', '.edit', function(){
      var self = $(this);
      if($(this).find('i').hasClass('fa-pencil'))
      {
        $(this).find('i').removeClass('fa-pencil').addClass('fa-save');
        $(this).parents('.editable').find( "input[name='ItemCode']" ).val($(this).parents('.editable').find('.value_ItemCode').text()).attr("type", "text") ;
        $(this).parents('.editable').find( "input[name='Prod_Line']" ).val($(this).parents('.editable').find('.value_Prod_Line').text()).attr("type", "text") ;
        $(this).parents('.editable').find( "input[name='Brand']" ).val($(this).parents('.editable').find('.value_Brand').text()).attr("type", "text") ;
        $(this).parents('.editable').find( "input[name='Qty']" ).val($(this).parents('.editable').find('.value_Qty').text()).attr("type", "text") ;
        $(this).parents('.editable').find( "input[name='Cost']" ).val($(this).parents('.editable').find('.value_Cost').text()).attr("type", "text") ;
        
        $(this).parents('.editable').find('.value_ItemCode, .value_Prod_Line, .value_Brand, .value_Qty, .value_Cost').text("");
      }
      else
      {
        $.ajax({
          url: "{{ route('stocks.update_detail', [ $stock ,'corpID' => $corpID]) }}",
          type: "POST",
          data: {
            "_token": "{{ csrf_token() }}",
            "Movement_ID": $(this).parents('.editable').data( "id" ),
            "old_id": $(this).parents('.editable').find( "input[name='old_item_id']" ).val(),
            "id": $(this).parents('.editable').find( "input[name='item_id']" ).val(),
            "Cost": $(this).parents('.editable').find( "input[name='Cost']" ).val(),
            "Qty": $(this).parents('.editable').find( "input[name='Qty']" ).val()
          },
          success: function(res){
            // if(res.status == true)
            // {
              self.parents('.editable').find('.value_ItemCode').text(res.ItemCode);
              self.parents('.editable').find('.value_Prod_Line').text(res.Prod_Line);
              self.parents('.editable').find('.value_Brand').text(res.Brand);
              self.parents('.editable').find('.edit_ItemCode').find("input[name='old_item_id']").val(res.item_id);
            // }
          }
        });

        $('#recommend-table').css('display', "none");

        $(this).find('i').addClass('fa-pencil').removeClass('fa-save');
        // $(this).parents('.editable').find('.value_ItemCode').text( $(this).parents('.editable').find( "input[name='ItemCode']" ).val());
        // $(this).parents('.editable').find('.value_Brand').text( $(this).parents('.editable').find( "input[name='Brand']" ).val());
        // $(this).parents('.editable').find('.value_Prod_Line').text( $(this).parents('.editable').find( "input[name='Prod_Line']" ).val());
        $(this).parents('.editable').find('.value_Qty').text( $(this).parents('.editable').find( "input[name='Qty']" ).val());
        $(this).parents('.editable').find('.value_Cost').text( $(this).parents('.editable').find( "input[name='Cost']" ).val());
        
        $(this).parents('.editable').find( "input[name='ItemCode']" ).attr("type", "hidden");
        $(this).parents('.editable').find( "input[name='Cost']" ).attr("type", "hidden");
        $(this).parents('.editable').find( "input[name='Prod_Line']" ).attr("type", "hidden");
        $(this).parents('.editable').find( "input[name='Brand']" ).attr("type", "hidden");
        $(this).parents('.editable').find( "input[name='Qty']" ).attr("type", "hidden");
      }
      
    });

    @if(!$stock->check_transfered())
      $(document).keydown(function(e) {
        if(e.which == 113) {
          $('#add-row').css('display' , ''); 
          // $('#PO').removeAttr('disabled', false);
          // $('input[name="RR_No"]').removeAttr('disabled');
          $('#no-item').css('display', 'none');
          return false;
        }
      });
    @endif

    $('#pressF2').click(function(){
      $('#add-row').css('display' , ''); 
      // $('#PO').removeAttr('disabled', false);
      // $('input[name="RR_No"]').removeAttr('disabled');
      $('#no-item').css('display', 'none');
    });

    $('.input_ItemCode ,.input_Prod_Line, .input_Brand').on('click', function(){
      $('#recommend-table').css('display', "");
    });

    var old_total = parseFloat($('#total_amount').text().replace(",", ""));

    $('.input_Cost ,.input_Qty, .input_Sub').on( 'change paste keyup', function()
    {
      $self = $(this);
      $parent = $self.parents('#add-row');
      if ($self.hasClass('input_Cost') || $self.hasClass('input_Qty'))
      {
        if( ($parent.find('.input_Cost').val() != "" ) && ($parent.find('.input_Qty').val() != "" ) )
        {
          var val = parseFloat($parent.find('.input_Cost').val()) * parseFloat($parent.find('.input_Qty').val());
          $parent.find('.input_Sub').val(val);
          var newtotal = (old_total + parseFloat($parent.find('.input_Sub').val()));
          $('#total_amount').text(newtotal.numberFormat(2));
        }
        else
        {
          $parent.find('.input_Sub').val('');
          $('#total_amount').text(old_total.numberFormat(2));
        }
      }

      if ($self.hasClass('input_Sub'))
      {
        if( ($parent.find('.input_Cost').val() != "" ) && ($parent.find('.input_Sub').val() != "" ) )
        {
          var val = parseFloat($parent.find('.input_Sub').val()) / parseFloat($parent.find('.input_Qty').val());
          $parent.find('.input_Cost').val(val);

        }
        else
        {
          $('#total_amount').text(old_total.numberFormat(2));
        }

        if($parent.find('.input_Sub').val() != "" )
        {
          var newtotal = (old_total + parseFloat( $parent.find('.input_Sub').val() ));
          $('#total_amount').text(newtotal.numberFormat(2));
        }
      }
      
    });

    $('.input_ItemCode ,.input_Prod_Line, .input_Brand').on( 'click change paste keyup' ,function(){
      $('.recommend_row').css('display', 'table');
      $self = $(this);
      $parent = $self.parents('#add-row');
      if ($self.hasClass('input_ItemCode'))
      {
        $('.recommend_row').each(function()
        {
          if ( $(this).find('.recommend_itemcode').text().includes( $parent.find('.input_ItemCode').val()) )
          {
          }
          else
          {
            $(this).css('display' , 'none');
          }
        });
      }

      if(($self.hasClass('input_Brand')))
      {
        $('.recommend_row').each(function()
        {
          if ( $(this).find('.recommend_brand').text().includes( $parent.find(".input_Brand").val()) )
          {
          }
          else
          {
            $(this).css('display' , 'none');
          }
        });
      }

      if($self.hasClass('input_Prod_Line'))
      {
        $('.recommend_row').each(function()
        {
          if ( $(this).find('.recommend_prod_line').text().includes( $parent.find(".input_Prod_Line").val())  )
          {
          }
          else
          {
            $(this).css('display' , 'none');
          }
        });
      }
    });

    $('.delete_add_detail').on('click', function()
    {
      $('#add-row').css('display' , 'none'); 
      $('.recommend_row').removeClass('row-highlight');
      $('.input_ItemCode').val('');
      $('.input_Prod_Line').val('');
      $('.input_Brand').val('');
      $('.input_Description').val('');
      $('.input_Cost').val('');
      $('.input_Unit').text('');
      $('.input_item_id').val('');
      $('#recommend-table').css('display', "none");
    });

  </script>
@endsection