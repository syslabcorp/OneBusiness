@extends('layouts.app')
@section('header-scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        thead:before, thead:after { display: none; }
        tbody:before, tbody:after { display: none; }
        .dataTables_scroll { overflow-x: auto; overflow-y: auto; }


        .panel-body { padding: 15px !important; }

        a.disabled { pointer-events: none; cursor: default; color: transparent; }

        .modal { z-index: 10001 !important; }

        #feedback { font-size: 14px; }
            
        /* css for selectable */
        .selectable .ui-selecting { background: #b8d4ea; }
        .selectable .ui-selected { background: #76acd6;}
        .selectable { list-style-type: none; margin: 0; padding: 0; }
        .selectable li { margin: 5px; padding: 0.4em; font-size: 14px; }
        /* end of -------- css for selectable */
        
        /* css for fixed first column of the table (for confStep 2) */

        .customThCss {text-align: center; vertical-align: middle; width: 300px !important;}
        .rightBorder {border-right: 2px solid #ccc;}

        table.fixedColumn > thead > tr { padding-top: 20px; padding-bottom: 20px;}
        table.fixedColumn td:not(.rightBorder), table.fixedColumn th:not(.rightBorder) {
          border-right: 1px solid #ccc;
        }
        table.fixedColumn > thead > tr + tr th {
          font-weight: normal;
        }
        table.fixedColumn > thead > tr th {
          text-align: center;
        }
        table.fixedColumn {
          border: 1px solid #ccc;
        }
        table.fixedColumn .childControl {
          background-color: transparent;
          background-image: none;
          border: none;
          border-radius: 4px;
          text-align: right;
        }
        
        table.fixedColumn tr>th:nth-child(1), table.fixedColumn tr>th:nth-child(2),
        table.fixedColumn tr>td:nth-child(1), table.fixedColumn tr>td:nth-child(2) {
          position: sticky;
          position: -webkit-sticky;
          background: #FFF;
          box-shadow: 1px 0px #ccc;
          box-shadow: 0px 0px 1px #aaa;
        }
        table.fixedColumn tr.ui-selected>td:nth-child(1), table.fixedColumn tr.ui-selected>td:nth-child(2) {
          background: #76acd6;
        }
        table.fixedColumn tr>th:nth-child(1), table.fixedColumn tr>td:nth-child(1) {
          width: 100px;
          left: 0;
        }
        table.fixedColumn tr>th:nth-child(2), table.fixedColumn tr>td:nth-child(2) {
          left: 120px;
          width: 200px;
        }

        .priceField {
          background-color: #fff;
          background-image: none;
          border: 1px solid #ccc;
          padding: 5px 0px;
          text-align: right;
          border-radius: 4px;
          display: none;
        }
        .table .price-col .amount {
          min-height: 32px;
          width: 100px;
          display: inline-block;
        }
        .table .editable .amount {
          display: none;
        }
        .table .editable .priceField {
          display: inline-block;
          width: 100px;
        }

        .rightBorder {border-right: 2px solid #ccc;}

        #serviceAppWrapper, #serviceAppWrapper select, #serviceAppWrapper select option {font-size: 14px !important;}

    </style>
@endsection
@section('content')
    <div class="container-fluid" id="serviceAppWrapper">
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
                    @if(Session::has('success'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('success') !!}</em></div>
                    @elseif(Session::has('error'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('error') !!}</em></div>
                    @endif

                    <div class="col-md-12 col-xs-12" style="margin-top: 20px;">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                              <strong>Service per Branch Configuration</strong>
                            </div>

                            <div class="panel-body">
                                <div class="row">
                                  <div class="col-md-4">
                                    <div class="row">
                                      <div class="col-md-2">
                                        <label style="margin-top: 6px;">Price</label>
                                      </div>
                                      <div class="col-md-8 price-box">
                                        <input type="number" name="price" class="form-control"
                                          {{ \Auth::user()->checkAccessById(37, "E") ? '' : 'disabled' }}>
                                      </div>
                                      <div class="col-md-2">
                                        <button type="button" class="btn btn-primary btn-sm btn-set-price"
                                          {{ \Auth::user()->checkAccessById(37, "E") ? '' : 'disabled' }}>Set Price</button>
                                      </div>
                                    </div>
                                  </div>
                                  <div class="col-md-8 text-right">
                                    
                                    <button type="button" class="btn btn-primary btn-sm btn-set-active"  style="margin-left: 5px;"
                                      {{ \Auth::user()->checkAccessById(37, "E") ? '' : 'disabled' }}>Set Active</button>
                                    <button type="button" class="btn btn-success btn-sm btn-unset-active" style="margin-left: 5px;"
                                      {{ \Auth::user()->checkAccessById(37, "E") ? '' : 'disabled' }}>Unset Active</button>
                                  </div>
                                </div>
                                <hr>
                                <div class="text-right" style="margin-bottom: 10px;">
                                  <button type="button" class="btn btn-primary btn-sm btn-edit"
                                    {{ \Auth::user()->checkAccessById(37, "E") ? '' : 'disabled' }}>Edit Details</button>
                                </div>
                                  <div class="table-responsive">
                                    <div class="bootstrap-table">
                                        <div class="fixed-table-container table-no-bordered" style="padding-bottom: 0px;">
                                            <div class="fixed-table-body">
                                              <form action="{{ route('services-price-conf.store', ['corpID' => $corpID, 'service_ids' => $service_ids, 'branch_ids' => $branch_ids]) }}"
                                                method="POST">
                                                {{ csrf_field() }}
                                                <table id="table" class="table fixedColumn">
                                                  <thead>
                                                    <tr>
                                                        <th style="min-width: 120px;width: 120px;">
                                                          Service Code
                                                        </th>
                                                        <th style="min-width: 200px;width: 200px;" class="rightBorder">Description</th>
                                                        @foreach($branchs as $branch)
                                                        <th class="customThCss rightBorder" colspan="2">
                                                          {{ $branch->ShortName }}
                                                        </th>
                                                        @endforeach
                                                    </tr>
                                                    <tr>
                                                      <th></th>
                                                      <th class="rightBorder"></th>
                                                      @foreach($branchs as $branch)
                                                      <th>
                                                        Price
                                                      </th>
                                                      <th class="rightBorder">
                                                        Active
                                                      </th>
                                                      @endforeach
                                                    </tr>
                                                  </thead>
                                                  <tbody class="selectable">
                                                    @foreach($services as $service)
                                                      <tr class="ui-widget-content"> 
                                                        <td>{{ $service->Serv_Code }}</td>
                                                        <td class="rightBorder">{{ $service->Description }}</td>
                                                        @foreach($branchs as $branch)
                                                        @php $item = $itemModel->where('Serv_ID', '=', $service->Serv_ID)->where('Branch', '=', $branch->Branch)->first() @endphp
                                                        <td class="text-right price-col">
                                                          <input type="number" name="items[{{$service->Serv_ID}}][{{$branch->Branch}}][Amount]" 
                                                            value="{{ $item ? $item->Amount : '0.00' }}" class="priceField">
                                                          <span class="amount">{{ $item ? number_format($item->Amount, 2) : '0.00' }}</span>
                                                        </td>
                                                        <td class="rightBorder text-center">
                                                          <input type="hidden" name="items[{{$service->Serv_ID}}][{{$branch->Branch}}][Active]" value="0">
                                                          <input type="checkbox" name="items[{{$service->Serv_ID}}][{{$branch->Branch}}][Active]" 
                                                          class="childControl" onclick="return false;" value="1" {{ $item && $item->Active ? 'checked' : '' }}>
                                                        </td> 
                                                        @endforeach
                                                      </tr>
                                                    @endforeach
                                                  </tbody>
                                                </table>
                                              </form>
                                            </div>
                                        </div>
                                    </div>
                                  </div>

                            </div>
                            <div class="panel-footer">
                              <div class="row">
                                  <a href="{{ url('/services-price-conf') }}" class="btn btn-default btn-md pull-left">Back</a> 
                                <div class="pull-right">
                                  <button type="button" class="btn btn-primary btn-md btn-save"
                                    {{ \Auth::user()->checkAccessById(37, "E") ? '' : 'disabled' }}>Save</button>
                                </div>
                              </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer-scripts')
<script type="text/javascript">
  $('.btn-set-price').click(function(event) {
    $('.price-box .error').remove();
    if($.isNumeric($('input[name="price"]').val())) {
      $('.table .ui-selected input.priceField').val(parseFloat($('input[name="price"]').val()).toFixed(2));
      $('.table .ui-selected .price-col .amount').text(parseFloat($('input[name="price"]').val()).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
    }else {
      $('.price-box').append('<span class="error">Please input number</span>');
    }
  });

  $('.btn-set-active').click(function(event) {
    $('.table .ui-selected input[type="checkbox"]').prop('checked', true);
  });

  $('.btn-unset-active').click(function(event) {
    $('.table .ui-selected input[type="checkbox"]').prop('checked', false);
  });

  $('.btn-save').click(function(event) {
    $('form').submit();
  });

  $('.btn-edit').click(function(event) {
    if($('.table .ui-selected').length == 0) {
      return;
    }

    if($('.table .editable').length > 0) {
      $('.table .ui-selected.editable input[type="checkbox"]').attr('onclick', 'return false;');
      $('.table .editable').removeClass('editable');
      $('.btn-set-active, .btn-unset-active, .btn-set-price, input[name="price"]').prop('disabled', false);
      $(this).text('Edit Details');
    }else {
      $('.table .ui-selected').addClass('editable');
      $('.table .ui-selected.editable input[type="checkbox"]').attr('onclick', '');
      $('.btn-set-active, .btn-unset-active, .btn-set-price, input[name="price"]').prop('disabled', true);
      $(this).text('Save Row');
    }
  });

  $('.table .priceField').change(function(event) {
    $(this).parents('td').find('.amount').text(parseFloat($(this).val()).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
  });
</script>
@endsection