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
      <div class="panel-body" style="margin: 30px 0px;">
        <div class="row purchase_menu" style="margin-bottom: 20px;">
            <ul class="purchase_order_style navbar-nav" >
                <li class="">
                    <a href="{{route('purchase_order.create_manual',['corpID' => $corpID]) }}">Manual P.O.</a>
                </li>

                <li class="active">
                    <a>Auto-generate P.O.</a>
                </li>
                <li class="last_item"></li>
            </ul>
        </div>

        <div class="row purchase_header_form">
          <div class="row">
            <div class="col-md-12">
            <form id="create_po_form" class="form-inline" action="/action_page.php">
            <div class="form-group">
              <label>City</label>
              <select  class="form-control" style="width: 300px;" name="" id="auto_city_list">
                <option value=""></option>
                @foreach($cities as $city)
                  <option value="{{$city->City_ID}}">{{$city->City}}</option>
                @endforeach
              </select>
            </div>
            <div class="checkbox">
              <label><input id="all_cities_checkbox_auto" type="checkbox"> All Cities</label>
            </div>
          </form>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered main_table">
            <thead>
              <tr>
                <th>Template Code</th>
                <th>Ave Cycle</th>
              </tr>
            </thead>
            <tbody id="render_template" class="selectable">

            </tbody>
          </table>
        </div>

        <!-- <div class="table-responsive">
          <table class="table process_table" style="border: 1px solid #ddd;">
            <tbody>
              <tr class="done">
                <td>Tempxyz_PO_0123154</td>
                <td>
                  Document Created 
                  <a href="#">View</a>
                  <span class=" pull-right far fa-check-circle"></span>
                </td>
              </tr>
              <tr class="done" >
                <td>Tempxza_PO_12312</td>
                <td>
                  No items to be ordered
                  <span class=" pull-right far fa-check-circle"></span>
                </td>
              </tr>
              <tr>
                <td>Tempxza_PO_123120 </td>
                <td>
                  Caculating order quantities(20%)
                  <span class=" pull-right fa fa-circle-notch fa-pulse"></span>
                </td>
              </tr>
              <tr>
                <td>Tempxza_PO_1231201 </td>
                <td>
                  Pending
                  <span class=" pull-right fas fa-circle-notch fa-pulse"></span>
                </td>
              </tr>
            </tbody>
          </table>
        </div> -->

      </div>

      <div class="panel-footer">
        <div class="row">
        <div class="pull-right">
          <button class="btn btn-primary" id="create_po_button">Create P.O.</button>
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

    $('#create_po_button').on('click', function(){
      $('.hidden_input').remove();

      $('.ui-selected').each(function()
      {
        if($(this).data('temp-id'))
        {
          $('#create_po_form').prepend("<input type='hidden' class='hidden_input' name='temp[]' value='" + $(this).data('temp-id') + "' >"); 
        }
      });

      recursively_ajax()
      
    });

    function recursively_ajax()
    {
      var corpID = {{$corpID}};
      var _token = $("meta[name='csrf-token']").attr('content');
      var list_item=$('.hidden_input');
      var temp_id = list_item.first().val();
      $.ajax({
        type:"POST",
        async:false, // set async false to wait for previous response
        url: ajax_url+'/purchase_order/auto_save',
        dataType:"json",
        data:{ _token , temp_id, corpID },
        success: function(data)
        {
          if(list_item.length > 1){
            list_item.first().remove();
            setTimeout(function()
            {
            }, 500);
            recursively_ajax();
          }
        }
      });
    }

    $('#auto_city_list').on('change', function(){
      var _token = $("meta[name='csrf-token']").attr('content');
      var City_ID = $('#auto_city_list option:selected').val();
      var corpID = {{$corpID}};
      $.ajax({
        url: ajax_url+'/purchase_order/ajax_render_template_by_city',
        data: {_token, City_ID, corpID},
        method: "POST",
        type: 'POST',
        success: function(res){
          $('#render_template').html('');
          if(res.POTemplates.length == 0)
          {
            $('#render_template').append("<tr> <td colspan='2' style='color: red;'> No PO templates found </td> </tr>");
          }
          $.each(res.POTemplates, function( index, value ) {
            $('#render_template').append("<tr class='ui-widget-content id_"+value.po_tmpl8_id+ " ' data-temp-id="+value.po_tmpl8_id+" > <td> "+value.po_tmpl8_desc+" </td> <td> "+value.po_avg_cycle+" </td> </tr>");
            
            // $('#render_template').append("<tr> <td> "+value.po_tmpl8_desc+" </td> <td> "+value.po_avg_cycle+" </td> </tr>");
            // <li class='ui-widget-content'>"+value.ShortName+"</li>
          });
        }
      });
    })

    $("body").on('change', '#all_cities_checkbox_auto', function(){
      var _token = $("meta[name='csrf-token']").attr('content');
      var corpID = {{$corpID}};
      // $('#auto_city_list').find('option').removeattr('selected');
      // $('#auto_city_list').find('option:first').attr('selected',true);
      if(this.checked){
        $.ajax({
          url: ajax_url+'/purchase_order/ajax_render_template_by_all_cities',
          data: {_token,corpID},
          type: 'POST',
          success: function(res){
            $('#render_template').html('');
            if(res.POTemplates.length == 0)
            {
              $('#render_template').append("<tr> <td colspan='2' style='color: red;'> No PO templates found </td> </tr>");
            }
            $.each(res.POTemplates, function( index, value ) {
              $('#render_template').append("<tr class='ui-widget-content id_"+value.po_tmpl8_id+ " ' data-temp-id="+value.po_tmpl8_id+" > <td> "+value.po_tmpl8_desc+" </td> <td> "+value.po_avg_cycle+" </td> </tr>");
            });
          }
        });
      }
      else
      {
        $('#render_template').html('');
      }
    });
  </script>
@endsection