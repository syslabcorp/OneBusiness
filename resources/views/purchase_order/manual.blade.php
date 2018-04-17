@extends('layouts.custom')

@section('content')

<!-- Page content -->
<section class="content">
<div class="row" id="main_tab">
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

                  <div class="form-group" style="margin-left: 20px;">
                      <label for="group">Group:</label>
                      <select class="form-control required listgroup" id="group" name="group_id">
                        @foreach ($groups as $group) 
                            <option {{ ($group ->group_ID == $group_id) ? "selected" : "" }} value="{{ $group->group_ID }}" >{{ $group->desc }} </option> 
                        @endforeach    
                      </select>
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
                      @foreach($branchs as $branch)
                        <li class="ui-widget-content" data-branch="{{$branch->Branch}}">{{$branch->ShortName}}</li>
                      @endforeach
                    </ul>

                  </div>

                </div>
              </div>
                @if(!$checkINN)
                  <div class="form-group">
                    <div class="col-md-4">
                      <label class="checkbox-inline">
                        <input type="checkbox" id="NX" name="branch_type" value="NX" checked >NetExpress
                      </label>
                    </div>
                    <div class="col-md-4 text-center">
                      <label class="checkbox-inline">
                        <input type="checkbox" id="SQ" name="branch_type" value="SQ" checked>Sequel
                      </label>
                    </div>
                    <div class="col-md-4 text-right">
                      <label class="checkbox-inline">
                        <input type="checkbox" id="IS" name="branch_type" value="IS" checked>iSing
                      </label>
                    </div>
                  </div>
                @endif
                <div class="form-group">
                  <label>Date Range</label>
                  
                <div class="row">
                  <div class="col-md-6">
                    <label for="">From</label>
                    <input type="text" name="from_date" id="from_date" class="datepicker form-control" >
                  </div>

                  <div class="col-md-6">
                    <label for="">To</label>
                    <input type="text" name="to_date" id="to_date" class="datepicker form-control">
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

<div id="loading-animation" style="display: none;">
  <b style="color: blue;">Processing Orders ... Please wait</b>

  <div class="meter blue" style="height: 30px;">
    <span style="width: 100%"><span></span></span>
  </div>
</div>

</section>

@endsection

@section('pageJS')

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.js"></script>
  <script>

    $('.datepicker').datepicker({
      changeMonth: true,
      changeYear: true
    });

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


    //manual suggest

    $('body').on('click', '#submit_main_form', function(event)
    {
      $('<input>').attr({
        type: 'hidden',
        name: 'total_pieces',
        value: parseFloat( $('.value_total_pieces').text().replace(",", ""))
      }).appendTo('#main_form');

      $('<input>').attr({
        type: 'hidden',
        name: 'total_amount',
        value: parseFloat($('.value_total_amount').text().replace(",", ""))
      }).appendTo('#main_form');

      $.ajax({
          url: $('#main_form').attr('action'),
          data: $('#main_form').serialize(),
          type: 'POST',
          success: function(res){
            $('#main_form').append("<input type='hidden' name='po_no' value='"+res.po_no+"'>")
            $('#myModal').modal('hide');
            setTimeout(function(){
              $('#pdfModal').modal('show');
              $('#pdfModal').find('#pdf_link').attr('href', res.url)
            }, 500);
          }
        });
    });

    $('body').on('click', '.edit', function(){
      $('.input_QtyPO').each(function()
      {
        if( parseInt($(this).val()) )
        {
          if(parseInt($(this).val()) < 0)
          {
            $(this).val(0);
          }
        }
        else
        {
          $(this).val(0);
        }
      });
      var self = $(this);
      if(self.find('span').hasClass('fa-pencil'))
      {
        self.find('span').removeClass('fa-pencil').addClass('fa-save');
        self.parents('.editable').find( ".input_QtyPO" ).each(function( index ) {
          $(this).val($(this).parents('td').find('.value_QtyPO').text()).attr("type", "text");
        });
        self.parents('.editable').find( ".value_QtyPO" ).css("display", "none");
      }
      else
      {
        var total_line = 0;
        self.find('span').removeClass('fa-save').addClass('fa-pencil');
        self.parents('.editable').find( ".input_QtyPO" ).attr("type", "hidden");
        self.parents('.editable').find( ".value_QtyPO" ).css("display", "");
        self.parents('.editable').find(".input_QtyPO").each(function(index)
        {
          total_line += parseInt($(this).val());
        });
        self.parents('.editable').find('.value_total').text(total_line);

        var total_pieces = 0;
        $('.value_total').each(function()
        {
          total_pieces += parseInt($(this).text());
        });
        $('.value_total_pieces').text(total_pieces.toFixed(2));

        var total_amount = 0;
        $('.input_QtyPO').each(function(index){
          total_amount+= parseInt($(this).val()) * parseFloat($(this).parents('td').find('.input_cost').val())
        });
        $('.value_total_amount').text(total_amount.toFixed(2));
      }
    });

    $('body').on('change paste keyup', '.input_QtyPO', function()
    {
      var self = $(this);
      var result;
      if( parseInt(self.val()) )
      {
        if( parseInt(self.val()) < 0 )
        {
          result = 0;
        }
        else
        {
          result = parseInt(self.val());
        }
      }
      else
      {
        result = 0;
      }
      // self.parents('td').attr('class').split(' ')[1];
      self.parents('.editable').find("."+self.parents('td').attr('class').split(' ')[1]).find( ".value_QtyPO" ).text( result );
      self.parents('.editable').find("."+self.parents('td').attr('class').split(' ')[1]).find( ".value_mult" ).text("?");
    });

    $('body').on('click', '#backbutton', function()
    {
      $('#main_tab').show();
      $('#ajax_tab').remove();
      $("#manual_generate").prop('disabled', false);
      $( "input[name='ItemCode[]']" ).remove();
      $( "input[name='branchs[]']" ).remove();
    });

    $("#all_cities_checkbox").on('change', function(){
      var _token = $("meta[name='csrf-token']").attr('content');
      var Corp_ID = {{ $corpID }};
      var group = $('#group').val();
      if(this.checked){
        $('#dropdown_city_list').prepend("<option id='addForFun' selected></option>");
        
        $('#dropdown_city_list').prop('disabled','disabled');
        $.ajax({
          url: ajax_url+'/purchase_order/ajax_render_branch_by_all_cities',
          data: {_token, Corp_ID, group},
          type: 'POST',
          success: function(res){
            $('#branch').html('');
            $.each(res.branchs, function( index, value ) {
              $('#branch').append("<li class='ui-widget-content ' data-branch="+value.Branch+">"+value.ShortName+"</li>");
            });
          }
        });
      }
      else
      {
        $('#branch').html('');
        $('#addForFun').remove();
        $('#dropdown_city_list').prop('disabled',false);

        var _token = $("meta[name='csrf-token']").attr('content');
        var City_ID = $('#city-list option:selected').val();
        var Corp_ID = {{ $corpID }};
        $.ajax({
          url: ajax_url+'/purchase_order/ajax_render_branch_by_city',
          data: {_token, City_ID, Corp_ID, group},
          method: "POST",
          type: 'POST',
          success: function(res){
            $.each(res.branchs, function( index, value ) {
              $('#branch').append("<li class='ui-widget-content ' data-branch="+value.Branch+">"+value.ShortName+"</li>");
            });
          }
        });

      }
    });
  
    $('#city-list ,#group').on('change', function(){
      var _token = $("meta[name='csrf-token']").attr('content');
      var group = $('#group').val();
      var City_ID = $('#city-list option:selected').val();
      var Corp_ID = {{ $corpID }};
      $.ajax({
        url: ajax_url+'/purchase_order/ajax_render_branch_by_city',
        data: {_token, City_ID, Corp_ID, group},
        method: "POST",
        type: 'POST',
        success: function(res){
          $('#branch').html('');
          $.each(res.branchs, function( index, value ) {
            $('#branch').append("<li class='ui-widget-content ' data-branch="+value.Branch+">"+value.ShortName+"</li>");
          });
        }
      });
    })
    
  </script>

@endsection
  