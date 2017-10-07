@extends('layouts.app')
@section('header-scripts')
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
            color: transparent;
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

        #example_ddl2, #example_ddl3, #example_ddl4 {
            margin-right: 5px !important;
        }

    </style>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div id="togle-sidebar-sec" class="active">
                <!-- Sidebar -->
                <div id="sidebar-togle-sidebar-sec">
                    <ul id="sidebar_menu" class="sidebar-nav">
                        <li class="sidebar-brand"><a id="menu-toggle" href="#">Menu<span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
                    </ul>
                    <div class="sidebar-nav" id="sidebar">
                        <div id="treeview_json"></div>
                    </div>
                </div>

                <!-- Page content -->
                <div id="page-content-togle-sidebar-sec">
                    @if(Session::has('alert-class'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                    @elseif(Session::has('flash_message'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                    @endif
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Checkbook Series</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <a href="#" class="pull-right @if(!\Auth::user()->checkAccessById(28, "A")) disabled @endif"
                                               data-toggle="modal" data-target="#addNewCheckbook" >Add Checkbook</a>
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
                <form class="form-horizontal" action="{{ url('/checkbooks') }}" METHOD="POST">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 nopadding">
                                <label for="">Account Number:</label>
                                <span class="accNO">{{ $banks[0]->accountNo }}</span>
                            </div>
                            <div class="col-md-12 nopadding">
                                <label for="">Bank Code</label>
                                <span class="bankCO">{{ $banks[0]->bankNameCode }}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="startingNum">Starting Number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="startingNum" name="startingNum" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 col-xs-12 control-label" for="endingNum">Ending Number:</label>
                            <div class="col-md-6 col-xs-10">
                                <input id="endingNum" name="endingNum" type="text" class="form-control input-md" required="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="row">
                            <div class="col-sm-6">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fa fa-reply"></i>&nbspBack</button>
                            </div>
                            <div class="col-sm-6">
                                {!! csrf_field() !!}
                                <input type="hidden" name="accountId" value="{{ $banks[0]->bank_acct_id }}">
                                <button type="submit" class="btn btn-success pull-right">Create</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for adding new bank -->

@endsection

@section('footer-scripts')
    <script>
        (function($){

            var mainTable = $('#myTable').DataTable({
                initComplete: function () {
                    $('<label for="">Filters:</label>').appendTo("#example_ddl");
                    var corporationID = $('<select class="form-control"><option value="{{ $corporations[0]->corp_id }}" selected>{{ $corporations[0]->corp_name }}</option></select>')
                        .appendTo('#example_ddl2');
                    var cntCorp = 0;
                    @foreach($corporations as $key => $val)
                    if(cntCorp != 0){
                        corporationID.append('<option value="{{ $val->corp_id }}">{{ $val->corp_name }}</option>');
                    }
                    cntCorp++;
                            @endforeach
                    var branchStatus = $('<select class="form-control"><option value="1" selected>Active</option></select>')
                            .appendTo('#example_ddl3');
                    branchStatus.append('<option value="0">Inactive</option>');

                    var branches = $('<select class="form-control"><option value="{{ $banks[0]->bank_acct_id }}">{{ $banks[0]->account_info }}</option></select>')
                        .appendTo('#example_ddl4');
                    var cntBranches = 0;
                    @foreach($banks as $key => $val)
                        if(cntBranches != 0){
                        branches.append('<option value="{{ $val->bank_acct_id }}">{{ $val->account_info }}</option>');
                    }
                    cntBranches++;
                    @endforeach

                    var mainStatus = $('<input class="" type="checkbox"><label value="">Main</label>')
                            .appendTo('#example_ddl5');
                },
                "processing": true,
                "serverSide": true,
                "ajax" : {
                    type: "POST",
                    url: "checkbooks/get-checkbooks",
                    data: function (d) {
                        d.dataStatus = $('#example_ddl3 select option:selected').val() == undefined ? 1 : $('#example_ddl3 select option:selected').val();
                        d.corpId = $('#example_ddl2 select option:selected').val() == undefined ? '{{ $corporations[0]->corp_id }}' : $('#example_ddl2 select option:selected').val();
                        d.branch = $('#example_ddl4 select option:selected').val() == undefined ? '{{ $banks[0]->bank_acct_id }}' : $('#example_ddl4 select option:selected').val();
                        d.MainStatus = $('#example_ddl5 input').is(":checked");

                    }
                },
                stateSave: true,
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                "<'row'<'col-sm-2.pull-left'<'#example_ddl'>><'col-sm-2.pull-left'<'#example_ddl2'>><'col-sm-2.pull-left'<'#example_ddl3'>><'col-sm-2.pull-left'<'#example_ddl4'>><'col-sm-2.pull-left'<'#example_ddl5'>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            var checked = "";
                            if(row.used == 1) checked = "checked";
                            return '<input type="checkbox" '+ checked +'>';
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
                        "render": function ( data, type, row ) {
                            return '<a href="satellite-branch/1/edit" name="edit" class="btn btn-primary btn-sm edit  @if(!\Auth::user()->checkAccessById(28, "E")) disabled @endif">' +
                                '<i class="glyphicon glyphicon-arrow-up"></i><span style="display: none;">1</span></a>' +
                                '<a href="satellite-branch/1/edit" name="edit" class="btn btn-primary btn-sm edit  @if(!\Auth::user()->checkAccessById(28, "E")) disabled @endif">' +
                                '<i class="glyphicon glyphicon-arrow-down"></i><span style="display: none;">1</span></a>'

                        },
                        "targets": 3
                    },
                    { "orderable": false, "width": "5%", "targets": 0},
                    { "orderable": false, "width": "10%", "targets": 3 },
                    {"className": "dt-center", "targets": 3},
                    {"className": "dt-center", "targets": 0}
                ],
                "columns": [
                    { "data": "used" },
                    { "data": "chknum_start" },
                    { "data": "chknum_end" },
                ],
            });



            $('.dataTable').wrap('<div class="dataTables_scroll" />');

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

            $('#example_ddl5').on("click", function(e) {
                mainTable.ajax.reload();
            });

            $('#example_ddl2').on('change', function () {
                mainTable.ajax.reload();
            })

            $('#example_ddl3').on('change', function () {
                mainTable.ajax.reload();
            })

            $('#example_ddl4').on('change', function () {
                var id = $('#example_ddl4 option:selected').val();
                var code = $('#example_ddl4 option:selected').text();

                var splitted = code.split('-');
                $('.bankCO').text(splitted[0]);
                $('.accNO').text(splitted[1]);
                $('input[name="accountId"]').val(id);

                mainTable.ajax.reload();
            })

        })(jQuery);
    </script>
@endsection