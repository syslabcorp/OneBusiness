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

@endsection

@section('footer-scripts')
    <script>
        (function($){
            $('#myTable').DataTable({
                initComplete: function () {
                    this.api().columns(3).every( function () {
                        var column = this;
                        var select = $('<select><option value="">All</option></select>')
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
                            if(d == 1) { activeName = 'Active'; } else { activeName = 'Inactive'; };
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