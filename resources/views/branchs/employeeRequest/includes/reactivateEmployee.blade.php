<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div id="filters" style="margin-bottom: 14px;">
                  Filters <select style="width: 128px; display: inline;" class="form-control branch-filter">
                           <option value="any">All Branches</option>
                           <option value="NX New test">NX New test</option>
                   </select>
           </div>

            <table id="reactivateEmployeeDatatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Branch</th>
                        <th>Last Unfrm Paid</th>
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
        let reactivateEmployeeDatatable = $('#reactivateEmployeeDatatable').DataTable({
                processing: true,
                serverSide: true,
                "ajax": {
                        url: "{{ url('getEmployeeRequests2') }}",
                        data: function (d) {
                                d.branch_name = $('.branch-filter').val();
                        }
                },
                columns: [
                        {data: 'username', name: 'username'},
                        {data: 'from_branch', name: 'from_branch'},
                        {data: 'LastUnfrmPaid', name: 'LastUnfrmPaid'},
                        {data: 'action', name: 'action', sortable: false, searchable: false}
                ]
        });
        $('.branch-filter').on('change', function () {
                reactivateEmployeeDatatable.draw();
        });
</script>
