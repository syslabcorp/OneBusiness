<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div id="filters" style="margin-bottom: 14px;">
                  Filters 
                  <select style="width: 128px; display: inline;" class="form-control branch-filter">
                           <option value="any">All Branches</option>
                           @foreach($branches as $branch)
                                <option value="{{ $branch->branch }}">{{ $branch->branch }}</option>
                           @endforeach
                   </select>
                   <select style="width: 128px; display: inline;" class="form-control active-filter">
                           <option value="any">Any Status</option>
                           <option value="1">Active</option>
                           <option value="0">Inactive</option>
                   </select>
           </div>

            <table id="reactivateEmployeeDatatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Branch</th>
                        <th>Last Unfrm Paid</th>
                        <th>Active</th>
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
                                d.isActive = $('.active-filter').val();
                                d.corpId = {{ $corpId }};
                        }
                },
                columns: [
                        {data: 'username', name: 'username'},
                        {data: 'from_branch', name: 'from_branch'},
                        {data: 'LastUnfrmPaid', name: 'LastUnfrmPaid'},
                        {data: 'Active', name: 'Active'},
                        {data: 'action', name: 'action', sortable: false, searchable: false}
                ]
        });
        $('.branch-filter, .active-filter').on('change', function () {
                reactivateEmployeeDatatable.draw();
        });
</script>
