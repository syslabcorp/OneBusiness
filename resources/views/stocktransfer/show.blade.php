<?php
use App\Spodetail;
use App\Srcvdetail;
?>
@extends('layouts.app')

@section('header_styles')
	<link href="{{ asset('css/my.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
<div class="container-fluid">
<div class="row">
    
<div id="togle-sidebar-sec" class="active">
      
      <!-- Sidebar -->
      <div id="sidebar-togle-sidebar-sec">
        <div id="sidebar_menu" class="sidebar-nav">
          <ul></ul>
        </div>
      </div>
          
      <!-- Page content -->
      <div id="page-content-togle-sidebar-sec">
		@if(Session::has('alert-class'))
			<div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@elseif(Session::has('flash_message'))
			<div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
		@endif
      <div class="col-md-12">

    	<div class="row" style="margin-top: 30px;">    
        <div class="panel panel-default">
          <div class="panel-heading">
            Stock Transfer
          </div>
          <div class="panel-body">
            <section class="content">
              <div class="row">
                <div class="col-md-12">
                  <div class="panel">
                    <div class="panel-body">
                        <div class="bs-example">
                            <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                              <li class="active">
                                <a href="#access" data-toggle="tab">Auto stock transfer</a>
                              </li>
                            </ul>
                            <div  class="tab-content">
                              <div class="tab-pane fade active in" id="access" >
                                <div class="col-md-12">
                                  <div class="row" style="border:1px solid lightgray;padding: 7px 7px 0px 7px;">
                                    <div class="col-md-4">
                                      <p> P.O.#:
                                        <span> {{ $stockItem->po_no}} </span>
                                      </p>
                                      <p> P.O.Template: 
                                        <span>
                                          {{ $stockItem->template ? $stockItem->template->po_tmpl8_desc : '' }}
                                        </span>
                                      </p>
                                    </div>
                                    <div class="col-md-4">
                                      <p>P.O.Date: <span> {{ $stockItem->po_date }} </span></p>
                                      <p>Total Pieces: 
                                        <span style="color:red;">
                                          {{ $stockItem->items()->whereIn('Branch', $branches->pluck('Branch'))->sum('Qty') }}
                                        </span>
                                      </p>
                                    </div>
                                    <div class="col-md-4" style=" padding-top: 10px;">
                                      <button class="btn btn-default" style="width:9em;float:right;">Print</button>
                                    </div>
                                  </div>
                                </div>
                                <form class="form-horizontal table-items" action="#" id="formTable" method="POST" >
                                  {{ csrf_field() }}
                                  <div class="col-md-12" style="margin-top: 10px;">
                                    <div class="row">
                                      <div class="table-responsive">
                                        <table id="table_editable_1" class="col-sm-12 table table-striped table-bordered">
                                          <thead>
                                            <tr>
                                              <th style="min-width: 150px;">Item Code</th>
                                              @foreach($branches as $branch)
                                                <th style="width: 250px; min-width: 150px">{{ $branch->ShortName }}</th>
                                              @endforeach
                                              <th class="text-center" style="color:blue; min-width: 100px;">TOTAL</th>
                                              <th class="text-center" style="color:red; min-width: 100px;">STOCK</th>
                                            </tr>
                                          </thead>
                                          <tbody>
                                            @foreach($itemRows as $row)
                                            <tr>
                                              <td data-id="{{ $row->first()->item_id }}">{{ $row->first()->ItemCode }}</td>
                                              @foreach($branches as $branch)
                                                @php
                                                  $maxQty = $row->where('Branch', $branch->Branch)->sum('Qty') - $row->where('Branch', $branch->Branch)->sum('ServedQty')
                                                @endphp
                                                <td style="width: 250px; min-width: 150px" class="col-qty" 
                                                  data-branch="{{ $branch->Branch }}" data-max="{{ $maxQty }}">
                                                  <input type="text" class="form-control" {{ !\Auth::user()->checkAccessByIdForCorp($corpID, 42, 'E')? "" : "readonly" }}
                                                    value="{{ $maxQty }}" {{ $maxQty == 0 ? 'readonly' : '' }}>
                                                </td>
                                              @endforeach
                                              <td class="col-total text-center">{{ $row->sum('Qty')  - $row->sum('ServedQty') }}</td>
                                              <td class="col-stock text-center">{{ $row->first()->rcvDetails()->sum('Bal') }}</td>
                                            </tr>
                                            @endforeach
                                          </tbody>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </form>
                                <div class="col-md-12">
                                  <div class="row">
                                    <div class="col-md-6">
                                      <a class="btn btn-default" style="width:8em;"
                                        href="{{ route('stocktransfer.index', ['corpID' => $corpID, 'tab' => 'auto']) }}">
                                        <span style="margin-right: 7px;" class="glyphicon glyphicon-arrow-left"></span>
                                        Back
                                      </a>
                                    </div>

                                    <div class="col-md-6">
                                      <button onclick="transferStocks()" class="btn btn-info btn-transfer" style="background-color:green;width:9em;float:right;" {{ !\Auth::user()->checkAccessByIdForCorp($corpID, 42, 'E')? "" : "disabled" }}>Transfer Stocks</button>
                                    </div>
                                  </div>
                                </div>
                              </div>
                              <div class="tab-pane fade" id="tasks">
                              </div>
                            </div>
                        </div>
                    </div>
                  </div>
                </div>
              </div>
            </section>
          </div>
        </div>
      </div>
    </div>
</div>
</div>
</div>
</div>
</div>
<script src="{{ asset('js/table-edits.min.js') }}"></script>
<script src="{{ asset('js/momentjs.min.js') }}"></script>
<script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
<script>
 changedRowArr = [];


$(document).ready(function() {
  // Set column text color red if value equal zero
  $('table tbody tr td').each(function(el) {
    if($.isNumeric($(this).text()) && parseInt($(this).text()) == 0) {
      $(this).css('color', '#f44336');
    }
  });

    $('#stockDetail').DataTable({
    "dom": '<"m-t-10"B><"m-t-10 pull-left"><"m-t-10 pull-right">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
   
    });

});

function goBack() {
    window.history.back();
}

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

checkRowsTransfer = () => {
  let hasRowsTransfer = false
  
  for(let index = 0; index < $('.table .col-total').length; index++) {
    let el = $($('.table .col-total')[index])

    if($.isNumeric(el.text()) && parseFloat(el.text()) != 0) {
      hasRowsTransfer = true
    }
  }

  if(!hasRowsTransfer) {
    showAlertMessage('No item to transfer')
    return false;
  }

  return true;
}

checkAvailableStock = () => {
  for(let index = 0; index < $('.table .col-total').length; index++) {
    let totalElement = $($('.table .col-total')[index])
    let stockElement = totalElement.parents('tr').find('.col-stock')
    let itemCode = totalElement.parents('tr').find('td:eq(0)').text()
    
    if(parseFloat(totalElement.text()) > parseFloat(stockElement.text())) {
      showAlertMessage('Not enough ' + itemCode + ' in warehouse...')
      return false
    }
  }

  return true
}

// @return Object
getTransferParams = () => {
  let params = {}

  for(let index = 0; index < $('.table tbody tr').length; index++) {
    let rowElement = $($('.table tbody tr')[index])
    let itemCode = rowElement.find('td:eq(0)').text()
    let itemId = rowElement.find('td:eq(0)').attr('data-id')
    let colElements = rowElement.find('.col-qty')
    
    if(parseInt(rowElement.find('.col-total').text()) == 0) {
      continue;
    }

    params[itemCode] = {}

    for(let subIndex = 0; subIndex < colElements.length; subIndex++) {
      let colElement = $(colElements[subIndex])
      let branchId = colElement.attr('data-branch')

      params[itemCode][branchId] = {}
      params[itemCode][branchId]['ItemId'] = itemId
      params[itemCode][branchId]['Branch'] = branchId
      params[itemCode][branchId]['Qty'] = colElement.find('.form-control').val()
    }
  }

  return params
}

transferStocks = () => {
  if($('.table tbody .error').length > 0) {
    showAlertMessage('Please check form error')
    return false
  }

  swal({
    title: "<div class='delete-title'>Transfer</div>",
    text:  "<div class='delete-text'>Are you sure you want to transfer these items</strong></div>",
    html:  true,
    customClass: 'swal-wide',
    showCancelButton: true,
    confirmButtonClass: 'btn-success',
    closeOnConfirm: false,
    closeOnCancel: true
  },
  (isConfirm) => {
    if(isConfirm) {
      if(checkRowsTransfer() && checkAvailableStock()) {
        $.ajax({
          url: '{{ route('stocktransfer.transfer', [$stockItem, 'corpID' => $corpID]) }}',
          data: {items: getTransferParams() },
          type: "POST",
          async: false,
          success: function(response){
            showAlertMessage("Items has been transferred successfully", "Success", true);
          }
        });
      }
    }
  });

}



</script>
@endsection


@section('footer-scripts')
<script type="text/javascript">
  (function() {
    $('.table-items .form-control').keyup(function() {
      $parent = $(this).parents('td');
      $parentTr = $(this).parents('tr');
      let maxItem = parseInt($parent.attr('data-max'));

      $parent.find('.error').remove();

      if(!$(this).val() || $.isNumeric($(this).val()) && parseInt($(this).val()) <= maxItem) {
        let qtyTotal = 0;
        $parentTr.find('.col-qty').each(function(el) {
          let colQty = $(this).find('.form-control').val();
          if($.isNumeric(colQty)) {
            qtyTotal += parseInt(colQty);
          }
        })

        if(qtyTotal != 0) {
          $parentTr.find('.col-total').css('color', '#111111');
        }else {
          $parentTr.find('.col-total').css('color', '#f44336');
        }

        $parentTr.find('.col-total').text(qtyTotal);
      }else {
        if($.isNumeric($(this).val()) && parseInt($(this).val()) > maxItem) {
          $parent.append('<span class="error">Input can\'t more than the required quantity (' + maxItem + ')</span>');
        }else {
          $parent.append('<span class="error">Invalid input</span>');
        }
      }
    });

    $('.table-items .form-control').keydown(function(event) {
      let currentCol = $(this).parents('td').index();
      let currentRow = $(this).parents('tr').index();
      let minCol = $('.table-items tr .col-qty').index();
      let maxCol = minCol - 1 + $('.table-items tr:eq(1) .col-qty').length;
      let maxRow = $('.table-items tbody tr').length;

      switch(event.which) {
        case 37:
          currentCol -= 1;
          break;
        case 38:
          currentRow -= 1;
          break;
        case 39:
          currentCol += 1;
          break;
        case 40:
        currentRow += 1;
          break;
        default:
          break;
      }

      if(currentCol < minCol) {
        currentCol = maxCol;
        currentRow -= 1;
      }else if(currentCol > maxCol) {
        currentCol = minCol;
        currentRow += 1;
      }
      
      if($('.table-items tbody tr:eq(' + currentRow + ') td:eq(' + currentCol + ') .form-control').length) {
        $('.table-items tbody tr:eq(' + currentRow + ') td:eq(' + currentCol + ') .form-control')[0].focus();
      }
    });

    
  })()
</script>
@endsection