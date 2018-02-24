@extends('layouts.custom')

@section('content')
<head>
<link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
</head>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <table id="messages-datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>From Branch</th>
                        <th>Last Duty</th>
                        <th>To Branch</th>
                        <th>Start Duty</th>
                        <th>Type</th>
                        <th>Approved</th>
                        <th>Uploaded</th>
                        <th>Sex</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tfoot>
                </tfoot>
            </table>

        </div>
      </div>
</section>
<script>
        let table = $('#messages-datatable').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                        url: "{{ url('getEmployeeRequests') }}",
                        data: {}
                },
                columns: [
                        {data: 'username', name: 'username'},
                        {data: 'from_branch', name: 'from_branch'},
                        {data: 'date_end', name: 'date_end'},
                        {data: 'to_branch', name: 'to_branch'},
                        {data: 'date_start', name: 'date_start'},
                        {data: 'type', name: 'type'},
                        {data: 'approved', name: 'approved'},
                        {data: 'executed', name: 'executed'},
                        {data: 'sex', name: 'sex'},
                        {data: 'action', name: 'action', sortable: false, searchable: false}
                ]
        });
    </script>

@endsection
