@extends('layouts.app')
@section('header-scripts')
    <link href="css/parsley.css" rel="stylesheet" >
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">
    <style>
        thead:before, thead:after { display: none; }
        tbody:before, tbody:after { display: none; }
        .dataTables_scroll
        {
            overflow-x: auto;
            overflow-y: auto;
        }

        th.dt-center, td.dt-center { text-align: center; }

        .panel-body {
            padding: 15px !important;
        }

        a.disabled {
            pointer-events: none;
            cursor: default;
        }
        .modal {
            z-index: 10001 !important;;
        }

        #example_ddl {
            position: relative;
            top: 8px;
        }

        #example_ddl5 {
            position: relative;
            top: 5px;
        }

        #example_ddl2, #example_ddl3, #example_ddl4, #example_ddl1 {
            margin-right: 5px !important;
        }

        .upArrow, .edit {
            margin-right: 2px;
        }

        .dtMoveUp {
            margin-right: 2px;
        }

        .my_custom {
            position: relative;
            left: 16px;
        }

    </style>
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
                    @if(Session::has('success'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('success') !!}</em></div>
                    @elseif(Session::has('error'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('error') !!}</em></div>
                    @endif
                        <div id="result" style="display: none;"></div>
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Checkbook Series</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                        </div>
                                        <div class="col-xs-6 text-right">
                                        @if(\Auth::user()->checkAccessById(28, 'A'))
                                            <a href="#" class="pull-right  addCheckbook">Add Checkbook</a>
                                        @endif
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Full</th>
                                            <th>Check Start</th>
                                            <th>Check End</th>
                                            <th>Orders</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal add new bank -->
    <div id="addNewCheckbook" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Add new Checkbook Series</h5>
                </div>
                <form class="form-horizontal" action="{{ url('/checkbooks') }}" METHOD="POST" id="checkbookForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 nopadding">
                                <label for="">Account Number:</label>
                                <span class="accNO">@if(isset($banks[0])){{ $banks[0]->accountNo }} @endif</span>
                            </div>
                            <div class="col-md-12 nopadding">
                                <label for="">Bank Code</label>
                                <span class="bankCO">@if(isset($banks[0])) {{ $banks[0]->bankNameCode }} @endif</span>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="startingNum">Starting Number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="startingNum" name="startingNum" type="text" class="form-control input-md" data-parsley-type="digits"
                                       data-parsley-required-message="Starting Number is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 8 characters"
                                       data-parsley-maxlength="8" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="endingNum">Ending Number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="endingNum" name="endingNum" type="text" class="form-control input-md" data-parsley-type="digits"
                                       data-parsley-required-message="Ending Number is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 8 characters"
                                       data-parsley-maxlength="8" required="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                {!! csrf_field() !!}
                                <input type="hidden" name="accountId" value=" @if(isset($banks[0])){{ $banks[0]->bank_acct_id }}  @endif">
                                <button type="button" class="btn btn-success pull-right" id="submit_by_ajax">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for adding new bank -->


    <!-- Modal for editing checkbook -->
    <div id="editCheckbook" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Add new Checkbook Series</h5>
                </div>
                <form class="form-horizontal" action="{{ url('/checkbooks') }}" METHOD="POST" id="editCheckbookForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 nopadding">
                                <label for="">Account Number:</label>
                                <span class="accNO"> @if(isset($banks[0])){{ $banks[0]->accountNo }} @endif</span>
                            </div>
                            <div class="col-md-12 nopadding">
                                <label for="">Bank Code</label>
                                <span class="bankCO">@if(isset($banks[0])){{ $banks[0]->bankNameCode }} @endif</span>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="editStartingNum">Starting Number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="editStartingNum" name="editStartingNum" type="text" class="form-control input-md" data-parsley-type="digits"
                                       data-parsley-required-message="Starting Number is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 8 characters"
                                       data-parsley-maxlength="8" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="editEndingNum">Ending Number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="editEndingNum" name="editEndingNum" type="text" class="form-control input-md" data-parsley-type="digits"
                                       data-parsley-required-message="Ending Number is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 8 characters"
                                       data-parsley-maxlength="8" required="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="glyphicon glyphicon-arrow-left"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                {!! csrf_field() !!}
                                <input type="hidden" name="editAccountId" value="@if(isset($banks[0])){{ $banks[0]->bank_acct_id }}  @endif">
                                <button type="submit" class="btn btn-success pull-right">Update</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for editing checkbook -->

    <!-- Modal delete item from inventory -->
    <div class="modal fade" id="confirm-delete-account" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <form action="" method="POST" id="deleteAccount" >
                    <div class="modal-body">
                        <p class="text-center">You are about to delete one track, this procedure is irreversible.</p>
                        <p class="text-center">Do you want to proceed deleting <span style="font-weight: bold" class="bankOfAccount"></span> -
                            <span style="font-weight:bold" class="accountToDelete"></span> ?</p>
                        <p class="debug-url"></p>
                    </div>

                    <div class="modal-footer">
                        <input type="hidden" class="deleteAccountId" >
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger btn-ok" class="deleteItem">Delete</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end Modal -->

@endsection

@section('footer-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.7.2/parsley.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <script>
        (function($){

            var __previous = "";
            $('#checkbookForm, #editCheckbookForm').parsley();

            var mainTable = $('#myTable').DataTable({
                initComplete: function () {
                    $('<label for="">Filters:</label>').appendTo("#example_ddl");
                    var corporationID = $('<select class="form-control"><option value="@if(isset($corporations[0])){{ $corporations[0]->corp_id }} @endif" selected>@if(isset($corporations[0])){{ $corporations[0]->corp_name }} @else N/A @endif</option></select>')
                        .appendTo('#example_ddl2');
                    var cntCorp = 0;
                    @if(is_object($corporations))
                    @foreach($corporations as $key => $val)
                    if(cntCorp != 0){
                        corporationID.append('<option value="{{ $val->corp_id }}">{{ $val->corp_name }}</option>');
                    }
                    cntCorp++;
                            @endforeach
                        @endif

                    var satelliteBranches = $('<select class="form-control"><option value="@if(isset($satelliteBranch[0])){{ $satelliteBranch[0]->Branch }} @endif">@if(isset($satelliteBranch[0])){{ $satelliteBranch[0]->ShortName }} @else N/A @endif</option></select>')
                            .appendTo('#example_ddl1');
                    var cntSatellite = 0;
                    @if(is_object($satelliteBranch))
                    @foreach($satelliteBranch as $key => $val)
                    if(cntSatellite != 0){
                        satelliteBranches.append('<option value="{{ $val->Branch }}">{{ $val->ShortName }}</option>');
                    }
                    cntSatellite++;

                            @endforeach
                        @endif




                    var branchStatus = $('<select class="form-control"><option value="1" selected>Active</option></select>')
                            .appendTo('#example_ddl3');
                    branchStatus.append('<option value="0">Inactive</option>');

                    var branches = $('<select class="form-control"><option value="@if(isset($banks[0])){{ $banks[0]->bank_acct_id }} @endif">@if(isset($banks[0])){{ $banks[0]->account_info }} @else N/A @endif</option></select>')
                        .appendTo('#example_ddl4');
                    var cntBranches = 0;
                    @if(is_object($banks))
                    @foreach($banks as $key => $val)
                        if(cntBranches != 0){
                        branches.append('<option value="{{ $val->bank_acct_id }}">{{ $val->account_info }}</option>');
                    }
                    cntBranches++;
                    @endforeach
                        @endif

                    var mainStatus = $('<input class="" type="checkbox"><label value="">Main</label>')
                            .appendTo('#example_ddl5');
                },
                "processing": true,
                "serverSide": true,
                "ajax" : {
                    type: "POST",
                    url: "{!! route('checkbooks.get_checkbooks') !!}",
                    data: function (d) {
                        @if(is_object($corporations))
                            d.dataStatus = $('#example_ddl3 select option:selected').val() == undefined ? 1 : $('#example_ddl3 select option:selected').val();
                            @if(isset($corporations[0]))
                                d.corpId = $('#example_ddl2 select option:selected').val() == undefined ? '{{ $corporations[0]->corp_id }}' : $('#example_ddl2 select option:selected').val();
                            @else
                                d.corpId = $('#example_ddl2 select option:selected').val();
                            @endif

                            @if(isset($banks[0]))
                                d.branch = $('#example_ddl4 select option:selected').val() == undefined ? '{{ $banks[0]->bank_acct_id }}' : $('#example_ddl4 select option:selected').val();
                            @else
                                d.branch = $('#example_ddl4 select option:selected').val();
                            @endif

                            @if(isset($satelliteBranch[0]))
                                d.sysBranch = $('#example_ddl1 select option:selected').val() == undefined ? '{{  $satelliteBranch[0]->Branch }}' : $('#example_ddl1 select option:selected').val();
                            @else
                                d.sysBranch = $('#example_ddl1 select option:selected').val();
                            @endif
                            d.MainStatus = $('#example_ddl5 input').is(":checked");
                        @endif
                    }
                },
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                "<'row my_custom'<'col-sm-2.pull-left'<'#example_ddl'>><'col-sm-2.pull-left'<'#example_ddl2'>><'col-sm-2.pull-left'<'#example_ddl3'>><'col-sm-2.pull-left'<'#example_ddl1'>><'col-sm-2.pull-left'<'#example_ddl4'>><'col-sm-2.pull-left'<'#example_ddl5'>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            var checked = "";
                            if(row.used == 1) checked = "checked";
                            return '<input type="checkbox" '+ checked +' disabled>';
                        },
                        "targets": 0
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.chknum_start;
                        },
                        "targets": 1
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.chknum_end;
                        },
                        "targets": 2
                    },
                    {
                        "render": function (data, type, full, meta) {
                                var $span = $('<span></span>');
                                var info = mainTable.page.info();

                                if (meta.row > 0) {
                                    $('<a name="edit" data-used="'+meta.row.used+'" class="btn btn-primary btn-md dtMoveUp">' +
                                        '<i class="glyphicon glyphicon-arrow-up"></i></a>').appendTo($span);
                                }else if(info.page > 0){
                                    $('<a name="edit" data-used="'+meta.row.used+'" class="btn btn-primary btn-md dtMoveUp">' +
                                        '<i class="glyphicon glyphicon-arrow-up"></i></a>').appendTo($span);
                                }

                                $( '<a name="edit" data-used="'+meta.row.used+'" class="btn btn-primary btn-md dtMoveDown">' +
                                    '<i class="glyphicon glyphicon-arrow-down"></i>').appendTo($span);

                                return $span.html();
                        },
                        "targets": 3
                    },
                    {
                        "render": function ( data, type, row ) {
                            var checkAccess = '<?php  if(\Auth::user()->checkAccessById(28, "E")) {  echo 1; }else{ echo 0; } ?>';
                            var checkAccessDel = '<?php  if(\Auth::user()->checkAccessById(28, "D")) {  echo 1; }else{ echo 0; } ?>';
                            var optionClass = "";
                            var optionClassDel = "";
                            if(checkAccess == 0 || row.used == 1) { optionClass = 'disabled' };
                            if(checkAccessDel == 0) { optionClassDel = 'disabled' };
                            return '<a name="edit" class="btn btn-primary btn-md edit '+optionClass+'">' +
                                '<i class="fas fa-pencil-alt"></i><span style="display: none;">'+row.txn_no+'</span></a>' +
                                '<a href="#" name="delete" class="btn btn-danger btn-md delete '+optionClassDel+'"><i class="glyphicon glyphicon-trash"></i></a>';

                        },
                        "targets": 4
                    },

                    { "className": "dt-center", "orderable": false, "width": "5%", "targets": 0},
                    { "width": "25%", "targets": 1},
                    { "width": "25%", "targets": 2},
                    { "orderable": false, "width": "10%", "className": "dt-center", "targets": [3, 4] },
                ],
                "columns": [
                    { "data": "chknum_start" },
                    { "data": "chknum_end" },
                ],
                'drawCallback': function (settings) {

                    var info = mainTable.page.info();
                    $('#myTable tr:last .dtMoveDown').remove();

                    // Remove previous binding before adding it
                    $('.dtMoveUp').unbind('click');
                    $('.dtMoveDown').unbind('click');

                    // Bind clicks to functions
                    $('.dtMoveUp').click(moveUp);
                    $('.dtMoveDown').click(moveDown);
                }
            });



            $('.dataTable').wrap('<div class="dataTables_scroll" />');

            // Move the row up
            function moveUp() {
                var tr = $(this).parents('tr');
                moveRow(tr, 'up');
            }

            // Move the row down
            function moveDown() {
                var tr = $(this).parents('tr');
                moveRow(tr, 'down');
            }

            // Move up or down (depending...)
            function moveRow(row, direction) {
                var index = mainTable.row(row).index();
                var order = -1;
                if (direction === 'down') {
                    order = 1;
                }

                var data1 = mainTable.row(index).data();
                //data1.order_num += order;

                var rowId = data1.txn_no;
                var order_num = data1.order_num;

                var data2 = mainTable.row(index + order).data();
                //data2.order_num -= order;

                var rowId2 = data2.txn_no;
                var order_num2 = data2.order_num;

                //edit the order of the column
                $.ajax({
                    method: 'POST',
                    url: "{!! route('checkbooks.edit_row_order') !!}",
                    data: { rowId : rowId, rowId2 : rowId2, order_num : order_num, order_num2 : order_num2 },
                    success: function () {
                        mainTable.ajax.reload();
                    }
                })
            }

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(2)').text();
                $('#confirm-delete').find('.serviceId').val(id);
                $('#confirm-delete .itemToDelete').text(itemCode);
                $('#confirm-delete form').attr('action', 'brands/'+id);
                $('#confirm-delete').modal("show");
            });

            $('#accNoSelect').on('change', function () {
                var id = $('#accNoSelect option:selected').val();

                if(id != "") $('#accNoDisplay').text('Bank Code: '+id);

            })

            $('#example_ddl5').on("click", function() {
                if($('#example_ddl5 input').is(':checked')){
                    $('#example_ddl1 select').attr('disabled', true).css({"background-color":"#FFF", "color":"#FFF"});

                    var options = $('#example_ddl4 select');
                    options.empty();
                    var cnt = 0;

                    $.ajax({
                        method: 'POST',
                        url: "{!! route('checkbooks.get_accounts_for_main') !!}",
                        success: function (data) {
                            data = JSON.parse(data);
                            $.each(data, function (key, val) {
                                options.append('<option value="'+val.bank_acct_id+'">'+val.account_info+'</option>');
                                cnt++;
                            });

                            if(cnt > 0){
                                var code = $('#example_ddl4 option:selected').text();
                                var id = $('#example_ddl4 option:selected').val();

                                var splitted = code.split(/-(.+)/);
                                $('.bankCO').text(splitted[0]);
                                $('.accNO').text(splitted[1]);
                                $('input[name="accountId"]').val(id);
                            }

                            if(cnt == 0){
                                options.append('<option value="">No options</option>');
                            }
                        }
                    })
                }else{
                    $('#example_ddl1 select').attr('disabled', false).css("color", "#333");
                    lastColumnReload();
                }
                
                function loadTable() {
                    mainTable.ajax.reload();
                }
                setTimeout(loadTable, 200);
            });

            $('#example_ddl2').on('click', function () {
                    __previous = $('#example_ddl2 select option:selected').val();
            }).change(function () {
                if(!$('#example_ddl5 input').is(':checked')){
                    var dataStatus = $('#example_ddl3 select option:selected').val();
                    var corpId = $('#example_ddl2 select option:selected').val();

                    var options = $('#example_ddl1 select');
                    options.empty();
                    //get branches
                    var cnt = 0;
                    $.ajax({
                        method: 'POST',
                        url: "{!! route('checkbooks.get_branches') !!}",
                        data: { status : dataStatus, corpId : corpId },
                        success: function (data) {
                            data = JSON.parse(data);
                            $.each(data, function (key, val) {
                                cnt++;
                                options.append('<option value="'+val.Branch+'">'+val.ShortName+'</option>');
                            })
                            if(cnt != 0){
                                lastColumnReload();
                            }
                            if(cnt == 0){
                                options.append('<option value="">No options</option>');
                            }
                        }

                    })
                    setTimeout(() => {
                        mainTable.ajax.reload();
                    }, 200);
                    
                }else{
                    $('#example_ddl2 select').val(__previous);
                }
            });

            $('#example_ddl3').on('click', function () {
                __previous = $('#example_ddl3 select option:selected').val();
            }).change(function () {
                if(!$('#example_ddl5 input').is(':checked')){
                    var dataStatus = $('#example_ddl3 select option:selected').val();
                    var corpId = $('#example_ddl2 select option:selected').val();

                    var options = $('#example_ddl1 select');
                    options.empty();
                    //get branches
                    var cnt = 0;
                    $.ajax({
                        method: 'POST',
                        url: 'checkbooks/get-branches',
                        data: { status : dataStatus, corpId : corpId },
                        success: function (data) {
                            data = JSON.parse(data);
                            $.each(data, function (key, val) {
                                cnt++;
                                options.append('<option value="'+val.Branch+'">'+val.ShortName+'</option>');
                            })
                            if(cnt != 0){
                                lastColumnReload();
                            }
                            if(cnt == 0){
                                options.append('<option value="">No options</option>');
                            }
                        }

                    })
                    mainTable.ajax.reload();
                }else{
                    $('#example_ddl3 select').val(__previous);
                }
            })

            $('#example_ddl1').on('change', function () {
                var branchId = $('#example_ddl1 select option:selected').val();
                
                var options = $('#example_ddl4 select');
                options.empty();
                //get branches
                var cnt = 0;
                $.ajax({
                    method: 'POST',
                    url: "{!! route('checkbooks.get_banks') !!}",
                    data: { branchId : branchId },
                    success: function (data) {
                        data = JSON.parse(data);
                        $.each(data, function (key, val) {
                            cnt++;
                            options.append('<option value="'+val.bank_acct_id+'">'+val.account_info+'</option>');
                        })

                        if(cnt > 0){
                            var code = $('#example_ddl4 option:selected').text();
                            var id = $('#example_ddl4 option:selected').val();

                            var splitted = code.split(/-(.+)/);
                            $('.bankCO').text(splitted[0]);
                            $('.accNO').text(splitted[1]);
                            $('input[name="accountId"]').val(id);
                        }

                        if(cnt == 0){
                            options.append('<option value="">No options</option>');
                        }
                    }

                })
                setTimeout(() => {
                    mainTable.ajax.reload();
                }, 200);
            })

            //reload
            function lastColumnReload(){

                    var branchId = $('#example_ddl1 select option:selected').val();

                    var options = $('#example_ddl4 select');
                    options.empty();
                    //get branches
                    var cnt = 0;
                    $.ajax({
                        method: 'POST',
                        url: "{!! route('checkbooks.get_banks') !!}",
                        data: { branchId : branchId },
                        success: function (data) {
                            data = JSON.parse(data);
                            $.each(data, function (key, val) {
                                cnt++;
                                options.append('<option value="'+val.bank_acct_id+'">'+val.account_info+'</option>');
                            })

                            if(cnt > 0){
                                var code = $('#example_ddl4 option:selected').text();
                                var id = $('#example_ddl4 option:selected').val();

                                var splitted = code.split(/-(.+)/);
                                $('.bankCO').text(splitted[0]);
                                $('.accNO').text(splitted[1]);
                                $('input[name="accountId"]').val(id);
                            }
                            if(cnt == 0){
                                options.append('<option value="">No options</option>');
                            }
                        }

                    })
                    mainTable.ajax.reload();
            }


            $('#example_ddl4').on('change', function () {
                var id = $('#example_ddl4 option:selected').val();
                var code = $('#example_ddl4 option:selected').text();

                var splitted = code.split(/-(.+)/);
                $('.bankCO').text(splitted[0]);
                $('.accNO').text(splitted[1]);
                $('input[name="accountId"]').val(id);

                mainTable.ajax.reload();
            });

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(2)').text();
                var account  = $(this).closest('tr').find('td:nth-child(3)').text();
                $('#confirm-delete-account').find('.deleteAccountId').val(id);
                $('#confirm-delete-account .bankOfAccount').text(itemCode);
                $('#confirm-delete-account .accountToDelete').text(account);
                $('#confirm-delete-account').modal("show");
            });

            $(document).on('submit', '#deleteAccount', function (e) {
                e.preventDefault();

                var accountID  = $('.deleteAccountId').val();

                $.ajax({
                    url: "checkbooks/delete",
                    method: "POST",
                    data: { id : accountID },
                    success: function (data) {
                        if(data == "success"){
                            $('#confirm-delete-account').modal("toggle");

                            $("#result").html('<div class="alert alert-success col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                                '</span><em>&nbspCheck book series deleted successfully!</em></div></div>');
                            $('#result').fadeIn();
                            $("#result").delay(3000).fadeOut("slow");
                            mainTable.ajax.reload();
                        }else{
                            $('#confirm-delete-account').modal("toggle");
                            $("#result").html('<div class="alert alert-danger col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                                '</span><em>&nbspSomething went wrong!</em></div></div>');
                            $('#result').fadeIn();
                            $("#result").delay(3000).fadeOut("slow");
                        }
                    },
                    error: function () {
                        $('#confirm-delete-account').modal("toggle");
                        $("#result").html('<div class="alert alert-danger col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                            '</span><em>&nbspSomething went wrong!</em></div></div>');
                        $('#result').fadeIn();
                        $("#result").delay(3000).fadeOut("slow");
                    }
                })

            });

            $(document).on('click', '.edit', function (e) {
                e.preventDefault();
                if($(this).data("used") == 1)
                {
                    alert("Check box is full: editing not allowed");
                }
                else
                {
                    var start = $(this).closest('tr').find('td:nth-child(2)').text();
                    var end = $(this).closest('tr').find('td:nth-child(3)').text();

                    var id  = $(this).closest('td').find('span').text();

                    $('input[name="editAccountId"]').val(id);

                    $('#editStartingNum').val(start);
                    $('#editEndingNum').val(end);

                    $('#editCheckbook').modal("toggle");
                }
            });

            $(document).on('submit', '#editCheckbookForm', function (e) {
                e.preventDefault();

                var accountID  = $('input[name="editAccountId"]').val();
                var editStart = $('#editStartingNum').val();
                var editEnd = $('#editEndingNum').val();

                $.ajax({
                    method: 'POST',
                    url: 'checkbooks/edit-checkbook',
                    data: { accountId : accountID, editStart : editStart, editEnd : editEnd },
                    success: function (data) {
                        if(data == "success"){
                            $('#editCheckbook').modal("toggle");

                            $("#result").html('<div class="alert alert-success col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                                '</span><em>&nbspAccount updated successfully!</em></div></div>');
                            $('#result').fadeIn();
                            $("#result").delay(3000).fadeOut("slow");
                            mainTable.ajax.reload();
                        }else{
                            $('#editCheckbook').modal("toggle");
                            $("#result").html('<div class="alert alert-danger col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                                '</span><em>&nbspSomething went wrong!</em></div></div>');
                            $('#result').fadeIn();
                            $("#result").delay(3000).fadeOut("slow");
                        }
                    },
                    error: function () {
                        $('#editCheckbook').modal("toggle");
                        $("#result").html('<div class="alert alert-danger col-md-8 col-md-offset-2"> <span class="glyphicon glyphicon-remove">' +
                            '</span><em>&nbspSomething went wrong!</em></div></div>');
                        $('#result').fadeIn();
                        $("#result").delay(3000).fadeOut("slow");
                    }
                })
            })

            $(document).on('click', '.addCheckbook', function (e) {
                e.preventDefault();
                var id = $('#example_ddl4 option:selected').val();
                if(id == ""){
                    $.confirm({
                        icon: 'glyphicon glyphicon-exclamation-sign',
                        title: 'Invalid selection!',
                        content: 'There is not an account number where a checkboox will be added!',
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            close: function () {
                            }
                        }
                    });
                }else{
                    $('#addNewCheckbook').modal("toggle");
                }
            })

            $(document).on('click', '#submit_by_ajax', function(e){
                e.preventDefault();
                $.ajax({
                    method: 'POST',
                    url: $('#addNewCheckbook form').attr('action'),
                    data: $('#addNewCheckbook form').serialize(),
                    success: function () {
                        $('#startingNum').val('');
                        $('#endingNum').val('');
                        $('#addNewCheckbook').modal("hide");
                        mainTable.ajax.reload();
                    }
                })
            })
        })(jQuery);
    </script>
@endsection