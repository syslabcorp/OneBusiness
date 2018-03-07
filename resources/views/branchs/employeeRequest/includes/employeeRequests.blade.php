<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div id="employee_filters" style="margin-bottom: 7px; clear: both;">
                  Filters 
                  <select style="width: 128px; display: inline;" class="form-control approved-filter">
                           <option value="any">All Requests</option>
                           <option value="uploaded">Uploaded</option>
                           <option value="approved">Approved</option>
                           <option value="for_approval">For Approval</option>
                   </select>
           </div>
            <table id="employeeRequestsDatatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>From Branch</th>
                        <th>Last Duty</th>
                        <th>To Branch</th>
                        <th>Start Duty</th>
                        <th>Approved</th>
                        <th>Uploaded</th>
                        <th>Sex</th>
                        <th>Birthdate</th>
                        <th>SSS</th>
                        <th>PHIC</th>
                        <th>HDMF</th>
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
                {data: 'type', name: 'type'},
                {data: 'from_branch', name: 'from_branch'},
                {data: 'date_end', name: 'date_end'},
                {data: 'to_branch', name: 'to_branch'},
                {data: 'date_start', name: 'date_start'},
                {data: 'approved', name: 'approved'},
                {data: 'executed', name: 'executed'},
                {data: 'sex', name: 'sex'},
                {data: 'bday', name: 'bday'},
                {data: 'SSS', name: 'SSS'},
                {data: 'PHIC', name: 'PHIC'},
                {data: 'pagibig', name: 'pagibig'},
                {data: 'action', name: 'action', sortable: false, searchable: false}
        ],
        initComplete: function () {
            this.api().columns().every(function () {
                var column = this;
                var input = document.createElement("input");
                $(input).appendTo($(column.footer()).empty())
                .on('change', function () {
                    column.search($(this).val(), false, false, true).draw();
                });
            });
        }
});
$('.approved-filter').on('change', function () {
        employeeRequestsDatatable.draw();
});

function sendApproveRequest(requestId){
    $.ajax({
        method: "POST", 
        url : "{{ url('approveEmployeeRequest') }}",
        data : {"_token" : "{{ csrf_token() }}", "employeeRequestId" : requestId, corpId :  {{ $corpId }}}
    }).done(function (response){
        if(response == "true") { 
                $(".approved_td[name='"+requestId+"']").prop('checked', true);
                $("[data-approve-id='"+requestId+"']").attr("disabled", "disabled");
                $("[data-delete-id='"+requestId+"']").attr("disabled", "disabled");
                showSuccessAlert(" The employee request successfully approved!");
                // showAlertModal("Success", "The employee request was approved!");
        }
        else { 
            showDangerAlert("Something went wrong, please contact administration"); 
            // showAlertModal("Error", "Something went wrong, please contact administration") 
        }
    });
}

function sendDeleteRequest(requestId, element){
    $.ajax({
        method: "POST", 
        url : "{{ url('deleteEmployeeRequest') }}",
        data : {"_token" : "{{ csrf_token() }}", "employeeRequestId" : requestId, corpId :  {{ $corpId }}}
    }).done(function (response){
        if(response == "true") { 
                $("[data-delete-id='"+requestId+"']").closest("tr").remove();
                $("[data-reactivate-id='"+requestId+"']").closest("tr").remove();
                showSuccessAlert(" The employee request successfully deleted!");
                // showAlertModal("Success", "The employee request was deleted!"); 
        }
        else { 
            showDangerAlert("Something Went Wrong, Please Contact Administration");
            // showAlertModal("Error", "Something Went Wrong, Please Contact Administration") 
        }
    });
}

function approveRequest(requestId){
    bootbox.confirm({
    title: "Request Confirmation",
    message: "Are you sure you want to approve this request?",
    buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Back', 
            className: "btn-default modal-back"
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Approve', 
            className: "btn-success"
        }
    },
    callback: function(result){ 
        if( result == true ) { sendApproveRequest(requestId)} }
    });
} 

function deleteRequest(requestId, element){
    bootbox.confirm({
    title: "Request Confirmation",
    message: "Are you sure you want to delete this request?",
    buttons: {
        cancel: {
            label: '<i class="fa fa-times"></i> Back',
            className: 'btn-default modal-back'
        },
        confirm: {
            label: '<i class="fa fa-check"></i> Delete',
            className: 'btn-danger'
        }
    },
    callback: function(result){ 
        if( result == true ) { sendDeleteRequest(requestId, element)} }
    });
}

$(document).ready(function (){
    $("#employee_filters").insertAfter("#employeeRequestsDatatable_filter");
});
</script>
