<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div id="filters" style="margin-bottom: 7px;">
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
                        <th>No. of Times</th>
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

      <div class="form-group row">
        <label style="margin-top: 7px;" class="col-sm-2 col-form-label">Username</label>
        <div class="col-sm-4">
          <input name="reactivationName" type="text" readonly class="form-control plaintext" value="Juan">
        </div>
        <label style="margin-top: 7px;" for="start_date" class="col-sm-2 col-form-label">Start Date</label>
        <div class="col-sm-4">
          <input type="date" required name="start_date" class="form-control plaintext">
        </div>
      </div>
      <div class="form-group row">
        <label style="margin-top: 7px;" for="password" class="col-sm-2 col-form-label">Password</label>
        <div class="col-sm-4">
          <input type="password" name="password" class="form-control">
        </div>
      </div>
        <input type="hidden" name="requestId">
        <input type="hidden" name="username">
        <hr>
        <label style="margin: 0 0 12px 14px;">Branch Assignment: </label>
            <div class="row branchAssignment">
            @foreach($corporations as $corporation)
                <div style="height: 33px;">
                    <span class="col-md-3">{{ $corporation->corp_name }}</span>
                     <input class="col-md-1" type="checkbox">
                    <select class="col-md-8" style="margin-top:-4px;">
                    <option value="null"></option>
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
                        {data: 'LoginsLeft', name: 'LoginsLeft'},
                        {data: 'Active', name: 'Active'},
                        {data: 'action', name: 'action', sortable: false, searchable: false}
                ]
        });
        $('.branch-filter, .active-filter').on('change', function () {
                reactivateEmployeeDatatable.draw();
        });

        function reactivateEmployee(requestId, username){
            $("input[name='requestId']").val(requestId);
            $("input[name='reactivationName']").val(username);
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
                    $('#reactivateModal').modal('hide');
                    location.reload();
                    // setTimeout(function (){
                    //     showAlertModal("Success", "The employee reactivated successfully");
                    // }, 1000);
                }
            });
        });

        $('#reactivateEmployeeForm input[type="checkbox"]').on('change', function() {
            if(!$(this).is(":checked")) {
                $(this).next("select").prop("disabled", true);
                $(this).next("select").css("background-color", "#ebebe4");
                $(this).next("select").prepend('<option value="null"></option>');
                $(this).next("select").val("null");
             } else {
                $(this).next("select").prop("disabled", false);
                $(this).next("select").css("background-color", "#ffffff");
                $(this).next("select").find("option[value='null']").remove();
             }
             $('#reactivateEmployeeForm input[type="checkbox"]').not(this).each(function (iterator, value){
                $(value).prop('checked', false);  
                $(value).next("select").prop("disabled", "disabled");
                $(value).next("select").css("background-color", "#ebebe4");
                $(value).next("select").prepend('<option value="null"></option>');
                $(value).next("select").val("null");
             });
        });
</script>
