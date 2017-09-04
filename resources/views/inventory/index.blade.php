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
        div.dataTables_wrapper {
            margin: 0 auto;
        }

        tr > td:last-child{
            width: 90px !important;
        }

    </style>
    @endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-6">
                            </div>
                            <div class="col-md-6 text-right">
                                @if(\Auth::user()->checkAccess("Retail Items", "A"))
                                <a href="{!! url('inventory/create') !!}" class="pull-right">Add Item</a>
                                    @endif
                            </div>
                        </div>

                    </div>
                    <div class="panel-body">
                        <table class="table table-striped table-bordered" id="myTable">
                            <thead>
                            <tr>
                                <th>ItemID</th>
                                <th>Item Code</th>
                                <th>Product Line</th>
                                <th>Brand</th>
                                <th>Description</th>
                                <th>Unit</th>
                                <th>Min. Level</th>
                                <th>Packaging</th>
                                <th>Threshold</th>
                                <th>Multiplier</th>
                                <th>Barcode #</th>
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
                                    @if(\Auth::user()->checkAccess("Retail Items", "E"))
                                    <a href="/inventory/{{ $article->item_id }}/edit" name="edit" class="btn btn-primary btn-sm">
                                        <i class="fa fa-pencil"></i>
                                    </a>
                                    @endif
                                    @if(\Auth::user()->checkAccess("Retail Items", "D"))
                                    <a href="#" name="delete" class="btn btn-danger btn-sm delete">
                                        <i class="fa fa-trash"></i><span style="display: none;">{{ $article->item_id }}</span>
                                    </a>
                                    @endif
                                </td>
                            </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal delete item from inventory -->
        <div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
    </div>
    @endsection
@section('footer-scripts')
    <script>
        (function($){
                $('#myTable').DataTable({
                    stateSave: true,
                    dom: "<'row'<'col-sm-6'l><'col-sm-6'<'pull-right'f>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-5'i><'col-sm-7'<'pull-right'p>>>",
                    columnDefs: [
                        { orderable: false, targets: 15},
                    ]
                });
                jQuery('.dataTable').wrap('<div class="dataTables_scroll" />');

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();

                var id  = $(this).closest('td').find('span').text();
                var itemCode  = $(this).closest('tr').find('td:nth-child(4)').text();
                console.log(id +' '+itemCode);
                $('#confirm-delete').find('.serviceId').val(id);
                $('#confirm-delete .itemToDelete').text(itemCode);
                $('#confirm-delete form').attr('action', '/inventory/'+id);
                $('#confirm-delete').modal("show");
            });

        })(jQuery);
    </script>
@endsection