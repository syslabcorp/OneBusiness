@extends('layouts.app')
@section('header-scripts')
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <style>
        thead:before, thead:after { display: none; }
        tbody:before, tbody:after { display: none; }
        .dataTables_scroll { overflow-x: auto; overflow-y: auto; }

        th.dt-center, td.dt-center { text-align: center; }

        .panel-body { padding: 15px !important; }

        a.disabled { pointer-events: none; cursor: default; color: transparent; }

        .modal { z-index: 10001 !important; }

        #feedback { font-size: 14px; }
            
        /* css for selectable */
        .selectable .ui-selecting { background: #b8d4ea; }
        .selectable .ui-selected { background: #76acd6;}
        .selectable { list-style-type: none; margin: 0; padding: 0; }
        .selectable li { padding: 0.4em; font-size: 14px; border-bottom-width: 0px;}
        .selectable li:last-child {
          border-bottom-width: 1px;
        }
        /* end of -------- css for selectable */
        
        /* css for fixed first column of the table (for confStep 2) */

        .customThCss {text-align: center; vertical-align: middle; width: 300px !important;}
        .rightBorder {border-right: 2px solid #ccc;}
        .priceField {width: 50px; text-align: center;}

        table.fixedColumn > thead > tr { padding-top: 20px; padding-bottom: 20px;}
        table.fixedColumn td:not(.rightBorder), table.fixedColumn th:not(.rightBorder) {
          border-right: 1px solid #ccc;
        }
        table.fixedColumn > tbody > tr > td {text-align: right;}
        table.fixedColumn > tbody > tr > td input[type="checkbox"]{
          display: block;
          margin: 0 auto;
        }
        table.fixedColumn > thead > tr th {
          text-align: center;
        }

        table.fixedColumn > thead > tr + tr th {
          font-weight: normal;
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
        table.fixedColumn .min-width {
          min-width: 80px;
          display: block; 
        }

        table.fixedColumn tr>th:nth-child(1), table.fixedColumn tr>th:nth-child(2),
        table.fixedColumn tr>td:nth-child(1), table.fixedColumn tr>td:nth-child(2) {
          position: sticky;
          background: #FFF;
          width: 100px;
          box-shadow: 0px 0px 1px #aaa;
        }
        table.fixedColumn tr.ui-selected>td:nth-child(1), table.fixedColumn tr.ui-selected>td:nth-child(2) {
          background: #76acd6;
        }
        table.fixedColumn tr>th:nth-child(1), table.fixedColumn tr>td:nth-child(1) {
          text-align: left;
          left: 0;
        }
        table.fixedColumn tr>th:nth-child(2), table.fixedColumn tr>td:nth-child(2) {
          left: 100px;
        }

        .selectedRow input {
          border: none;
          background: none;
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
        .table .price-col .price, .table .points-col .points {
          min-height: 32px;
          width: 100px;
          display: inline-block;
        }
        .table .editable .price, .table .editable .points {
          display: none;
        }
        .table .editable .priceField {
          display: inline-block;
          width: 100px;
        }

        #retailItemPCAppWrapper, #retailItemPCAppWrapper select, #retailItemPCAppWrapper select option {font-size: 14px !important;}


    </style>
@endsection
@section('content')
    <div class="container-fluid" id="retailItemPCAppWrapper">
        <div class="row">
            <div id="togle-sidebar-sec" class="active">
                <!-- Sidebar -->
                <div id="sidebar-togle-sidebar-sec">
                    <ul id="sidebar_menu" class="sidebar-nav">
                        <li class="sidebar-brand"><a id="menu-toggle" href="#">Menu <span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
                    </ul>
                    <div class="sidebar-nav" id="sidebar">
                        <div id="treeview_json"></div>
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
                            <strong>Retail Items Pricing</strong>
                          </div>
                          <div class="panel-body">
                              <div>
                                  <div class="row">
                                      
                                    <div class="col-sm-7">
                                      <div class="row">
                                        <div class="col-sm-6">
                                          <div class="form-group points-box">
                                            <label>Points per peso (SRP)</label>
                                            <input type="number" class="form-control" name="points" step="1"
                                              {{ \Auth::user()->checkAccessById(36, "E") ? '' : 'disabled' }}>
                                          </div>
                                          <button type="button" class="btn-set-points btn btn-success btn-sm"
                                            {{ \Auth::user()->checkAccessById(36, "E") ? '' : 'disabled' }}>Set Points</button>
                                        </div>
                                        <div class="col-sm-6">
                                          <div class="form-group price-box">
                                            <label>Price</label>
                                            <input type="number" name="price" class="form-control" {{ \Auth::user()->checkAccessById(36, "E") ? '' : 'disabled' }}>
                                          </div>
                                          <button type="button" class="btn btn-set-price btn-success btn-sm"
                                            {{ \Auth::user()->checkAccessById(36, "E") ? '' : 'disabled' }}>Set Price</button>
                                        </div>
                                      </div>
                                    </div>
                                    <div class="col-sm-5">
                                      <div class="row">
                                        <div class="col-xs-6">
                                          <button type="button" class="btn-set-active btn btn-success btn-sm" style="width:100%;"
                                            {{ \Auth::user()->checkAccessById(36, "E") ? '' : 'disabled' }}>Set Active</button>
                                        </div>
                                        <div class="col-xs-6">
                                          <button type="button" class="btn-set-redeem btn btn-info btn-sm" style="width:100%;"
                                            {{ \Auth::user()->checkAccessById(36, "E") ? '' : 'disabled' }}>Set Redeemable</button>
                                        </div>
                                      </div>
                                      <div class="row" style="margin-top: 5px;">
                                        <div class="col-xs-6">
                                          <button type="button" class="btn-unset-active btn btn-success btn-sm" style="width:100%;"
                                            {{ \Auth::user()->checkAccessById(36, "E") ? '' : 'disabled' }}>Unset Active</button>
                                        </div>
                                        <div class="col-xs-6">
                                          <button type="button" class="btn-unset-redeem btn btn-info btn-sm" style="width:100%;"
                                            {{ \Auth::user()->checkAccessById(36, "E") ? '' : 'disabled' }}>Unset Redeemable</button>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <hr>
                                  <div class="text-right" style="margin-bottom: 10px;">
                                    <button type="button" class="btn-edit btn btn-primary btn-sm"
                                      {{ \Auth::user()->checkAccessById(36, "E") ? '' : 'disabled' }}>Edit Details</button>
                                  </div>
                              </div>

                              <div class="table-responsive">
                                <div class="bootstrap-table">
                                    <div class="fixed-table-container" style="padding-bottom: 0px;">
                                        <div class="fixed-table-body">
                                          <form action="{{ route('retail-items-price-conf.store', ['corpID' => $corpID, 'item_ids' => $item_ids, 'branch_ids' => $branch_ids]) }}"
                                                method="POST">
                                                {{ csrf_field() }}
                                            <table id="table" class="table table-boderred fixedColumn">
                                                <thead>
                                                    <tr>
                                                      <th  style="min-width: 100px;width: 100px;">
                                                        Item Code
                                                      </th>
                                                      <th style="min-width: 100px;width: 100px;" class="rightBorder">
                                                        Last Cost
                                                      </th>
                                                      @foreach($branches as $branch)
                                                      <th class="customThCss rightBorder" colspan="6">
                                                        {{ $branch->ShortName }}
                                                      </th>
                                                      @endforeach
                                                    </tr>
                                                    <tr>
                                                      <th></th>
                                                      <th class="rightBorder"></th>
                                                      @foreach($branches as $branch)
                                                        <th>Active</th>
                                                        <th>Redeem</th>
                                                        <th>Points</th>
                                                        <th>SRP</th>
                                                        <th>% MarkUp</th>
                                                        <th class="rightBorder">
                                                          Net
                                                        </th>
                                                      @endforeach
                                                    </tr>
                                                </thead>
                                                <tbody class="selectable">
                                                  @foreach($stocks as $stock)
                                                  <tr class="ui-widget-content"> 
                                                    
                                                    <td>{{ $stock->ItemCode }}</td>
                                                    <td class="rightBorder last-cost">{{ number_format($stock->LastCost, 2) }}</td>
                                                    @foreach($branches as $branch)
                                                    @php
                                                      $item = $itemModel->where('item_id', '=', $stock->item_id)->where('Branch', '=', $branch->Branch)->first()
                                                    @endphp
                                                    <td>
                                                      <input type="hidden" name="items[{{$stock->item_id}}][{{$branch->Branch}}][ItemCode]" value="{{ $stock->ItemCode }}">
                                                      <input type="hidden" name="items[{{$stock->item_id}}][{{$branch->Branch}}][Active]" value="0">
                                                      <input type="checkbox" name="items[{{$stock->item_id}}][{{$branch->Branch}}][Active]" 
                                                      class="active" onclick="return false;" value="1" {{ $item && $item->Active ? 'checked' : '' }}>
                                                    </td>
                                                    <td>
                                                      <input type="hidden" name="items[{{$stock->item_id}}][{{$branch->Branch}}][pts_redeemable]" value="0">
                                                      <input type="checkbox" name="items[{{$stock->item_id}}][{{$branch->Branch}}][pts_redeemable]" 
                                                      class="redeemable" onclick="return false;" value="1" {{ $item && $item->pts_redeemable ? 'checked' : '' }}>
                                                    </td> 
                                                    <td class="points-col">
                                                      <input type="number" name="items[{{$stock->item_id}}][{{$branch->Branch}}][pts_price]" 
                                                      value="{{ $item ? $item->pts_price : '0' }}" class="priceField" step="1">
                                                      <span class="points">{{ $item ? number_format($item->pts_price) : '0.00' }}</span>
                                                    </td> 
                                                    <td class="price-col">
                                                      <input type="number" name="items[{{$stock->item_id}}][{{$branch->Branch}}][Sell_Price]" 
                                                      value="{{ $item ? $item->Sell_Price : '0.00' }}" class="priceField">
                                                      <span class="price">{{ $item ? number_format($item->Sell_Price, 2) : '0.00' }}</span>
                                                    </td> 
                                                    <td>
                                                      {{ $item ? number_format(($item->Sell_Price - $stock->LastCost) / $stock->LastCost * 100, 2) : '0.00' }}
                                                    </td> 
                                                    <td class="rightBorder">
                                                      {{ $item ? number_format($item->Sell_Price - $stock->LastCost, 2) : '0.00' }}
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
                              <a href="{{ route('retail-items-price-conf.index') }}" class="btn btn-default btn-md pull-left">Back</a> 

                              <div class="pull-right">
                                <button type="button" class="btn btn-primary btn-md btn-save" style="margin-left: 5px;"
                                  {{ \Auth::user()->checkAccessById(36, "E") ? '' : 'disabled' }}>Save</button>
                              </div>

                              <div class="clearfix"></div>
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
    
    $('body').on('click', '.btn-copy', function(event) {
      $('.modal-copy .table tbody').html('');

      $('.modal-copy').modal('show');
    });

    $('.price-col .priceField').change(function(event) {
      var lastCost = parseFloat($(this).parents('tr').find('.last-cost').text());
      var net = parseFloat($(this).val()) - lastCost;
      var markUp = net / lastCost * 100;

      $(this).parents('td').next().next().text(net.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
      $(this).parents('td').next().text(markUp.toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
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
        $('.btn-set-active, .btn-unset-active, .btn-set-price, input[name="price"], .btn-set-redeem, .btn-unset-redeem').prop('disabled', false);
        $('.btn-set-points, input[name="points"]').prop('disabled', false);
        $(this).text('Edit Details');
      }else {
        $('.table .ui-selected').addClass('editable');
        $('.table .ui-selected.editable input[type="checkbox"]').attr('onclick', '');
        $('.btn-set-active, .btn-unset-active, .btn-set-price, input[name="price"], .btn-set-redeem, .btn-unset-redeem').prop('disabled', true);
        $('.btn-set-points, input[name="points"]').prop('disabled', true);
        $(this).text('Save Row');
      }
    });

    $('.btn-set-points').click(function(event) {
      $('.points-box .error').remove();
      if($.isNumeric($('input[name="points"]').val())) {
        $('.table .ui-selected .points-col').each(function(index, el) {
          var points = parseFloat($('input[name="points"]').val()) * parseFloat($(this).next().find('input').val());
          $(this).find('input').val(points.toFixed(2));
          $(this).find('.points').text(points.toFixed(0).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
        });
      }else {
        $('.points-box').append('<span class="error">Please input number</span>');
      }
    });

    $('.btn-set-price').click(function(event) {
      $('.price-box .error').remove();
      if($.isNumeric($('input[name="price"]').val())) {
        $('.table .ui-selected .price-col input').val(parseFloat($('input[name="price"]').val()).toFixed(2));
        $('.table .ui-selected .price-col input').change();
        $('.table .ui-selected .price-col .price').text(parseFloat($('input[name="price"]').val()).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
      }else {
        $('.price-box').append('<span class="error">Please input number</span>');
      }
    });

    $('.btn-set-active').click(function(event) {
      $('.table .ui-selected input[type="checkbox"].active').prop('checked', true);
    });

    $('.btn-unset-active').click(function(event) {
      $('.table .ui-selected input[type="checkbox"].active').prop('checked', false);
    });

    $('.btn-set-redeem').click(function(event) {
      $('.table .ui-selected input[type="checkbox"].redeemable').prop('checked', true);
    });

    $('.btn-unset-redeem').click(function(event) {
      $('.table .ui-selected input[type="checkbox"].redeemable').prop('checked', false);
    });

    $('.table .price-col .priceField').change(function(event) {
      $(this).parents('td').find('.price').text(parseFloat($(this).val()).toFixed(2).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
    });

    $('.table .points-col .priceField').change(function(event) {
      $(this).parents('td').find('.points').text(parseFloat($(this).val()).toFixed(0).replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
    });

  </script>
@endsection