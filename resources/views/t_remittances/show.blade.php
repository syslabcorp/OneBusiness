@extends('layouts.custom')

@section('content')

@if(true)

  <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-9">
                  <h4>REMITTANCE COUNTERCHECK</h4>
                  
                </div>
              </div>
            </div>

            <div class="panel-body" style="margin: 30px 0px;">
                
                <div style="margin-bottom: 30px;">
                  <form action="">
                    <div class="row">
                      <div class="form-group">
                        <label for="" class="control-label col-xs-2">
                          CLEAR STATUS
                        </label>
                          <div class="col-xs-10">
                          <label class="radio-inline" for="">
                            <input type="radio" name="status" id="">
                            All
                          </label>
                        
                          <label class="radio-inline" for="">
                            <input type="radio" name="status" id="">
                            Checked
                          </label>

                          <label class="radio-inline" for="">
                            <input type="radio" name="status" id="">
                            Unchecked
                          </label>

                          <label class="radio-inline" for="">
                            <input type="radio" name="status" id="">
                            Show Shortage only
                          </label>

                          <label class="radio-inline" for="">
                            <input type="radio" name="status" id="">
                            Show Remarks only
                          </label>
                          </div>
                          

                    </div>
                    </div>
    
                  </form>
                </div>

                <div class="table-responsive">
                  <table class="table table-striped table-bordered">
                    <tbody>
                      <tr>
                        <th class="text-center">BRANCH</th>
                        <th class="text-center">DATE</th>
                        <th class="text-center">SHIFT ID</th>
                        <th class="text-center">SHIFT TIME</th>
                        <th class="text-center">CASHIER NAME</th>
                        <th class="text-center">DETAIL</th>
                        <th class="text-center">SERVICES</th>
                        <th class="text-center">GAMES</th>
                        <th class="text-center">INTERNET</th>
                        <th class="text-center">TOTAL SALES</th>
                        <th class="text-center">TOTAL REMIT</th>
                        <th class="text-center">CLR</th>
                        <th class="text-center">WI</th>
                        <th class="text-center">AS</th>
                        <th class="text-center">SHORT</th>
                        <th class="text-center">REMARKS</th>
                        <th class="text-center">ACTION</th>
                      </tr>

                      <!-- @foreach($shifts_by_branch as $branch => $shifts_by_date)
                        @foreach($shifts_by_date as $date => $shifts)
                          @php $index = $loop->index @endphp
                          @foreach($shifts as $shift)
                            <tr>
                              @if($index == 0)
                                <td rowspan="3">{{$branch}}</td>
                              @endif
                              @if($loop->index == 0 )
                                <td rowspan="{{count($shifts)}}">{{$date}}</td>
                              @endif
                              <td>{{ $shift->Shift_ID }}</td>
                              <td></td>
                              <td></td>
                              <td></td>
                              <td>{{ $shift->remittance ?  $shift->remittance->Serv_TotalSales : "" }}</td>
                              <td>{{ $shift->remittance ? $shift->remittance->Games_TotalSales : "" }}</td>
                              <td>{{ $shift->remittance ? $shift->remittance->Net_TotalSales : "" }}</td>
                              <td>{{ $shift->remittance ? $shift->remittance->TotalSales : "" }}</td>
                              <td>{{ $shift->remittance ? $shift->remittance->TotalRemit : "" }}</td>
                              <td>
                                <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Sales_Checked == 1 ? "checked" : "") : "" }} onclick="return false;" >
                              </td>
                              <td>
                                <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Wrong_Input == 1 ? "checked" : "") : "" }} onclick="return false;" >
                              </td>
                              <td>
                                <input type="checkbox" name="" id="" {{ $shift->remittance ? ($shift->remittance->Adj_Short == 1 ? "checked" : "") : "" }} onclick="return false;"  >
                              <td></td>
                              <td>{{ $shift->remittance ? $shift->remittance->Notes : "" }}</td>
                              <td>
                                <button type="button" class="btn btn-primary show_modal" data-shift-id="{{$shift->Shift_ID}}" data-toggle="modal" data-target="#Modal">
                                  <i class="fa fa-pencil"></i>
                                </button>
                              </td>
                            </tr>
                              
                          @endforeach
                        @endforeach
                      @endforeach -->

                    </tbody>
                  </table>
                </div>

                <div id="Modal" class="modal fade" role="dialog">
                  <div class="modal-dialog">

                    <div class="modal-content">
                      <form class="form-horizontal" id="modal_form" method="POST" action="{{ route('branch_remittances.store') }}" role="form">
                          {{ csrf_field() }}
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">&times;</button>
                          <h4 class="modal-title">Edit Transaction Details</h4>
                        </div>
                        <div class="modal-body">
                        
                          <div class="row">
                            <div class="col-xs-3 ">
                              <p class="text-right">
                                <strong>
                                  CASHIER
                                </strong>
                              </p>
                              
                            </div>
                            <div class="col-xs-9">
                              <b id="cashier">
                              </b>
                            </div>

                            
                          </div>
                          <div class="row">
                            <div class="col-xs-3 ">
                              <p class="text-right">
                                <strong>
                                  Shift ID
                                </strong>
                              </p>
                              
                            </div>
                            <div class="col-xs-9">
                              <b id="shift_id">
                              </b>
                              <input type="hidden" id="hidden_shift_id" name="Shift_ID" value="">
                            </div>
                          </div>
                          
                          <div class="row">
                            <div class="col-xs-3 ">
                              <p class="text-right">
                                <strong>
                                  TOTAL SALES
                                </strong>
                              </p>
                              
                            </div>
                            <div class="col-xs-9">
                              <b id="total_sales">
                              </b>
                            </div>
                          </div>

                            <div class="row">
                              <div class="col-xs-3 ">
                              <p class="text-right">
                                <strong>
                                  TOTAL SHORTAGE  
                                </strong>
                              </p>
                              
                              </div>
                            <div class="col-xs-9">
                              <b id="total_shortage">
                              </b>
                            </div>
                          </div>

                            
                          <div class="form-group">
                            <div class="row">
                              <label  class="col-sm-3 control-label">TOTAL REMITTANCE</label>
                              <div class="col-xs-9">
                                  <input type="number" id="total_remittance" name="TotalRemit" class="form-control" value=""/>
                              </div>
                            </div>
                            
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-xs-3">
                                <div class="checkbox">
                                  <label for="">
                                    <input type="checkbox" id="counterchecker"  value="1" name="" id="">
                                    Counterchecked
                                  </label>
                                </div>
                              </div>
                            </div>
                            
                          </div>
                          <div class="form-group">
                            <div class="row">
                              <div class="col-xs-3">
                                <div class="checkbox">
                                  <label for="">
                                    <input type="checkbox" id="wrong_input" name="Wrong_Input" id="" value="1" >
                                    Wrong Input
                                  </label>
                                </div>
                              </div>
                            </div>
                            
                          </div>

                          <div class="form-group">
                            <div class="row">
                              <div class="col-xs-3">
                                  <div class="checkbox">
                                    <label for="">
                                      <input type="checkbox" id="adj_short"  value="1" name="Adj_Short" >
                                      Adjust Shortage
                                    </label>
                                  </div>
                                </div>

                                <div class="col-xs-9">
                                  <input type="text" id="shortage" name="Adj_Amt" class="form-control">
                                </div>
                            </div>
                          </div>

                          <div class="form-group">
                            <div class="row">
                              <label for="" class="col-xs-2">REMARKS</label>
                              <div class="col-xs-10">
                                <textarea id="remarks" name="Notes" class="form-control" rows="10">
                                </textarea>
                              </div>
                            </div>
                          </div>
      
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn pull-left btn-default" data-dismiss="modal">
                            <i class="fa fa-reply">Back</i>
                          </button>

                          <button id="save_button" class="btn btn-primary">Save</button>
                        </div>
                      </form>
                      
                    </div>

                  </div>
                </div>
                
                <div class="row">
                  <table class="table table-triped">
                    <tr>
                      <th>Auto Total:</th>
                      <td>Total Retail: 12321321321</td>
                      <td>Total Service: 121321321</td>
                      <td>Total Rental: 12312321</td>
                      <th>Total Sales: 1231321321</th>
                      <th>Total Remit: 1231321321</th>
                    </tr>
                  </table>
                </div>
                <div class="row">
                  <div class="pull-right col-md-3">
                    <button   class="btn btn-primary">Check Ok <br> Selection</button>
                    <button  class="btn btn-success">Save Ok <br> Selection</button>
                  </div>
                </div>
                <div class="row">
                  <a class="btn btn-default">
                    <i class="fa fa-reply"></i> Back
                  </a>
                </div>
                
            </div>
            
          </div>
        </div>
      </div>
  </section>

@else

  <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
              <div class="row">
                <div class="col-xs-9">
                  <h4>REMITTANCE COUNTERCHECK</h4>
                  
                </div>
              </div>
            </div>

            <div class="panel-body" style="margin: 30px 0px;">

              <form action="">
                <table class="table table-striped table-bordered">
                  <tbody>
                    <tr>
                      <th >BRANCH</th>
                      <th>DATE</th>
                      <th>SHIFT ID</th>
                      <th>SHIFT TIME</th>
                      <th>CASHIER NAME</th>
                      <th>ROOMS</th>
                      <th>ORDERS</th>
                      <th>TOTAL SALES</th>
                      <th>TOTAL REMIT</th>
                      <th>CLR</th>
                      <th>WI</th>
                      <th>AS</th>
                      <th>SHORT</th>
                      <th>REMARKS</th>
                      <th>ACTION</th>
                    </tr>
                    <tr>
                      <td rowspan="2">aaaa</td>
                      <td>abcde</td>
                      <td>abcde</td>
                      <td>abcde</td>
                      <td>abcde</td>
                      <td>abcde</td>
                      <td>abcde</td>
                      <td>abcde</td>
                      <td>abcde</td>
                      
                    </tr>
                  </tbody>
                </table>
              </form>
            </div>

            <div class="panel-footer"></div>
            
          </div>
        </div>
      </div>
  </section>

@endif

@endsection