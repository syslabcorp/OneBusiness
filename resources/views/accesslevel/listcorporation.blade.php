@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        @if(Session::has('alert-class'))
            <div class="alert alert-success"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
        @elseif(Session::has('flash_message'))
            <div class="alert alert-danger"><span class="fa fa-close"></span><em> {!! session('flash_message') !!}</em></div>
        @endif
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">List Corporations<a href="{{ URL('add_corporation') }}" class="pull-right">Add Corporation</a></div>
                <div class="panel-body">
                   <table id="list_corp" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>SNo.</th>
                                <th>Title</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($detail as $key=>$det)
                                <tr>
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $det->corp_name }}</td>
                                    <td><a class="btn btn-primary btn-md" data-title="Edit" href="{{ URL::to('add_corporation/' . $det->corp_id) }}"><span class="glyphicon glyphicon-pencil"></span></a>
                                    <a class="btn btn-danger btn-md sweet-4" data-title="Delete" href="#" rel="{{ URL::to('delete_corporation/' . $det->corp_id) }}"><span class="glyphicon glyphicon-trash"></span></a></td>
                                </tr>  
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#list_corp').DataTable();
    $('.sweet-4').click(function(){
        var delete_url = $(this).attr("rel");
        swal({
            title: "Are you sure?",
            text:  "You will not be able to recover this Corporation Data!",
            type:  "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm){
                window.location.replace(delete_url);
            } else {
                return false;
            }
        });
    });
});
</script>
@endsection


