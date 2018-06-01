
<div class="row" id="ajax_tab">
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
                <li class="">
                  <a href="{{route('purchase_order.create_automate',['corpID' => $corpID]) }}">Auto-generate P.O.</a>
                </li>
                <li class="active">
                    <a>Manual P.O.</a>
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
                  <th class="text-center">Item Code</th>
                  @foreach( $header_branch as $key => $branch )
                    <th class="text-center" colspan="5">{{$branch}}</th>
                  @endforeach
                  <th class="red_box text-center">TOTAL</th>
                  <th class="blue_box text-center" >BAL</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td class="text-center"></td>
                  @for($x = 1; $x <= $num_branch; $x++)
                    <td class="text-center">Ave.Sold Qty(Daily)</td>
                    <td class="text-center">Mult</td>
                    <td class="text-center">Stock(w/in-Trans)</td>
                    <td class="text-center">Pending(for PO)</td>
                    <td class="blue_box text-center" style="width: 100px;">Qty</td>
                  @endfor
                  <td class="red_box text-center"></td>
                  <td class="blue_box text-center"></td>
                  <td></td>
                </tr>
                
                @foreach($items as $item_id => $item)
                <tr class="editable">
                  <td class=" text-center">
                    <span class="value_ItemCode">
                      {{$item['ItemCode']}}
                    </span>
                  </td>
                  @foreach($item['items'] as $branch_id => $item_of_branch)
                    <input type="hidden" name="ItemCode[{{$item['item_id']}}][{{$branch_id}}]" value="{{$item['ItemCode']}}">
                    <td class="branch_{{$branch_id}}  text-center">
                      <span class="value_daily_sold_qty">
                        {{$item_of_branch['daily_sold_qty']}}
                      </span>
                    </td>
                    <td class="branch_{{$branch_id}}  text-center">
                      <span class="value_mult mult_{{$branch_id}}">
                        {{$item_of_branch['Mult']}}
                      </span>
                    </td>
                    <td class="branch_{{$branch_id}} text-center">
                      <span class="value_stock">
                        {{$item_of_branch['stock']}}
                      </span>
                    </td>
                    <td class="branch_{{$branch_id}} text-center">
                      <span class="value_pending">{{$item_of_branch['pending']}}</span>
                    </td>
                    <td class="blue_box branch_{{$branch_id}} text-center">
                      <span class="value_QtyPO">{{$item_of_branch['QtyPO']}}</span>
                      <input type="hidden" name="cost[{{$item['item_id']}}][{{$branch_id}}]" class="input_cost" value="{{$item_of_branch['cost']}}">
                      <input autocomplete="off" class="input_QtyPO" type="hidden" name="QtyPO[{{$item['item_id']}}][{{$branch_id}}]" value="{{$item_of_branch['QtyPO']}}" > 
                    </td>
                  @endforeach
                  <td class="red_box text-center">
                    <span class="value_total">
                      {{$item['total']}}
                    </span>
                  </td>
                  <td class="blue_box text-center">
                    <span class="value_bal">
                      {{$item['Bal']}}
                    </span>
                  </td>
                  <td class="text-center"> <button type="button" class="btn btn-primary edit"> <span class="fas fa-pencil-alt"></span> </button> </td>
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
          <button class="btn btn-default" id="backbutton">Back</button>
        </div>
        <div class="col-ms-6 pull-right">
          <button class="btn btn-primary" data-toggle="modal" data-target="#myModal" >Save</button>
        </div>

        </div>

      </div>

      <!-- Modal -->
      <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

          <!-- Modal content-->
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal">&times;</button>
              <h4 class="modal-title">
                Confirm Save
              </h4>
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

