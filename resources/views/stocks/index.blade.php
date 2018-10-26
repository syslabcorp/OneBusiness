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
                @if(Auth::user()->checkAccessByIdForCorp($corpID, 35, 'A'))
                  <a href="{{route('stocks.create' , ['corpID' => $corpID] )}}" class="pull-right">Add Stock</a>
                @endif
              </div>
            </div>
          </div>

          <div class="panel-body" style="margin: 30px 0px;">
            <div class="row" style="margin-bottom: 20px;">
                <form id="form-search" action="{{route('stocks.index' , ['corpID' => $corpID] )}}" class="form-inline">
                  <div class="row">
                        <div class="radio">
                          <label><input type="radio" {{ $one_vendor ? "" : "checked" }} id="all" value="all" name="vendor">All Vendors</label>
                          <input type="hidden" name="corpID" value="{{$corpID}}">
                        </div>
                  </div>
                  <div class="radio">
                    <label><input type="radio" id="one" value="one" {{ $one_vendor ? "checked" : "" }} name="vendor">Vendor:</label>
                  </div>

                  <div class="form-group">
                    <select class="form-control" style="min-width: 200px;" name="vendorID" id="select-vendor" {{ $one_vendor ? "" : "disabled" }} >
                      @foreach($vendors as $vendor)
                        <option {{ $vendor_ID == $vendor->Supp_ID ? "selected": "" }} value="{{$vendor->Supp_ID}}">{{$vendor->VendorName}}</option>
                      @endforeach
                    </select>
                  </div>

                  
                </form>
              </div>
            <div class="table-responsive">
              <table class="table table-striped table-bordered table-parts" id="stocks_table">
                <thead>
                  @if( true )
                    <tr>
                      <th>
                        <a href="{{route('stocks.index', ['corpID' => $corpID, 'sortBy' => 'txn_no', 'order' => $next_order, 'page' => $stocks->currentPage(), 'vendor' => $vendor_list_type, 'vendorID' => $vendor_ID ]  )}}">
                          SRR #
                          @if($sortBy == 'txn_no')
                            <span class="text-right fa fa-sort-amount-{{ $next_order == 'asc' ? 'desc' : 'asc' }} pull-right">
                            </span> 
                          @else
                            <span class="text-right fa fa-exchange fa-rotate-90 pull-right">
                            </span> 
                          @endif
                        </a>
                      </th>
                      <th>
                        <a href="{{route('stocks.index' , ['corpID' => $corpID, 'sortBy' => 'RR_No', 'order' => $next_order, 'page' => $stocks->currentPage(), 'vendor' => $vendor_list_type, 'vendorID' => $vendor_ID ] )}}">
                          D.R.#
                          @if($sortBy == 'RR_No')
                            <span class="text-right fa fa-sort-amount-{{ $next_order == 'asc' ? 'desc' : 'asc' }} pull-right"></span> 
                          @else
                            <span class="text-right fa fa-exchange fa-rotate-90 pull-right"></span> 
                          @endif
                        </a>
                      </th>
                      <th>
                        <a href="{{route('stocks.index' , ['corpID' => $corpID, 'sortBy' => 'RcvDate', 'order' => $next_order, 'page' => $stocks->currentPage(), 'vendor' => $vendor_list_type, 'vendorID' => $vendor_ID ] )}}">
                          Date
                          @if($sortBy == 'RcvDate')
                            <span class="text-right fa fa-sort-amount-{{ $next_order == 'asc' ? 'desc' : 'asc' }} pull-right"></span> 
                          @else
                            <span class="text-right fa fa-exchange fa-rotate-90 pull-right"></span> 
                          @endif
                        </a>
                      </th>
                      <th>
                        <a href="{{route('stocks.index' , ['corpID' => $corpID, 'sortBy' => 'TotalAmt', 'order' => $next_order, 'page' => $stocks->currentPage(), 'vendor' => $vendor_list_type, 'vendorID' => $vendor_ID ] )}}">
                          Total Amount
                          @if($sortBy == 'TotalAmt')
                            <span class="text-right fa fa-sort-amount-{{ $next_order == 'asc' ? 'desc' : 'asc' }} pull-right"></span> 
                          @else
                            <span class="text-right fa fa-exchange fa-rotate-90 pull-right"></span> 
                          @endif
                        </a>
                      </th>
                      <th>
                        <a href="{{route('stocks.index' , ['corpID' => $corpID, 'sortBy' => 'Supp_ID', 'order' => $next_order, 'page' => $stocks->currentPage(), 'vendor' => $vendor_list_type, 'vendorID' => $vendor_ID ] )}}">
                          Vendor Name
                          @if($sortBy == 'Supp_ID')
                            <span class="text-right fa fa-sort-amount-{{ $next_order == 'asc' ? 'desc' : 'asc' }} pull-right"></span> 
                          @else
                            <span class="text-right fa fa-exchange fa-rotate-90 pull-right"></span> 
                          @endif
                        </a>
                      </th>
                      <th>
                        <a href="{{route('stocks.index' , ['corpID' => $corpID, 'sortBy' => 'DateSaved', 'order' => $next_order, 'page' => $stocks->currentPage(), 'vendor' => $vendor_list_type, 'vendorID' => $vendor_ID ] )}}">
                          Date Saved
                          @if($sortBy == 'DateSaved')
                            <span class="text-right fa fa-sort-amount-{{ $next_order == 'asc' ? 'desc' : 'asc' }} pull-right"></span> 
                          @else
                            <span class="text-right fa fa-exchange fa-rotate-90 pull-right"></span> 
                          @endif
                        </a>
                      </th>
                      <th>Action</th>
                    </tr>
                  @else


                  @endif
                </thead>
                <tbody>
                  @php $checkCountZero = true; @endphp
                  @foreach($stocks as $stock)
                    @php $checkCountZero = false; @endphp
                    <tr>
                      <td>{{$stock->txn_no}}</td>
                      <td>{{$stock->RR_No}}</td>
                      <td>{{$stock->RcvDate->format('M,d,Y') }}</td>
                      <td class="text-right">{{number_format($stock->TotalAmt,2)}}</td>
                      <td>{{$stock->vendor ? $stock->vendor->VendorName : ""}}</td>
                      <td>{{$stock->DateSaved->format('M,d,Y h:m:s A')}}</td>
                      <td class="text-center" >
                        <a href="{{ route('stocks.show', [ $stock , 'corpID' => $corpID] ) }}" class="btn btn-success {{ Auth::user()->checkAccessByIdForCorp($corpID, 35, 'V') ? "" : "disabled" }}">
                          <i class="fa fa-eye"></i>
                        </a>
                        @if($stock->check_transfered() )
                          <a class="btn btn-danger {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'D') ? "" : "disabled" }}" data-dr="{{$stock->RR_No}}" data-toggle="modal" data-target="#alert" >
                            <i class="fa fa-trash"></i>
                          </a>
                        @else
                          <a data-href="{{ route('stocks.destroy', [ $stock , 'corpID' => $corpID] ) }}" class="btn btn-danger {{ \Auth::user()->checkAccessByIdForCorp($corpID, 35, 'D') ? "" : "disabled" }}" data-dr="{{$stock->RR_No}}" data-toggle="modal" data-target="#confirm-delete" >
                            <i class="fa fa-trash"></i>
                          </a>
                        @endif
                      </td>
                    </tr>
                  @endforeach

                  @if($checkCountZero)
                    <tr>
                      <td colspan="7" style="color:red;">No received stock for this vendor</td>
                    </tr>
                  @endif
                </tbody>
              </table>
            </div>
            <div class="col-md-6">
              Show {{ $stocks->firstItem()  }} to {{ $stocks->lastItem()  }} of {{ $stocks->total() }} 
            </div>
            <div class="col-md-6 text-right">
              @if($one_vendor)
                {{ $stocks->appends(request()->except('page'))->links() }}
              @else
                {{ $stocks->appends(request()->except('page'))->links() }}
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal confirm detele -->
    
    <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">
                  <strong>Confirm Delete</strong>
                </h4>
            </div>
        
            <div class="modal-body" style="margin-bottom: 150px; margin-top: 50px;">
                <p>You are sure you want to delete <strong>DR #</strong><strong id="dr"></strong> </p>
            </div>
            
            <div class="modal-footer">
              <div class="col-md-6">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal">
                  <i class="fa fa-reply"></i> Back  
                </button>
              </div>
              <div class="col-md-6">
                <form action="" class="btn-ok" method="POST">
                  {{ csrf_field() }}
                  <input type="hidden" name="_method" value="DELETE">
                  <button class="btn btn-danger btn-ok" type="submit">Delete</button>
                </form>
              </div>
            </div>
        </div>
      </div>
    </div>

    <!-- End modal confirm detele -->

    <!-- Modal alert -->

    <div class="modal fade" id="alert" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">
              <strong>Delete DR #<span id="alert-dr"></span></strong>
            </h4>
          </div>
          <div class="modal-body">
            <p>Some or all of the items on this DR have been transferred already. You cannot delete this anymore...</p>
          </div>
          <div class="modal-footer" style="margin-top: 100px;">
            <button type="button" class="btn btn-default" data-dismiss="modal">OK</button>
          </div>
        </div>
      </div>
    </div>
    <!-- End modal alert -->



</section>

@endsection

@section('pageJS')
  <script>
    let tablePart = $('.table-parts').DataTable({
      dom: '<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f><"#customFilter">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      initComplete: ()  => {
        $("#customFilter").append('<div class="col-sm-12" style="margin: 15px 0px;"> \
          Filter: \
          <label style="font-weight: normal;"><input checked name="document-filter" value="all" type="radio" /> Show All </label> \
          <label style="font-weight: normal;padding-left: 30px;"><input name="document-filter" value="by" type="radio" /> Filter By: </label> \
          <select disabled class="form-control branch-select" style="width: 150px;"> <option value="brand">Brand</option><option value="category">Category</option><option value="vendor">Vendor</option></select> \
          <select disabled class="form-control filter-select" style="width: 150px;"> </select> \
        </div>')
      },
    })

    // $('#confirm-delete').onclick(function(event){
    //   event.preventDefault();
    // });

    $('#confirm-delete').on('show.bs.modal', function(e) {
      $(this).find('.btn-ok').attr('action', $(e.relatedTarget).data('href'));
      $('#dr').text( $(e.relatedTarget).data('dr'));
    });

    $('#alert').on('show.bs.modal', function(e) {
      $('#alert-dr').text( $(e.relatedTarget).data('dr'));
    });

    // $('#stocks_table').dataTable({
    //   "bPaginate": false,
    //   "bLengthChange": false,
    //   "bFilter": false,
    //   "aaSorting": [[ 0, "asc" ]],
    //   "columnDefs": [ {
    //     "targets": 6,
    //     "orderable": false
    //     } ],
    //   "bInfo": false,
    //   "bAutoWidth": false
    // });
  </script>
@endsection