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


        #example_ddl label {
            position: relative;
            top: 8px;
        }

        .edit {
            margin-right: 2px;
        }

        #example_ddl3{
            margin-left: 5px;
        }

        #example_ddl2 label{
            margin-right: 5px;
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
                    @if(Session::has('success'))
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('success') !!}</em></div>
                    @elseif(Session::has('error'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('error') !!}</em></div>
                    @endif
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Satellite Branches</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <a href="{{ route('satellite-branch.create') }}" class="pull-right @if(!\Auth::user()->checkAccessById(26, "A")) disabled @endif" >Add Satellite Branch</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Satellite Branch</th>
                                            <th>Description</th>
                                            <th>Notes</th>
                                            <th>Action</th>
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

    <!-- Modal delete satellite branch -->
    <div class="modal fade" id="confirm-delete" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="myModalLabel">Confirm Delete</h4>
                </div>
                <form action="" method="POST" >
                    <div class="modal-body">
                        <p class="text-center">You are about to delete one track, this procedure is irreversible.</p>
                        <p class="text-center">Do you want to proceed deleting <span style="font-weight: bold" class="brandToDelete"></span> -
                            <span style="font-weight:bold" class="descriptionOfBrand"></span> ?</p>
                        <p class="debug-url"></p>
                    </div>

                    <div class="modal-footer">
                        <input style="display: none" class="serviceId" >
                        {!! csrf_field() !!}
                        {{ method_field('Delete') }}
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
    <script>
        (function($){

            var table = $('#myTable').DataTable({
                initComplete: function () {
                    $('<label for="">Filters:</label>').appendTo("#example_ddl2");
                    var corporationID = $('<select class="form-control"></select>')
                        .appendTo('#example_ddl2');

                    @foreach($corporations as $key => $val)
                      corporationID.append('<option value="{{ $val->corp_id }}" >{{ $val->corp_name }}</option>');
                    @endforeach
                    var branchStatus = $('<select class="form-control"><option value="1">Active</option></select>')
                        .appendTo('#example_ddl3');
                    branchStatus.append('<option value="0">Inactive</option>');

                    let initStatus = localStorage.getItem('satellite.active') || 1;
                    let initCorp = localStorage.getItem('satellite.corpID') || '{{ $corporations[0]->corp_id }}';

                    corporationID.val(initCorp);
                    branchStatus.val(initStatus);
                },
                "processing": true,
                "serverSide": true,
                "order": [[ 0, "asc" ]],
                "ajax" : {
                    type: "POST",
                    url: "satellite-branch/get-branch-list",
                    data: function ( d ) {
                      let initStatus = localStorage.getItem('satellite.active') || 1;
                      let initCorp = localStorage.getItem('satellite.corpID') || '{{ $corporations[0]->corp_id }}';
                
                      d.statusData = $('#example_ddl3 select option:selected').val() == undefined ? initStatus : $('#example_ddl3 select option:selected').val(),
                      @if(isset($corporations[0]->corp_id))
                      d.corpId = $('#example_ddl2 select option:selected').val() == undefined ? initCorp : $('#example_ddl2 select option:selected').val();
                      @endif
                    }
                },
                stateSave: true,
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                "<'row'<'col-sm-12'<'.form-group.pull-left#example_ddl'<'#example_ddl2'>><'.form-group'<'#example_ddl3'>>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            return row.sat_branch;
                        },
                        "targets": 0
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.short_name;
                        },
                        "targets": 1
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.description;
                        },
                        "targets": 2
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.notes;
                        },
                        "targets": 3
                    },
                    {
                        "render": function ( data, type, row ) {
                            var checkAccess = '<?php  if(\Auth::user()->checkAccessById(26, "E")) {  echo 1; }else{ echo 0; } ?>';
                            var checkAccessDel = '<?php  if(\Auth::user()->checkAccessById(26, "D")) {  echo 1; }else{ echo 0; } ?>';
                            var optionClass = "";
                            var optionClassDel = "";
                            if(checkAccess == 0) { optionClass = 'disabled' };
                            if(checkAccessDel == 0) { optionClassDel = 'disabled' };
                            return '<a href="satellite-branch/'+row.sat_branch+'/edit" name="edit" class="btn btn-primary btn-sm edit '+optionClass+'">' +
                                '<i class="glyphicon glyphicon-pencil"></i><span style="display: none;">'+row.sat_branch+'</span></a>' +
                                '<a href="#" name="delete" class="btn btn-danger btn-sm delete '+optionClassDel+'">'+
                                '<i class="glyphicon glyphicon-trash"></i></a>';

                        },
                        "targets": 4
                    },
                    { "width": "5%", "targets": 0},
                    { "orderable": false, "width": "9%", "targets": 4 },
                    {"className": "dt-center", "targets": 4},
                    {"className": "dt-center", "targets": 0}
                ],
                "columns": [
                    { "data": "sat_branch" },
                    { "data": "short_name" },
                    { "data": "description" },
                    { "data": "notes" }
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

            $('#example_ddl3').on('change', function () {
                table.ajax.reload();
            });

            $('#example_ddl2').on('change', function () {
                table.ajax.reload();
            });

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var description  = $(this).closest('tr').find('td:nth-child(2)').text();
                $('#confirm-delete').find('.serviceId').val(id);
                $('#confirm-delete .brandToDelete').text(id);
                $('#confirm-delete .descriptionOfBrand').text(description);
                $('#confirm-delete form').attr('action', 'satellite-branch/'+id);
                $('#confirm-delete').modal("show");
            });

            $(document).on('click', '.edit', function(e) {
              localStorage.setItem('satellite.corpID', $('#example_ddl2 select').val());
              localStorage.setItem('satellite.active', $('#example_ddl3 select').val());
            });


        })(jQuery);
    </script>
@endsection