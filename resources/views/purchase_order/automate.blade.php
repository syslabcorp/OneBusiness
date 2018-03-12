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
          <form class="form-inline" action="/action_page.php">
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
        <div class="table-responsive">
          <table class="table table-striped table-bordered">
            <thead>
              <tr>
                <th>Template Code</th>
                <th>Ave Cycle</th>
              </tr>
            </thead>
            <tbody id="render_template">

            </tbody>
          </table>
        </div>

      </div>

      <div class="panel-footer">
        <div class="row">
        <div class="pull-right">
          <button class="btn btn-primary">Create P.O.</button>
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
          $.each(res.POTemplates, function( index, value ) {
            $('#render_template').append("<tr> <td> "+value.po_tmpl8_desc+" </td> <td> "+value.po_avg_cycle+" </td> </tr>");
            // <li class='ui-widget-content'>"+value.ShortName+"</li>
          });
        }
      });
    })

    $("#all_cities_checkbox_auto").on('change', function(){
      var _token = $("meta[name='csrf-token']").attr('content');
      var corpID = {{$corpID}};
      if(this.checked){
        $.ajax({
          url: ajax_url+'/purchase_order/ajax_render_template_by_all_cities',
          data: {_token,corpID},
          type: 'POST',
          success: function(res){
            $('#render_template').html('');
            $.each(res.POTemplates, function( index, value ) {
              $('#render_template').append("<tr> <td> "+value.po_tmpl8_desc+" </td> <td> "+value.po_avg_cycle+" </td> </tr>");
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