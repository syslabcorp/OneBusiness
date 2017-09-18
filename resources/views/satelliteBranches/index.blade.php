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

        #example_ddl > select {
            margin: 2px 0 2px 0;
            width: 176px;
            height: 30px;
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
                        <h3 class="text-center">Satellite Branches</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <a href="{{ route('satellite-branch.create') }}" class="pull-right @if(!\Auth::user()->checkAccessById(23, "A")) disabled @endif" >Add Satellite Branch</a>
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
                                            <th>Active</th>
                                            <th>Notes</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($satelliteBranches as $branch)
                                            <tr>
                                                <td>{{ $branch->sat_branch }}</td>
                                                <td>{{ $branch->short_name }}</td>
                                                <td>{{ $branch->description }}</td>
                                                <td>{{ $branch->active }}</td>
                                                <td>{{ $branch->notes }}</td>
                                                <td>
                                                    <a href="satellite-branch/{{ $branch->sat_branch }}/edit" name="edit" class="btn btn-primary btn-sm edit  {{--@if(!\Auth::user()->checkAccessById(23, "E")) disabled @endif--}}">
                                                        <i class="glyphicon glyphicon-pencil"></i><span style="display: none;">{{ $branch->sat_branch }}</span>
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
    <!-- Modal add new brand -->
    <div id="addNewBrand" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Add New Brand</h5>
                </div>
                <form class="form-horizontal" action="{{ url('/brands') }}" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="brandName">Brand Name</label>
                            <div class="col-md-8">
                                <input id="brandName" name="brandName" type="text" class="form-control input-md" required="">
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
                                <button type="submit" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for creting new service -->

    <!-- Modal for editing service -->
    <div id="editBrand" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Edit Brand: <span style="font-weight: bold" class="brandToEdit"></span></h5>
                </div>
                <form class="form-horizontal" action="" METHOD="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="editBrandName">Brand Name</label>
                            <div class="col-md-8">
                                <input id="editBrandName" name="editBrandName" type="text" class="form-control input-md editBrandName" required="">
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
                                {{ method_field('PUT') }}
                                <input type="hidden" class="brandId" value="">
                                <button type="submit" class="btn btn-primary pull-right">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal for editing service -->
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
                        <p>You are about to delete one track, this procedure is irreversible.</p>
                        <p>Do you want to proceed deleting <span style="font-weight: bold" class="itemToDelete"></span> ?</p>
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
            $('#myTable').DataTable({
                initComplete: function () {
                    this.api().columns(3).every( function () {
                        var column = this;
                        var select = $('<select><option value="">Select Active</option></select>')
                            .appendTo( '#example_ddl' )
                            .on( 'change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                    .search( val ? '^'+val+'$' : '', true, false )
                                    .draw();
                            } );

                        column.data().unique().sort().each( function ( d, j ) {
                            var activeName = "";
                            if(d == 1) { activeName = 'Yes'; } else { activeName = 'No'; };
                            select.append( '<option value="'+d+'">'+activeName+'</option>' )
                        } );
                    } );
                },
                stateSave: true,
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                    "<'row'<'col-sm-12'<'#example_ddl.pull-right'>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                "columnDefs": [
                    { "width": "5%", "targets": 0},
                    { "visible": false, "searchable": true, "targets": 3 },
                    { "orderable": false, "width": "9%", "targets": 5 },
                    {"className": "dt-center", "targets": 5}
                ]
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

        })(jQuery);
    </script>
@endsection