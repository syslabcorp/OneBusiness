@extends('layouts.app')
@section('header-scripts')
    <link href="css/parsley.css" rel="stylesheet" >
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
                    <div class="col-md-12 col-xs-12">
                        <h3 class="text-center">Services</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <a href="#" class="pull-right @if(!\Auth::user()->checkAccessById(20, "A")) disabled @endif" data-toggle="modal" data-target="#addNewService">Add New Service</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>Service Code</th>
                                            <th>Description</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($services as $service)
                                            <tr>
                                                <td>{{ $service->Serv_Code }}</td>
                                                <td>{{ $service->Description }}</td>
                                                <td>
                                                        <a href="#" name="edit" class="btn btn-primary btn-md edit @if(!\Auth::user()->checkAccessById(20, "E")) disabled @endif">
                                                            <i class="fas fa-pencil-alt"></i><span style="display: none;">{{ $service->Serv_ID }}</span>
                                                        </a>

                                                        <a href="#" name="delete" class="btn btn-danger btn-md delete @if(!\Auth::user()->checkAccessById(20, "D")) disabled @endif">
                                                            <i class="glyphicon glyphicon-trash"></i>
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
    <!-- Modal create item and service -->
    <div id="addNewService" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Add New Service</h5>
                </div>
                <form class="form-horizontal" action="{{ url('/services') }}" id="form1" METHOD="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="serviceCode">Item Code</label>
                            <div class="col-md-8">
                                <input id="serviceCode" name="serviceCode" type="text" class="form-control input-md"
                                       data-parsley-required-message="Item Code person is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 30 characters"
                                       data-parsley-maxlength="30" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="serviceDescription">Description</label>
                            <div class="col-md-8">
                                <input id="serviceDescription" name="serviceDescription" type="text" class="form-control input-md"
                                       data-parsley-required-message="Description person is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 40 characters"
                                       data-parsley-maxlength="40" required="">
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
    <div id="editService" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Edit Service: <span style="font-weight: bold" class="serviceToEdit"></span></h5>
                </div>
                <form class="form-horizontal" action="" id="form2" METHOD="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="serviceCode">Item Code</label>
                            <div class="col-md-8">
                                <input id="serviceCode" name="serviceCode" type="text" class="form-control input-md serviceCode"
                                       data-parsley-required-message="Item Code person is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 30 characters"
                                       data-parsley-maxlength="30" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="serviceDescription">Description</label>
                            <div class="col-md-8">
                                <input id="serviceDescription" name="serviceDescription" type="text" class="form-control input-md serviceDescription"
                                       data-parsley-required-message="Description is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 40 characters"
                                       data-parsley-maxlength="40" required="">
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
                                {{ method_field('PUT') }}
                                <input type="hidden" class="serviceId" value="">
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
                        <p class="text-center">You are about to delete one track, this procedure is irreversible.</p>
                        <p class="text-center">Do you want to proceed deleting <span style="font-weight: bold" class="itemToDelete"></span> -
                            <span style="font-weight: bold" class="descriptionOfService"></span> ?</p>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/parsley.js/2.7.2/parsley.min.js"></script>
    <script>
        (function($){
            $('#form1, #form2').parsley();
            $('#myTable').DataTable({
                stateSave: true,
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                "columnDefs": [
                    { "width": "18%", "targets": 0},
                    { "orderable": false, "width": "9%", "targets": 2 },
                    {"className": "dt-center", "targets": 2}
                ]
            });
            $('.dataTable').wrap('<div class="dataTables_scroll" />');

            //toggle edit
            $(document).on('click', '.edit', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:first').text();
                var description  = $(this).closest('tr').find('td:nth-child(2)').text();
                console.log(id);

                $('#editService').find('.serviceId').val(id);
                $('#editService .serviceToEdit').text(itemCode);
                $('#editService .serviceCode').val(itemCode);
                $('#editService .serviceDescription').val(description);
                $('#editService form').attr('action', 'services/'+id);
                $('#editService').modal("show");
                //   alert($(this).closest('td').find('span').text());
            });

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:first').text();
                var description  = $(this).closest('tr').find('td:nth-child(2)').text();
                $('#confirm-delete').find('.serviceId').val(id);
                $('#confirm-delete .itemToDelete').text(itemCode);
                $('#confirm-delete .descriptionOfService').text(description);
                $('#confirm-delete form').attr('action', 'services/'+id);
                $('#confirm-delete').modal("show");
            });

        })(jQuery);
    </script>
@endsection