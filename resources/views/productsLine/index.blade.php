@extends('layouts.app')
@section('header-scripts')
    <link href="/css/parsley.css" rel="stylesheet" >
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

        input[type="checkbox"] {
            width: 18px;
            height: 18px;
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
                        <h3 class="text-center">Product Lines</h3>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-6">
                                        </div>
                                        <div class="col-xs-6 text-right">
                                            <a href="#" class="pull-right  @if(!\Auth::user()->checkAccessById(24, "A")) disabled @endif" data-toggle="modal" data-target="#addNewProductLine">Add New Product Line</a>
                                        </div>
                                    </div>

                                </div>
                                <div class="panel-body">
                                    <table class="table table-striped table-bordered" id="myTable" cellspacing="0" width="100%">
                                        <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Product Line</th>
                                            <th>Active</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($products as $product)
                                            <tr>
                                                <td>{{ $product->ProdLine_ID }}</td>
                                                <td>{{ $product->Product }}</td>
                                                <td>
                                                    <input type="checkbox" @if($product->Active) checked @endif disabled>
                                                </td>
                                                <td>
                                                    <a href="#" name="edit" class="btn btn-primary btn-md edit  @if(!\Auth::user()->checkAccessById(24, "E")) disabled @endif">
                                                        <i class="fas fa-pencil-alt"></i><span style="display: none;">{{ $product->ProdLine_ID }}</span>
                                                    </a>
                                                    <a href="#" name="delete" class="btn btn-danger btn-md delete  @if(!\Auth::user()->checkAccessById(24, "D")) disabled @endif">
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
        <!-- Modal add new product line -->
        <div id="addNewProductLine" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h5 class="modal-title">Add New Product Line</h5>
                    </div>
                    <form class="form-horizontal" action="{{ url('/productlines') }}" id="form1" METHOD="POST">
                        <div class="modal-body">
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="prodcutLineName">Product Line</label>
                                <div class="col-md-8">
                                    <input id="prodcutLineName" name="prodcutLineName" type="text" class="form-control input-md"
                                           data-parsley-required-message="Product Line is required"
                                           data-parsley-maxlength-message="The template name may not be greater than 50 characters"
                                           data-parsley-maxlength="50" required="">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label" for="prodcutLineName">Active</label>
                                <div class="col-md-8">
                                    <input id="activeCheck" name="activeCheck" type="checkbox" >
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
                                    <button type="submit" class="btn btn-success pull-right">Save</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal for creating new product line -->

    <!-- Modal for editing service -->
    <div id="editProdcutLine" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Edit Product Line</h5>
                </div>
                <form class="form-horizontal" action="" id="form2" METHOD="post">
                    <div class="modal-body">
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="editProductLineName">Product Line</label>
                            <div class="col-md-8">
                                <input id="editProductLineName" name="editProductLineName" type="text" class="form-control input-md editProductLineName"
                                       data-parsley-required-message="Product Line is required"
                                       data-parsley-maxlength-message="The template name may not be greater than 50 characters"
                                       data-parsley-maxlength="50"  required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-3 control-label" for="prodcutLineName">Active</label>
                            <div class="col-md-8">
                                <input id="editActiveCheck" class="editActiveCheck" name="editActiveCheck" type="checkbox" >
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
                                <input type="hidden" class="productLineId" value="">
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
                        <p class="text-center">Do you want to proceed deleting  <span style="font-weight: bold" class="descriptionOfProduct">
                            </span> -  <span style="font-weight: bold" class="itemToDelete"></span> ?</p>
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
                    { "orderable":true , "targets": 0},
                    { "orderable": true, "width": "60%", "targets": 1 },
                    { "orderable": true, "targets": 2 },
                    { "orderable": false, "targets": 3 },
                    {"className": "dt-center", "targets": 3},
                    {"className": "dt-center", "targets": 2}
                ]
            });
            $('.dataTable').wrap('<div class="dataTables_scroll" />');

            //toggle edit
            $(document).on('click', '.edit', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var productLineName  = $(this).closest('tr').find('td:nth-child(2)').text();
                var activeCheck  = $(this).closest('tr').find('td:nth-child(3) input').is(":checked");
                $('#editProdcutLine').find('.productLineId').val(id);
                $('#editProdcutLine .editProductLineName').val(productLineName);
                if(activeCheck == true) {
                    $('#editProdcutLine .editActiveCheck').attr("checked", true);
                }else{
                    $('#editProdcutLine .editActiveCheck').attr("checked", false);
                }
                $('#editProdcutLine form').attr('action', 'productlines/'+id);
                $('#editProdcutLine').modal("show");

            });

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(2)').text();
               // var description  = $(this).closest('tr').find('td:nth-child(2)').text();
                $('#confirm-delete').find('.serviceId').val(id);
                $('#confirm-delete .itemToDelete').text(itemCode);
                $('#confirm-delete .descriptionOfProduct').text(id);
                $('#confirm-delete form').attr('action', 'productlines/'+id);
                $('#confirm-delete').modal("show");
            });

        })(jQuery);
    </script>
@endsection