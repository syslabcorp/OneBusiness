<head>
    <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script> -->
    

</head>
<section class="content">
    <div class="row">
        <div class="col-md-12" style="padding: 0; overflow-x: auto;">
            <div id="employee_filters" style="margin-bottom: 7px; clear: both;">
                  Filters 
                  <select style="width: 128px; display: inline;" class="form-control approved-filter">
                           <!-- <option value="any">All Requests</option> -->
                           <option value="uploaded">Uploaded</option>
                           <option value="approved">Approved</option>
                           <option value="for_approval" selected>For Approval</option>
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
                        <th>Approved By</th>
                        <th>Date Approved</th>
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
        "order": [],
        "ajax": {
                url: "{{ url('getEmployeeRequests') }}",
                data: function (d) {
                        d.approved = $(".approved-filter").val();
                        d.corpId = "{{ $corpId }}";
                }
        },
        columns: [
                {data: 'username', name: 'username'},
                {data: 'type', name: 'type'},
                {data: 'from_branch', name: 'from_branch'},
                {data: 'date_end', name: 'date_end'},
                {data: 'to_branch_name', name: 'to_branch_name'},
                {data: 'date_start', name: 'date_start'},
                {data: 'approved', name: 'approved'},
                {data: 'executed', name: 'executed'},
                {data: 'approvedBy', name: 'approvedBy' },
                {data: 'DateApproved', name: 'DateApproved'},
                {data: 'sex', name: 'sex'},
                {data: 'bday', name: 'bday'},
                {data: 'SSS', name: 'SSS'},
                {data: 'PHIC', name: 'PHIC'},
                {data: 'pagibig', name: 'pagibig'},
                {data: 'action', name: 'action', sortable: false, searchable: false}
        ],
        "drawCallback" : function( settings ) {
            tippy("[title]", {
                arrow: true,
                placement: 'left',
                size: "large"
            });
        },
});

function defineVisibilityOfColumns() {
    if($(".approved-filter").val() == "uploaded" || $(".approved-filter").val() == "approved") {
        employeeRequestsDatatable.column( "th:contains(Approved By)" ).visible( true );
        employeeRequestsDatatable.column( "th:contains(Date Approved)" ).visible( true );
    } else {
        employeeRequestsDatatable.column( "th:contains(Approved By)" ).visible( false );
        employeeRequestsDatatable.column( "th:contains(Date Approved)" ).visible( false );
    }
}

$('.approved-filter').on('change', function () {
        if($(this).val() == "for_approval") {
            // show_table_columns();
        } else {
            // hide_table_columns();
        }
        employeeRequestsDatatable.draw();
        defineVisibilityOfColumns();
});

function hide_table_columns(){
    var column = employeeRequestsDatatable.column( 11 ); column.visible( false );
    var column2 = employeeRequestsDatatable.column( 10 ); column2.visible( false );
    var column3 = employeeRequestsDatatable.column( 12 ); column3.visible( false );
}

function show_table_columns(){
    var column = employeeRequestsDatatable.column( 11 ); column.visible( true );
    var column4 = employeeRequestsDatatable.column( 10 ); column4.visible( true );
    var column5 = employeeRequestsDatatable.column( 12); column5.visible( true );
}

function sendApproveRequest(requestId){
    $.ajax({
        method: "POST", 
        url : "{{ url('approveEmployeeRequest') }}",
        data : {"_token" : "{{ csrf_token() }}", "employeeRequestId" : requestId, corpId :  {{ $corpId }}}
    }).done(function (response){
        if(response == "true") { 
                employeeRequestsDatatable.draw();
                reactivateEmployeeDatatable.draw();
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
                employeeRequestsDatatable.draw();
                reactivateEmployeeDatatable.draw();
                // $("[data-delete-id='"+requestId+"']").closest("tr").remove();
                // $("[data-reactivate-id='"+requestId+"']").closest("tr").remove();
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
    $("#employee_filters").insertAfter("#employeeRequestsDatatable_wrapper .dataTables_length");
    defineVisibilityOfColumns();
});
</script>
