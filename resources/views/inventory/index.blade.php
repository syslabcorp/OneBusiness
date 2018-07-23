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

        .panel-body {
            padding: 15px !important;
        }

        tr > td:last-child{
            width: 80px !important;
        }

        a.disabled {
            pointer-events: none;
            cursor: default;
        }

        .modal {
            z-index: 10001 !important;;
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
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('success') !!}</em></div>
                    @elseif(Session::has('error'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('error') !!}</em></div>
                    @endif
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Inventory</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                        </div>
                                        <div class="col-md-6 col-xs-6 text-right">
                                            <a href="{!! url('inventory/create') !!}" class="pull-right  @if(!\Auth::user()->checkAccessById(19, "A")) disabled @endif">Add Item</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" id="myTable" width="100%" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>Item ID</th>
                                            <th>Item Code</th>
                                            <th>Product Line</th>
                                            <th>Brand</th>
                                            <th>Description</th>
                                            <th>Unit</th>
                                            <th>Min. Level</th>
                                            <th>Packaging</th>
                                            <th>Threshold</th>
                                            <th>Multiplier</th>
                                            <th>Barcode</th>
                                            <th>Type</th>
                                            <th>Track</th>
                                            <th>Print</th>
                                            <th>Active</th>
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
    <!-- Modal delete item from inventory -->
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
                "processing": true,
                "serverSide": true,
                "ajax" : {
                    type: "POST",
                    url: "inventory/get-inventory-list",
                },
                stateSave: true,
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                "columnDefs": [
                    {
                        "render": function ( data, type, row ) {
                            return row.item_id;
                        },
                        "targets": 0
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.ItemCode;
                        },
                        "targets": 1
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.Product;
                        },
                        "targets": 2
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.Brand;
                        },
                        "targets": 3
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.Description;
                        },
                        "targets": 4
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.Unit;
                        },
                        "targets": 5
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.Min_Level;
                        },
                        "targets": 6
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.Packaging;
                        },
                        "targets": 7
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.Threshold;
                        },
                        "targets": 8
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.Multiplier;
                        },
                        "targets": 9
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.barcode;
                        },
                        "targets": 10
                    },
                    {
                        "render": function ( data, type, row ) {
                            return row.type_desc;
                        },
                        "targets": 11
                    },
                    {
                        "render": function ( data, type, row ) {
                            var checked = "";
                            if(row.TrackThis == 1) checked = "checked";
                            return '<input type="checkbox" '+ checked +' disabled >';
                        },
                        "targets": 12
                    },
                    {
                        "render": function ( data, type, row ) {
                            var checked = "";
                            if(row.Print_This == 1) checked = "checked";
                            return '<input type="checkbox" '+ checked +' disabled >';
                        },
                        "targets": 13
                    },
                    {
                        "render": function ( data, type, row ) {
                            var checked = "";
                            if(row.Active == 1) checked = "checked";
                            return '<input type="checkbox" '+ checked +' disabled >';
                        },
                        "targets": 14
                    },
                    {
                        "render": function ( data, type, row ) {
                            var checkAccess = '<?php  if(\Auth::user()->checkAccessById(19, "E")) {  echo 1; }else{ echo 0; } ?>';
                            var optionClass = "";
                            if(checkAccess == 0) { optionClass = 'disabled' };

                            var checkDelete = '<?php  if(\Auth::user()->checkAccessById(19, "D")) {  echo 1; }else{ echo 0; } ?>';
                            var optionClassDel = "";
                            if(checkDelete == 0) { optionClassDel = 'disabled' };

                            return '<a href="inventory/'+row.item_id+'/edit" name="edit" class="btn btn-primary btn-md" '+optionClass+'>' +
                                    '<i class="fas fa-pencil-alt"></i></a>' +
                                    '<a href="#" name="delete" style="margin-left: 2px" class="btn btn-danger btn-md delete" '+optionClassDel+'>' +
                                    '<i class="far fa-trash-alt"></i><span style="display: none;">'+row.item_id+'</span></a>';


                        },
                        "targets": 15
                    },
                    { "orderable": true, "width": "5%", "targets": 0},
                    { "orderable": true, "width": "2%", "targets": 1 },
                    { "orderable": true, "width": "3%", "targets": 2 },
                    { "orderable": true, "width" : "4%", "targets": 3 },
                    { "orderable": true, "width" : "4%", "targets": 4 },
                    { "orderable": false, "targets": 5 },
                    { "orderable": false, "targets": 6 },
                    { "orderable": false, "targets": 7 },
                    { "orderable": false, "targets": 8 },
                    { "orderable": false, "targets": 9 },
                    { "orderable": false, "targets": 10},
                    { "orderable": false, "targets": 11 },
                ],
                "columns": [
                    { "data": "item_id" },
                    { "data": "ItemCode" },
                    { "data": "Product" },
                    { "data": "Brand" },
                    { "data": "Description" },
                    { "data": "Unit" },
                    { "data": "Min_Level" },
                    { "data": "Packaging" },
                    { "data": "Threshold" },
                    { "data": "Multiplier" },
                    { "data": "barcode" },
                    { "data": "type_desc" },
                ],
            });
            $('#myTable').wrap('<div class="dataTables_scroll" />');


            $(document).on('click', '.delete', function (e) {
                e.preventDefault();
                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(2)').text();
                $('#confirm-delete').find('.serviceId').val(id);
                $('#confirm-delete .brandToDelete').text(id);
                $('#confirm-delete .descriptionOfBrand').text(itemCode);
                $('#confirm-delete form').attr('action', 'inventory/'+id);
                $('#confirm-delete').modal("show");
            });

        })(jQuery);
    </script>
@endsection