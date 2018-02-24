<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div id="filters" style="margin-bottom: 14px;">
                  Filters 
                  <select style="width: 128px; display: inline;" class="form-control approved-filter">
                           <option value="any">All Requests</option>
                           <option value="0">For Approval</option>
                           <option value="1">Approved</option>
                   </select>
           </div>
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
                        <th>SSS</th>
                        <th>PHIC</th>
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
                                d.approved = $(".approved-filter").val();
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
                        {data: 'SSS', name: 'SSS'},
                        {data: 'PHIC', name: 'PHIC'},
                        {data: 'action', name: 'action', sortable: false, searchable: false}
                ]
        });
        $('.approved-filter').on('change', function () {
                employeeRequestsDatatable.draw();
        });
</script>
