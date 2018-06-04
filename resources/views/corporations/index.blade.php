@extends('layouts.custom')

@section('content')
<div class="panel panel-default">
    <div class="panel-heading">List of Corporations
        @if(\Auth::user()->checkAccessById(30, "A"))
        <a href="{{ route('corporations.create') }}" class="pull-right">Add Corporation</a>
        @endif
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table id="list_corp" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
              <thead>
                  <tr>
                      <th>Corp. ID</th>
                      <th>Title</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                @foreach($corporations as $key=>$det)
                  <tr>
                    <td><span class="dispnone">{{ $det->corp_name }}</span>{{ $det->corp_id }}</td>
                    <td>{{ $det->corp_name }}</td>
                    <td>
                      <a class="btn btn-primary btn-md blue-tooltip {{ \Auth::user()->checkAccessById(30, 'E') ? '' : 'disabled' }}" data-title="Edit"
                        href="{{ route('corporations.edit', $det) }}" data-toggle="tooltip" data-placement="top" title="Edit Corporation">
                        <i class="fas fa-pencil-alt"></i>
                      </a>
                      <a class="btn btn-danger btn-md sweet-4 {{ \Auth::user()->checkAccessById(30, 'D') ? '' : 'disabled' }}"
                        rel="{{ route('corporations.destroy', $det->corp_id) }}" id="{{ $det->corp_id }}" corp-name="{{ $det->corp_name }}" title="Delete Corporation">
                        <i class="fas fa-trash-alt"></i>
                      </a>
                    </td>
                  </tr>  
                @endforeach
              </tbody>
            </table>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('#list_corp').DataTable();
    $(document).on("click", ".sweet-4", function(){
        var delete_url = $(this).attr("rel");
        var corp_name = $(this).attr("corp-name");
        var id = $(this).attr("id");
        swal({
            title: "<div class='delete-title'>Confirm Delete</div>",
            text:  "<div class='delete-text'>You are about to delete Corporation <strong>"+id+" - "+corp_name +"</strong><br/> Are you sure?</div>",
            html:  true,
            customClass: 'swal-wide',
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Delete',
            cancelButtonText: "Cancel",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
            if (isConfirm) {
                $.ajax({
                    type: 'DELETE',
                    url: delete_url,
                    success: (res) => {
                        location.reload()
                    }
                })
            } else {
                return false;
            }
        });
    });
    $('[data-toggle="tooltip"]').tooltip();
});
</script>
@endsection


