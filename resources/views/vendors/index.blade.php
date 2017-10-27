@extends('layouts.app')
@section('header-scripts')
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
            color: transparent;
        }
        .modal {
            z-index: 10001 !important;;
        }

        @media screen and (min-width : 300px) and (max-width : 1280px) {

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
                        <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('alert-class') !!}</em></div>
                    @elseif(Session::has('flash_message'))
                        <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
                    @endif
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Vendors</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-md-6 col-xs-6">
                                        </div>
                                        <div class="col-md-6 col-xs-6 text-right">
                                            <a href="{!! url('vendors/create') !!}" class="pull-right  @if(!\Auth::user()->checkAccessById(29, "A")) disabled @endif">Add Vendor</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" id="myTable" width="100%" cellspacing="0">
                                        <thead>
                                        <tr>
                                            <th>Vendor Name</th>
                                            <th>Pay To</th>
                                            <th>Address</th>
                                            <th>Contact Person</th>
                                            <th>Tel. No-Line 1</th>
                                            <th>Tel. No-Line 2</th>
                                            <th>Cellphone No.</th>
                                            <th>x-check</th>
                                            <th>Show on</th>
                                            <th>Tracking</th>
                                            <th>Actions</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($vendors as $vendor)
                                            <tr>
                                                <td>{{ $vendor->VendorName }}</td>
                                                <td>{{ $vendor->PayTo }}</td>
                                                <td>{{ $vendor->Address }}</td>
                                                <td>{{ $vendor->ContactPerson }}</td>
                                                <td>{{ $vendor->TelNo }}</td>
                                                <td>{{ $vendor->OfficeNo }}</td>
                                                <td>{{ $vendor->CelNo }}</td>
                                                <td>
                                                    <input type="checkbox" name="trackThis" @if($vendor->x_check == 1) checked @endif disabled>
                                                </td>
                                                <td>@if($vendor->petty_visible == 1) CDS @elseif($vendor->petty_visible == 2) Petty @else CDS&Petty @endif</td>
                                                <td><input type="checkbox" name="printThis" @if($vendor->withTracking == 1) checked @endif disabled></td>
                                                <td>
                                                    <a href="#" name="view" class="btn btn-success btn-sm viewBtn @if(!\Auth::user()->checkAccessById(29, "E")) disabled @endif ">
                                                        <i class="glyphicon glyphicon-eye-open"></i>
                                                    </a>
                                                    <a href="vendors/{{ $vendor->Supp_ID }}/edit" name="edit" class="btn btn-primary btn-sm @if(!\Auth::user()->checkAccessById(29, "E")) disabled @endif">
                                                        <i class="glyphicon glyphicon-pencil"></i>
                                                    </a>
                                                    <a href="#" name="delete" class="btn btn-danger btn-sm delete @if(!\Auth::user()->checkAccessById(29, "D")) disabled @endif">
                                                        <i class="glyphicon glyphicon-trash"></i><span style="display: none;">{{ $vendor->Supp_ID }}</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>
    <script>
        (function($){
            var table = $('#myTable').DataTable({
                stateSave: true,
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                "columnDefs": [
                    { "width": "25%", "targets": 0},
                    { "width": "25%", "targets": 2},
                    { "width": "9%", "targets": 3},
                    { "orderable": false, "width": "18%", "targets": 10},
                    { "orderable": false, "width": "7%", "targets": [7, 9] },
                    {"className": "dt-center", "targets": [7, 8, 9, 10]}
                ]
            });
            $('#myTable').wrap('<div class="dataTables_scroll" />');


            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var description  = $(this).closest('tr').find('td:nth-child(1)').text();
                $('#confirm-delete').find('.serviceId').val(id);
                $('#confirm-delete .brandToDelete').text(id);
                $('#confirm-delete .descriptionOfBrand').text(description);
                $('#confirm-delete form').attr('action', 'vendors/'+id);
                $('#confirm-delete').modal("show");
            });

            $(document).on('click', '.viewBtn', function (e) {
                e.preventDefault();
                var id  = $(this).closest('td').find('span').text();
                var checked  = $(this).closest('tr').find('td:nth-child(10) input').is(":checked");
                if(checked == false){
                    $.confirm({
                        icon: 'glyphicon glyphicon-exclamation-sign',
                        title: 'Invalid selection!',
                        content: 'Tracking is not enabled for this vendor',
                        type: 'red',
                        typeAnimated: true,
                        buttons: {
                            close: function () {
                            }
                        }
                    });
                }else{
                    window.location.href = 'vendors/'+id
                }
            })

        })(jQuery);
    </script>
@endsection