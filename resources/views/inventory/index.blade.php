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
                                        <tbody>
                                        @foreach($articles as $article)
                                            <tr>
                                                <td>{{ $article->item_id }}</td>
                                                <td>{{ $article->ItemCode }}</td>
                                                <td>{{ $article->Product }}</td>
                                                <td>{{ $article->Brand }}</td>
                                                <td>{{ $article->Description }}</td>
                                                <td>{{ $article->Unit }}</td>
                                                <td>{{ $article->Min_Level }}</td>
                                                <td>{{ $article->Packaging }}</td>
                                                <td>{{ $article->Threshold }}</td>
                                                <td>{{ $article->Multiplier }}</td>
                                                <td>{{ $article->barcode }}</td>
                                                <td>{{ $article->type_desc }}</td>
                                                <td>
                                                    <input type="checkbox" name="trackThis" @if($article->TrackThis == 1) checked @endif disabled>
                                                </td>
                                                <td><input type="checkbox" name="printThis" @if($article->Print_This == 1) checked @endif disabled></td>
                                                <td><input type="checkbox" name="prodActive" @if($article->Active == 1) checked @endif disabled></td>
                                                <td>
                                                    <a href="inventory/{{ $article->item_id }}/edit" name="edit" class="btn btn-primary btn-sm @if(!\Auth::user()->checkAccessById(19, "E")) disabled @endif">
                                                        <i class="glyphicon glyphicon-pencil"></i>
                                                    </a>
                                                    <a href="#" name="delete" class="btn btn-danger btn-sm delete @if(!\Auth::user()->checkAccessById(19, "D")) disabled @endif">
                                                        <i class="glyphicon glyphicon-trash"></i><span style="display: none;">{{ $article->item_id }}</span>
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
    <script>
        (function($){
            var table = $('#myTable').DataTable({
                deferRender:    true,
                stateSave: true,
                dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
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