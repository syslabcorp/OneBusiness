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
                    <a href="{{route('purchase_order.create_automate') }}">Auto-generate P.O.</a>
                </li>
                <li class="last_item"></li>
            </ul>
        </div>
        <form id="main_form" action="{{route('purchase_order.manual_save')}}"  method="POST">
          <input type="hidden" name="_token" value="{{ csrf_token() }}">
          <input type="hidden" name="corpID" value="{{$corpID}}">
          <div class="row border_bottom">
            <span style="font-size: 36px">Generate P.O.:</span>
            <span>Total Pieces: 
              <span class="value_total_pieces">
                {{number_format($total_pieces,2)}}  
              </span>
            </span>
            <span>Total Amount: 
              <span class="value_total_amount">
                {{number_format($total_amount,2)}}
              </span>
            </span>
          </div>

          <div class="table-responsive">
            <table class="table table-bordered" id="table_editable">
              <thead>
                <tr>
                  <th>Item Code</th>
                  @foreach( $header_branch as $key => $branch )
                    <th class="text-center" colspan="5">{{$branch}}</th>
                  @endforeach
                  <th class="red_box">TOTAL</th>
                  <th class="blue_box" >BAL</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td></td>
                  @for($x = 1; $x <= $num_branch; $x++)
                    <td>Ave.Sold Qty(Daily)</td>
                    <td>Mult</td>
                    <td>Stock(w/in-Trans)</td>
                    <td>Pending(for PO)</td>
                    <td class="blue_box" style="width: 100px;">Qty</td>
                  @endfor
                  <td class="red_box"></td>
                  <td class="blue_box"></td>
                  <td></td>
                </tr>
                
                @foreach($items as $item_id => $item)
                <tr class="editable">
                  <td>
                    <span class="value_ItemCode">
                      {{$item['ItemCode']}}
                    </span>
                  </td>
                  @foreach($item['items'] as $branch_id => $item_of_branch)
                    <input type="hidden" name="ItemCode[{{$item['item_id']}}][{{$branch_id}}]" value="{{$item['ItemCode']}}">
                    <td class="branch_{{$branch_id}}">
                      <span class="value_daily_sold_qty">
                        {{$item_of_branch['daily_sold_qty']}}
                      </span>
                    </td>
                    <td class="branch_{{$branch_id}}">
                      <span class="value_mult mult_{{$branch_id}}">
                        {{$item_of_branch['Mult']}}
                      </span>
                    </td>
                    <td class="branch_{{$branch_id}}">
                      <span class="value_stock">
                        {{$item_of_branch['stock']}}
                      </span>
                    </td>
                    <td class="branch_{{$branch_id}}">
                      <span class="value_pending">{{$item_of_branch['pending']}}</span>
                    </td>
                    <td class="blue_box branch_{{$branch_id}}">
                      <span class="value_QtyPO">{{$item_of_branch['QtyPO']}}</span>
                      <input type="hidden" name="cost[{{$item['item_id']}}][{{$branch_id}}]" class="input_cost" value="{{$item_of_branch['cost']}}">
                      <input autocomplete="off" class="input_QtyPO" type="hidden" name="QtyPO[{{$item['item_id']}}][{{$branch_id}}]" value="{{$item_of_branch['QtyPO']}}" > 
                    </td>
                  @endforeach
                  <td class="red_box">
                    <span class="value_total">
                      {{$item['total']}}
                    </span>
                  </td>
                  <td class="blue_box">
                    <span class="value_bal">
                      {{$item['Bal']}}
                    </span>
                  </td>
                  <td> <button type="button" class="btn btn-primary edit"> <span class="fa fa-pencil"></span> </button> </td>
                </tr>
                @endforeach

              </tbody>
            </table>
          </div>
        </form>
      </div>

      <div class="panel-footer">
        <div class="row">
        <div class="col-md-6">
          <button class="btn btn-default">Back</button>
        </div>
        <div class="col-ms-6 pull-right">
          <button class="btn btn-primary" data-toggle="modal" data-target="#myModal" >Save</button>
        </div>

        </div>

      </div>

      <button type="button" class="btn btn-info btn-lg" >Open Modal</button>

      <!-- Modal -->
      <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to save this P.O.?</p>
            </div>
            <div class="modal-footer">
              <button id="submit_main_form" type="button" class="btn btn-primary">Save</button>
            </div>
          </div>

        </div>
      </div>

      <!-- Modal PDF -->
      <div id="pdfModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">PO PDF file</h4>
            </div>
            <div class="modal-body">
              <p>Do you want to open the file?</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-default" data-dismiss="modal">No</button>
              <a id="pdf_link" href="#" class="btn btn-primary" target="_blank" >Yes</a>
            </div>
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
  $('#submit_main_form').on('click',function(event)
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
          $('#myModal').modal('hide');
          setTimeout(function(){
            $('#pdfModal').modal('show');
            $('#pdfModal').find('#pdf_link').attr('href', res.url)
          }, 500);
        }
      });
  });

  $('body').on('click', '.edit', function(){
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
    // self.parents('td').attr('class').split(' ')[1];
    self.parents('.editable').find("."+self.parents('td').attr('class').split(' ')[1]).find( ".value_QtyPO" ).text(parseInt(self.val()) );
    self.parents('.editable').find("."+self.parents('td').attr('class').split(' ')[1]).find( ".value_mult" ).text("?");
  });
</script>

@endsection