<section class="content">
    <div class="row">
        <div class="col-md-12" style="overflow-x: auto; padding: 0;">
            <div id="reactivate_filters" style="margin-bottom: 7px; clear: both;">
                  Filters 
                  <select style="width: 128px; display: inline;" class="form-control branch-filter">
                           @foreach($branches as $branch)
                                <option @if ($loop->first) selected @endif value="{{ $branch->ShortName }}">{{ $branch->ShortName }}</option>
                           @endforeach
                   </select>
                   <select style="width: 128px; display: inline;" class="form-control active-filter">
                           <option value="any">Any Status</option>
                           <option value="1" selected>Active</option>
                           <option value="0">Inactive</option>
                   </select>
                   <input type="checkbox" name="all-branches"> All Branches
           </div>

            <table id="reactivateEmployeeDatatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>Username</th>
                        {!! ($corpId == 6?"<th>NX</th><th>SQ</th>":"") !!}
                        {!! ($corpId == 7?"<th>OG</th>":"") !!}
                        <th>No. of Times</th>
                        <th>Free Mins</th>
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
          <input type="date" required name="start_date" class="form-control plaintext" value="{{ date("Y-m-d") }}">
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
                    <span class="col-md-3">{{ $corporation["corporation"] }}</span>
                     <input class="col-md-1" type="checkbox">
                    <select disabled class="col-md-8" style="margin-top:-4px; background-color: rgb(235, 235, 228);">
                    <option value="null"></option>
                    @foreach($corporation["branches"] as $branch)
                    <option value="{{ $branch->Branch }}">{{ $branch->ShortName }}</option>
                    @endforeach
                    </select>
                </div>
            @endforeach
            </div>
      </div>
      <div class="modal-footer">
        <!-- <button data-bb-handler="cancel" type="button" class="btn btn-default modal-back"><i class="fa fa-times"></i> Back</button> -->
        <button type="button" class="btn btn-default modal-back" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fa fa-times"></i> Back</span>
        </button>
        <button type="submit" class="btn btn-success">Reactivate</button>
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
                "order": [],
                columns: [
                        {data: 'username', name: 'username'},
                        {!! ($corpId == 6?"{data: 'nx', name: 'nx'},{data: 'sq', name: 'sq'},":"") !!}
                        {!! ($corpId == 7?"{data: 'og', name: 'og'},":"") !!}
                        {data: 'LoginsLeft', name: 'LoginsLeft'},
                        {data: 'AllowedMins', name: 'AllowedMins'},
                        {data: 'LastUnfrmPaid', name: 'LastUnfrmPaid'},
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
            var requestId = $("input[name='requestId']").val();
            branch_id = $("#reactivateModal :checkbox:checked").next("select").val();
            branch_name = $("#reactivateModal :checkbox:checked").next("select").children("option").filter(":selected").text();
            password = $("input[name='password']").val();
            start_date = $("input[name='start_date']").val();
            $.ajax({
                method: "POST", 
                url : "{{ url('reactivateEmployeeRequest') }}", 
                data : { "_token" : '{{ csrf_token() }}', branch_id : branch_id, password : password, start_date : start_date, "employeeRequestId" : requestId,  corpId : {{ $corpId }} }
            }).done(function (response){
                if(response == "true") {
                    $('#reactivateModal').modal('hide');
                      // $("[date_start_id='"+requestId+"']").html(start_date);
                      // $("[to_branch_id='"+requestId+"']").html(branch_name);
                      showSuccessAlert(" The employee reactivated successfully");
                      // showAlertModal("Success", "The employee reactivated successfully");
                } else {
                  showDangerAlert("Something Went Wrong, Please Contact Administration");
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
$(document).ready(function (){
    $("#reactivate_filters").insertAfter("#reactivateEmployeeDatatable_wrapper .dataTables_length");
});

$('[name="all-branches"]').change(function (){
  if($(this).is(":checked")){
    $(".branch-filter").prepend("<option value='any'></option>");
    $(".branch-filter").val("any");
    $(".branch-filter").attr("disabled", "disabled");
    $(".branch-filter").trigger("change");
  } else {
    $(".branch-filter option[value='any']").remove();
    $(".branch-filter").removeAttr("disabled");
    $(".branch-filter").trigger("change");
  }
});
</script>
