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
      <form class="form-horizontal" action="{{ route('stocktransfer.update', [$hdrItem, 'corpID' => $corpID]) }}" method="POST">
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
                  <a class="btn btn-success {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'A') ? "" : "disabled" }} " id="pressF2" >
                  Add Row
                  <br>
                  (F2)
                  </a>
                </div>
              </div>
              
          <div class="table-responsive">
            <table id="table_editable" class="table table-bordered" style="width: 100% !important; display: table;" >
              <thead>
                <tr>
                  <th style="min-width: 100px;">Item Code</th>
                  <th>Product Line</th>
                  <th>Brand</th>
                  <th>Description</th>
                  <th>Qty</th>
                  <th>Unit</th>
                  <th style="min-width: 100px;">Action</th>
                </tr>
              </thead>
              <tbody>
                @foreach($hdrItem->details()->get()->groupBy('item_id') as $row)
                @php
                  $detail = $row->first();
                  if(!$detail->item) {
                    continue;
                  }
                @endphp

                <tr class="editable">
                  <td class="edit_ItemCode" data-field="ItemCode" >
                    <span class="value_ItemCode">{{ $detail->ItemCode }}</span>
                    <input class="input_item_id" type="hidden" value="{{ $detail->item_id }}" 
                      name="details[{{ $loop->index }}][item_id]">
                    <input autocomplete="off" class="show_suggest input_ItemCode" type="hidden" value="{{ $detail->ItemCode }}"
                      name="details[{{ $loop->index }}][ItemCode]">
                  </td>
                  <td class="edit_Prod_Line" data-field="Prod_Line" >
                    <span class="value_Prod_Line">{{$detail->item->product_line->Product}}</span>
                    <input autocomplete="off" class="show_suggest input_Prod_Line" type="hidden" 
                      value="{{$detail->item->product_line->Product}}">
                  </td>
                  <td class="edit_Brand" data-field="Brand" >
                    <span class="value_Brand">{{$detail->item->brand->Brand}}</span>
                    <input autocomplete="off" class="show_suggest input_Brand" type="hidden"
                      value="{{$detail->item->brand->Brand}}">
                  </td>
                  <td class="edit_Description" >
                    <span class="value_Description">{{$detail->item->Description}}</span>
                  </td>
                  <td class="edit_Qty text-right" data-field="Qty" >
                    @php $maxQty = $row->sum('Qty') + $rcvModel->where('item_id', $detail->item_id)->sum('Bal') @endphp
                    <span class="value_Qty">{{ $row->sum('Qty') }}</span>
                    <input type="hidden" class="input_Qty"  data-validation-error-msg="Invalid input: Please enter a number."  data-validation="number" data-validation-allowing="float" data-validation-optional="true" value="{{ $row->sum('Qty') }}"
                    data-max="{{ $maxQty }}"
                      name="details[{{ $loop->index }}][Qty]">
                  </td>
                  <td class="edit_Unit" >
                    <span class="value_Unit">{{ $detail->item->Unit }}</span>
                  </td>
                  <td class="text-center">
                    <a class="btn btn-primary edit {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'E') ? "" : "disabled" }}" >
                      <i class="fa fa-pencil"></i>
                    </a>
                    <a href="#" class="delete_row btn btn-danger {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'D') ? "" : "disabled" }} " >
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>
                @endforeach
                <tr class="editable" id="example" style="display: none;">
                  <td class="edit_ItemCode" data-field="ItemCode" >
                    <span class="value_ItemCode"></span>
                    <input class="input_item_id" type="hidden" value="" >
                    <input autocomplete="off" class="show_suggest input_ItemCode" type="hidden">
                  </td>
                  <td class="edit_Prod_Line" data-field="Prod_Line" >
                    <span class="value_Prod_Line"></span>
                    <input autocomplete="off" class="show_suggest input_Prod_Line" type="hidden"  >
                  </td>
                  <td class="edit_Brand" data-field="Brand" >
                    <span class="value_Brand"></span>
                    <input autocomplete="off" class="show_suggest input_Brand" type="hidden" >
                  </td>
                  <td class="edit_Description" >
                    <span class="value_Description"></span>
                  </td>
                  <td class="edit_Qty text-right" data-field="Qty" >
                    <span class="value_Qty"></span>
                    <input type="hidden" class="input_Qty"  data-validation-error-msg="Invalid input: Please enter a number."  data-validation="number" data-validation-allowing="float" data-validation-optional="true" value="" >
                  </td>
                  <td class="edit_Unit" >
                    <span class="value_Unit"></span>
                  </td>
                  <td class="text-center">
                    <a class="btn btn-primary edit {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'E') ? "" : "disabled" }}" >
                      <i class="fa fa-pencil"></i>
                    </a>
                    <a href="#" class="delete_row btn btn-danger {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'D') ? "" : "disabled" }} " >
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>

                <tr class="" id="add-row" style="display: none;">
                  <input type="hidden" name="item_id" value="" class="input_item_id">
                  <td> <input autocomplete="off" type="text" name="ItemCode" class="form-control check_focus input_ItemCode"> </td>
                  <td> <input autocomplete="off" type="text" name="Prod_Line" class="form-control check_focus input_Prod_Line"> </td>
                  <td> <input autocomplete="off" type="text" name="Brand" class="form-control check_focus input_Brand"> </td>
                  <td> <input type="text" name="Description" id="" class="form-control input_Description"> </td>
                  <td> <input type="text" name="Qty" id=""  data-validation-error-msg="Invalid input: Please enter a number."  data-validation="number" data-validation-allowing="float" value="1" data-validation-optional="true" class="input_Qty form-control"> </td>
                  <td class="input_Unit"></td>
                  <td class="text-center" >
                    <a class="btn btn-primary add_detail {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'A') ? "" : "disabled" }}" 
                      href="javascript:void(0);">
                      <i class="fa fa-check"></i>
                    </a>
                    <a type="button" data-href="#"  class="btn btn-danger delete_add_detail {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'D') ? "" : "disabled" }}" href="javascript:void(0);">
                      <i class="fa fa-trash"></i>
                    </a>
                  </td>
                </tr>

              </tbody>
            </table>
          </div>


          <table class="table table-bordered" id="recommend-table" style="display: none; " >
            <thead>
              <tr style="display:table;width:100%;table-layout:fixed; background:#f27b82" >
                <th>Item Code</th>
                <th>Product Line</th>
                <th>Brand</th>
                <th>Description</th>
                <th>Qty On Hand</th>
                <th>Unit</th>
              </tr>
            </thead>
            <tbody style="max-height:300px; overflow-y:scroll; background: #f4b2b6;">
              @foreach($suggestItems as $suggestItem )
                <tr class="recommend_row" style="display:none;width:100%;table-layout:fixed;" data-branch="{{ $suggestItem->Branch }}">
                  <td class="recommend_item_id" style="display: none;">{{$suggestItem->item_id}} </td>
                  <td class="recommend_itemcode" >{{$suggestItem->ItemCode}}</td>
                  <td class="recommend_prod_line" >{{$suggestItem->item->product_line->Product}} </td>
                  <td class="recommend_prod_line_id" style="display: none;" >{{$suggestItem->item->Prod_Line}} </td>
                  <td class="recommend_brand"  >{{$suggestItem->item->brand->Brand}}</td>
                  <td class="recommend_brand_id" style="display: none;" >{{$suggestItem->item->Brand_ID}}</td>
                  <td class="recommend_description">{{$suggestItem->item->Description}}</td>
                  <td class="qty_on_hand">{{ $rcvModel->where('item_id', $suggestItem->item_id)->sum('Bal') }}</td>
                  <td class="recommend_unit">{{$suggestItem->item->Unit}}</td>
                </tr>
              @endforeach
              <tr style="display: none;" class="empty">
                <td colspan="6">
                  <span class="error">No active items for this branch</span>
                </td>
              </tr>
            </tbody>
          </table>

          <div class="row">
            <div class="col-md-6">
              <a type="button" class="btn btn-default" href="{{ route('stocktransfer.index', [ 'corpID' => $corpID, 'tab' => 'stock', 'stockStatus' => $stockStatus]) }}">
              <span style="margin-right: 7px;" class="glyphicon glyphicon-arrow-left"></span>Back
              </a>
            </div>
            <div class="col-md-6">
              <button type="button" data-toggle="modal" class="btn btn-success pull-right btn-save {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'A') ? "" : "disabled" }} " >
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

@section('pageJS')
  <script type="text/javascript">
    branchChange = () => {
      let branchId = $('select[name="Txfr_To_Branch"]').val()

      $('#recommend-table tbody tr').css('display', 'none')
      $('#recommend-table tbody tr[data-branch="' + branchId + '"]').css('display', 'table')
      
      if($('#recommend-table tbody tr:not(:hidden)').length == 0) {
        $('#recommend-table tbody tr.empty').css('display', 'table-row')
      }
    }

    isItemRowsValid = () => {
      for(let index = 0; index < $('#table_editable .editable:visible').length; index++) {
        let checkElement = $($('#table_editable .editable:visible')[index]).find('.input_Qty')
        if(parseInt(checkElement.val()) > parseInt(checkElement.attr('data-max'))) {
          showAlertMessage('Qty exceeds stock on hand...', 'Error in Qty')
          return false
        }
      }

      return true
    }

    checkDuplicateItem = (itemCode) => {
      for(let index = 0; index < $('#table_editable .editable:visible').length; index++) {
        let rowElement = $($('#table_editable .editable:visible')[index])
        if(rowElement.find('.input_item_id').val() == itemCode) {
          return true
        }
      }

      return false
    }
  </script>
  
  <script type="text/javascript">
    $.validate({
      form : 'form'
    });

    $('form').on('keypress', function(event) {
        if (event.keyCode == 13) {
            event.preventDefault();
        }
    });

    var old_total = parseFloat($('#total_amount').text().replace(",", ""));
  

    $('body').on('click', '.delete_row', function(event) {
      event.preventDefault();
      $(this).closest('tr').remove();
    });

    $('.add_detail').on('click', function()
    {
      $.validate({
        form : 'form'
      });
      $trParent = $(this).parents('tr');
      $trParent.find('td:eq(0) .error').remove();
      if($trParent.find('input[name="item_id"]').val() == "") {
        $trParent.find('td:eq(0)').append("<span class='error'>Please select an item.</span>");
        return;
      }

      $('#recommend-table').css('display', "none");

      if(checkDuplicateItem($('#add-row .input_item_id').val().trim())) {
        showAlertMessage('Duplicate entry detected...', 'Item Entry Error...')
        return false
      }
      
      if( !$('.input_Cost ').hasClass('error') && !$('.input_Qty ').hasClass('error')) {
        let inputQtyElement = $('#add-row .input_Qty')
        if(parseInt(inputQtyElement.val()) > parseInt(inputQtyElement.attr('data-max'))) {
          showAlertMessage('Qty exceeds stock on hand...', 'Error in Qty')
          return false
        }

          var $add_row = $('#add-row');
          var new_element = $('#example').clone();
          let countItem = $("#table_editable tr").length

          new_element.css("display", "").removeAttr('id');


          $('.editable').last().after(new_element);

          
          //ItemCode
          new_element.find('.input_item_id').val($add_row.find('.input_item_id').val());
          new_element.find('.input_item_id').attr('name', 'details[' + countItem + '][item_id]')
          new_element.find('.input_ItemCode').val($add_row.find('.input_ItemCode').val());
          new_element.find('.input_ItemCode').attr('name', 'details[' + countItem + '][ItemCode]')
          new_element.find('.value_ItemCode').text($add_row.find('.input_ItemCode').val());
          
          //ProductLine
          new_element.find('.value_Prod_Line').text($add_row.find('.input_Prod_Line').val());
          new_element.find('.input_Prod_Line').val($add_row.find('.input_Prod_Line').val());

          new_element.find('.input_Qty').attr('data-max', $add_row.find('.input_Qty').attr('data-max'));

          //Brand
          new_element.find('.value_Brand').text($add_row.find('.input_Brand').val());
          new_element.find('.input_Brand').val($add_row.find('.input_Brand').val());

          //Description
          new_element.find('.value_Description').text($add_row.find('.input_Description').val());

          //Qty
          if($add_row.find('.input_Qty').val() != '')
          {
            new_element.find('.value_Qty').text( parseInt($add_row.find('.input_Qty').val()) );
            new_element.find('.input_Qty').val( parseInt($add_row.find('.input_Qty').val()) );
          }
          else
          {
            new_element.find('.value_Qty').text( '0.00' );
            new_element.find('.input_Qty').val( '0.00' );
          }

          new_element.find('.input_Qty').attr('name', 'details[' + countItem + '][Qty]')

          //Unit
          new_element.find('.value_Unit').text($add_row.find('.input_Unit').text());


          $('#add-row').find('input').val('');
          $('#add-row').find('.input_Qty').val('1');
          $('#add-row').find('.input_Unit').text('');
          $('#add-row').css('display', 'none');
          $('.recommend_row').removeClass('row-highlight');
          
        }

    });

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
      }
    });

    $('.recommend_row').click(function(){
      $('#table_editable td span.error').remove();
      $('.recommend_row').removeClass('row-highlight');
      if($('.last_focus').hasClass('check_focus'))
      {
        $parent = $('.last_focus').parents('#add-row');
        $(this).addClass('row-highlight');
        $('#add-row').find('.input_ItemCode').val($(this).find('.recommend_itemcode').text());
        $('#add-row').find('.input_Prod_Line').val($(this).find('.recommend_prod_line').text());
        $('#add-row').find('.input_Brand').val($(this).find('.recommend_brand').text());
        $('#add-row').find('.input_Description').val($(this).find('.recommend_description').text());
        $('#add-row').find('.input_Qty').attr('data-max', $(this).find('.qty_on_hand').text())
        if($(this).find('.recommend_cost').text() != "")
        {
          $('#add-row').find('.input_Cost').val($(this).find('.recommend_cost').text());
          if( ($('#add-row').find('.input_Cost').val() != "" ) && ($('#add-row').find('.input_Qty').val() != "" ) )
          {
            var val = parseFloat($parent.find('.input_Cost').val()) * parseFloat($parent.find('.input_Qty').val());
            $('#add-row').find('.input_Sub').val(val);
          }
        }
        $parent.find('.input_Unit').text($(this).find('.recommend_unit').text());
        $parent.find('.input_item_id').val($(this).find('.recommend_item_id').text());
        $('#recommend-table').css('display', "none");
      }
      else
      {
        $parent = $('.last_focus').parents('.editable');
        $(this).addClass('row-highlight');
        $('.last_focus').parents('.editable').find('.edit_ItemCode').find(".input_ItemCode").val($(this).find('.recommend_itemcode').text());
        $('.last_focus').parents('.editable').find('.edit_ItemCode').find(".input_item_id").val($(this).find('.recommend_item_id').text());
        $('.last_focus').parents('.editable').find('.edit_Brand').find(".input_Brand").val($(this).find('.recommend_brand').text());
        $('.last_focus').parents('.editable').find('.edit_Prod_Line').find(".input_Prod_Line").val($(this).find('.recommend_prod_line').text());
        $('.last_focus').parents('.editable').find('.edit_Description').find(".value_Description").text($(this).find('.recommend_description').text());
        $('.last_focus').parents('.editable').find('.edit_Unit').find(".value_Unit").text($(this).find('.recommend_unit').text());
        $('.last_focus').find('.editable').find('.input_Qty').attr('data-max', $(this).find('.qty_on_hand').text())

        $('#recommend-table').css('display', "none");
      }
    });

    $('body').on('click', '.edit', function(){
      var self = $(this);
      $.validate({
        form : 'form'
      });
      if($(this).find('i').hasClass('fa-pencil'))
      {
        $(this).find('i').removeClass('fa-pencil').addClass('fa-save');
        $(this).parents('.editable').find( ".input_ItemCode" ).val($(this).parents('.editable').find('.value_ItemCode').text()).attr("type", "text") ;
        $(this).parents('.editable').find( ".input_Prod_Line" ).val($(this).parents('.editable').find('.value_Prod_Line').text()).attr("type", "text") ;
        $(this).parents('.editable').find( ".input_Brand" ).val($(this).parents('.editable').find('.value_Brand').text()).attr("type", "text") ;
        $(this).parents('.editable').find( ".input_Qty" ).val($(this).parents('.editable').find('.value_Qty').text()).attr("type", "text") ;
        $(this).parents('.editable').find( ".input_type" ).val('editting') ;
        $(this).parents('.editable').find('.value_ItemCode, .value_Prod_Line, .value_Brand, .value_Qty, .value_Cost, .value_Sub').text("");
      }
      else
      {
        if(!isItemRowsValid() || $(this).parents('tr').find('.error').length > 0) {
          return false
        }

        $(this).parents('tr').find('.error').remove();
        if( !$('.input_Cost ').hasClass('error') && !$('.input_Qty ').hasClass('error') && !$('.input_Sub ').hasClass('error')  )
        {
        self.parents('.editable').find('.value_ItemCode').text(self.parents('.editable').find('.input_ItemCode').val() );
        self.parents('.editable').find('.value_Prod_Line').text(self.parents('.editable').find('.input_Prod_Line').val() );
        self.parents('.editable').find('.value_Brand').text(self.parents('.editable').find('.input_Brand').val());

        if( self.parents('.editable').find('.input_Cost').val() != "" )
        {
          self.parents('.editable').find('.value_Cost').text( parseFloat(self.parents('.editable').find('.input_Cost').val()).toFixed(2) );
        }
        else
        {
          self.parents('.editable').find('.value_Cost').text('0.00');
        }

        if( self.parents('.editable').find('.input_Qty').val() != "" )
        {
          self.parents('.editable').find('.value_Qty').text( parseInt(self.parents('.editable').find('.input_Qty').val()) );
        }
        else
        {
          self.parents('.editable').find('.value_Qty').text('0');
        }

        if( self.parents('.editable').find('.input_Sub').val() != "" )
        {
          self.parents('.editable').find('.value_Sub').text(parseFloat(self.parents('.editable').find('.input_Sub').val()).toFixed(2));
        }
        else
        {
          self.parents('.editable').find('.value_Sub').text('0.00');
        }
        if(self.parents('.editable').attr('data-id') == "") {
          self.parents('.editable').find( ".input_type" ).val('add') ;
        }else {
          self.parents('.editable').find( ".input_type" ).val('none') ;
        }
        
        
        $('#recommend-table').css('display', "none");

        self.find('i').addClass('fa-pencil').removeClass('fa-save');
        
        self.parents('.editable').find( ".input_ItemCode" ).attr("type", "hidden");
        self.parents('.editable').find( ".input_Cost" ).attr("type", "hidden");
        self.parents('.editable').find( ".input_Prod_Line" ).attr("type", "hidden");
        self.parents('.editable').find( ".input_Brand" ).attr("type", "hidden");
        self.parents('.editable').find( ".input_Qty" ).attr("type", "hidden");
        self.parents('.editable').find( ".input_Sub" ).attr("type", "hidden");
        
        }
      }
    });

    $(document).keydown(function(e) {
      if(e.which == 113) {
        $('#add-row').css('display' , ''); 
        $('#no-item').css('display', 'none');
        $('#alert_nothing').remove();
        return false;
      }
    });

    $('#pressF2').click(function(){
      $('#add-row').css('display' , ''); 
      $('#no-item').css('display', 'none');
      $('#alert_nothing').remove();
    });

    $('body').on('click', '.input_ItemCode ,.input_Prod_Line, .input_Brand', function(){
      $('#recommend-table').css('display', "");
      branchChange()
    });

    var ignore_key = false;
    var key_next = false;
    var key_prev = false;
    var key_enter = false;
    var last;
    var index;
    $('body').on('keydown', '.input_ItemCode ,.input_Prod_Line, .input_Brand', function(e){      
      if(e.which == 40) {
        key_prev = true;
        ignore_key = true;
      }

      if(e.which == 38) {
        key_next = true;
        ignore_key = true;
      }
    }).on( 'click change paste keyup', '.input_ItemCode ,.input_Prod_Line, .input_Brand' ,function(e){
        if(ignore_key || (e.which == 13)){
        if(key_prev)
        {
          last = $('.row-highlight');
          index = $('.recommend_row:not(:hidden)').index(last);
          if (last[0] == $('.recommend_row:not(:hidden)').last()[0])
          {
            $('.recommend_row:not(:hidden):eq(0)').addClass('row-highlight');
          }
          else
          {
            $(".recommend_row:not(:hidden):eq("+(index+1)+")").addClass('row-highlight');
          }
          last.removeClass('row-highlight');
          $('.row-highlight')[0].scrollIntoView({
            behavior: "smooth",
            block: "center" 
          });
        }

        if(key_next)
        {
          last = $('.row-highlight');
          index = $('.recommend_row:not(:hidden)').index(last);
          if (last[0] == $('.recommend_row:not(:hidden)').first()[0])
          {
            $('.recommend_row:not(:hidden)').last().addClass('row-highlight');
          }
          else
          {
            $(".recommend_row:not(:hidden):eq("+(index-1)+")").addClass('row-highlight');
          }
          last.removeClass('row-highlight'); 
          $('.row-highlight')[0].scrollIntoView({
            behavior: "smooth",
            block: "center" 
          }); 
        }

        if(e.which == 13)
        {
          $('.row-highlight').click();
          $('body').focus();
        }
        ignore_key = false;
        key_next = false;
        key_prev = false;
        key_enter = false;

        return false;
      }
      $self = $(this);

      if ($self.parents('#add-row').length) 
      {
        $parent = $self.parents('#add-row');
      }
      else
      {
        $parent = $self.parents('.editable');
      }

      branchChange()
      
      if ($self.hasClass('input_ItemCode'))
      {
        $('.recommend_row').each(function()
        {
          if ( $(this).find('.recommend_itemcode').text().toUpperCase().includes( $parent.find('.input_ItemCode').val().toUpperCase() ) ) 
          
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
          if ( $(this).find('.recommend_brand').text().toUpperCase().includes( $parent.find(".input_Brand").val().toUpperCase() ) )
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
          if ( $(this).find('.recommend_prod_line').text().toUpperCase().includes( $parent.find(".input_Prod_Line").val().toUpperCase() )  )
          {
          }
          else
          {
            $(this).css('display' , 'none');
          }
        });
      }
      $('.recommend_row').removeClass('row-highlight');
      $('.recommend_row:not(:hidden)').first().addClass('row-highlight');
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
      $('.input_Qty').val(1);
      $('.input_Sub').val('');
      $('.input_item_id').val('');
      $('#recommend-table').css('display', "none");
    });

    showAlertMessage = (message, title = "Alert", isReload = false) => {
      swal({
        title: "<div class='delete-title'>" + title + "</div>",
        text:  "<div class='delete-text'>" + message + "</strong></div>",
        html:  true,
        customClass: 'swal-wide',
        showCancelButton: false,
        closeOnConfirm: true,
        allowEscapeKey: !isReload
      }, (data) => {
        if(isReload) {
          window.location.reload()
        }
      });
    }

    $('.btn-save').on('click', function(event) {
      @if($hdrItem->Rcvd == '1')
      showAlertMessage('This stock transfer has just been received by the branch! You \
      can not longer save modifications to this transaction...', 'Save Conflict')
      return
      @endif

      $('#table_editable td span.error').remove();
      $('#table_editable input[value="editting"]').each(function() {
        $(this).parents('.editable').find('td:eq(0)').append("<span class='error'>Please save or delete this row first…</span>");
      });

      if($('#add-row').is(':visible')) {
        $('#add-row').find('td:eq(0)').append("<span class='error'>Please save or delete this row first…</span>");
      }

      if($('#table_editable input[value="editting"]').length > 0 || $('#add-row').is(':visible')) {
        return;
      }

      if( $('form').isValid(false) )
      {
        if(!isItemRowsValid()) {
          return false;
        }

        if( $('.editable').length == 1 )
        {
          $('.alert-nothing').remove();
          $('#alert_nothing').remove();
          $("<tr> <td colspan='10' id='alert_nothing' class='text-center' style='color:red;'> Please select an item </td>  </tr>").insertAfter( $('#table_editable').find('tr').last() );
          $('#page-content-togle-sidebar-sec').prepend('\
          <div class="row alert-nothing">\
            <div class="alert alert-danger col-md-8 col-md-offset-2" style="border-radius: 3px;">\
              <span class="fa fa-close"></span> <em>Nothing to save!</em>\
            </div>\
          </div>\
          ');
          setTimeout(function () {
            $('.alert-nothing').slideUp(400);
            $("#alert_nothing").remove();
          }, 10000);
        }
        else
        {
          swal({
            title: "<div class='delete-title'>Transfer</div>",
            text:  "<div class='delete-text'>Are you sure you want to save?</strong></div>",
            html:  true,
            customClass: 'swal-wide',
            showCancelButton: true,
            confirmButtonClass: 'btn-success',
            closeOnConfirm: false,
            closeOnCancel: true
          },
          (isConfirm) => {
            $(this).closest('form').submit()
          })
        }
      }
    });

  </script>
@endsection