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
                        <th>Free Mins</th>
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

<form id="reactivateEmployeeForm">
<div class="modal fade" id="reactivateModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 style="display: inline;" class="modal-title" id="exampleModalLongTitle"><b>Reactivate Employee Account</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div class="row">
            <span class="col-md-6">
                <label>Username: </label>
                <input type="text" value="test" disabled="">
            </span>
            <span class="col-md-6">
                <label>Start Date: </label>
                <input style="line-height: initial;" type="date" name="start_date">
            </span>
            <span class="col-md-12" style="margin-top: 8px;">
                    <label>Password: </label>
                    <input style="margin-left: 2px;" type="password" name="password">
            </span>
            <input type="hidden" name="requestId">
        </div>
        <hr>
        <label>Branch Assignment: </label>
            <div class="row branchAssignment">
            @foreach($corporations as $corporation)
                <div>
                    <span class="col-md-3">{{ $corporation->corp_name }}</span>
                     <input class="col-md-1" type="checkbox">
                    <select class="col-md-8">
                    @foreach($corporation->branches as $branch)
                    <option value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
                    @endforeach
                    </select>
                </div>
            @endforeach
            </div>
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
</form>
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
                        {data: 'AllowedMins', name: 'AllowedMins'},
                        {data: 'Active', name: 'Active'},
                        {data: 'action', name: 'action', sortable: false, searchable: false}
                ]
        });
        $('.branch-filter, .active-filter').on('change', function () {
                reactivateEmployeeDatatable.draw();
        });

        function reactivateEmployee(requestId){
            $("input[name='requestId']").val(requestId);
            $('#reactivateModal').modal('show');
        }

        $("#reactivateEmployeeForm").submit(function (event){
            event.preventDefault();
            branch_id = $(":checkbox:checked").next("select").val();
            password = $("input[name='password']").val();
            start_date = $("input[name='start_date']").val();
            $.ajax({
                method: "POST", 
                url : "{{ url('reactivateEmployeeRequest') }}", 
                data : { "_token" : '{{ csrf_token() }}', branch_id : branch_id, password : password, start_date : start_date, "employeeRequestId" : $("input[name='requestId']").val(),  corpId : {{ $corpId }} }
            }).done(function (response){
                if(response == "true") {
                    console.log("Success");
                }
            });
        });
</script>
