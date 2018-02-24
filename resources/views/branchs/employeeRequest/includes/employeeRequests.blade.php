<section class="content">
    <div class="row">
        <div class="col-md-12">
            <table id="employeeRequestsDatatable" class="table table-bordered">
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
        let employeeRequestsDatatable = $('#employeeRequestsDatatable').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                        url: "{{ url('getEmployeeRequests') }}",
                        data: function (d) {
                                d.corpId = {{ $corpId }};
                        }
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
