

<section class="content">
    <div class="row">
        <div class="col-md-12" style="padding: 0; overflow-x: auto;">
            <div id="employee_filters" style="margin-bottom: 7px; clear: both;">
                  Filters 
                  <select style="width: 128px; display: inline;" class="form-control approved-filter">
                           <option value="for_approval" selected>For Approval</option>
                           <option value="any">All Requests</option> 
                           <option value="approved">Approved</option>
                  </select>
           </div>
            <table id="recommendationRequestsDatatable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th style="background-position: center left !important;">Name</th>
                        <th style="background-position: center left !important;">Wage Code (From)</th>
                        <th style="background-position: center left !important;">Wage Code (To)</th>
                        <th style="background-position: center left !important;">Approved</th>
                        <th style="background-position: center left !important;">Deleted</th>
                        <th style="background-position: center left !important;">Effective Date</th>
                        <th style="background-position: center left !important;">Recommended By</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                
            </tbody>
                <tfoot>
                </tfoot>
            </table>

        </div>
      </div>
</section>
@section('pageScripts')
<script>
let recommendationRequestsDatatable = $('#recommendationRequestsDatatable').DataTable({
        processing: true,
        serverSide: true,
        "order": [],
        // "aaSorting": [],
        "ajax": {
                
                url: "{{ route('getRecommendation') }}",
                data: function (d) {
                        d.approved = $(".approved-filter").val();
                        d.corpId =  {{ $corpId }} ;
                        
                }
        },
        columns: [
                {data: 'name', name: 'name'},
                {data: 'from_wage', name: 'from_wage'},
                {data: 'to_wage', name: 'to_wage'},
                {data: 'approved', name: 'approved' , render: function ( data, type, row ) {
                            
                            let checked = "";
                            if( data == 1 ) { 
                                checked = "checked"; 
                            }
                            
                            return '<input type=checkbox ' + checked + ' disabled />';
                            
                        }},
                {data: 'deleted', name: 'deleted', render: function ( data, type, row ) {
                            
                            let checked = "";
                            if(data == 1) { 
                                checked = "checked"; 
                            }
                            return '<input type=checkbox ' + checked + ' disabled />';
                            
                        }},
                {data: 'effective_date', name: 'effective_date'},
                {data: 'recommended_by', name: 'recommended_by'},
                {data: 'action', name: 'action', sortable: false, searchable: false}
        ],
        columnDefs: [
            {
                targets: -1,
                className: 'dt-body-center '
            },
            {
                targets: 3,
                className: 'dt-body-center'
            },
            {
                targets: 4,
                className: 'dt-body-center'
            }
        ]
        
        // initComplete: function () {
        //     this.api().columns().every(function () {
        //         var column = this;
        //         var input = document.createElement("input");
        //         $(input).appendTo($(column.footer()).empty())
        //         .on('change', function () {
        //             column.search($(this).val(), false, false, true).draw();
        //         });
        //     });
        // }
});
$('.approved-filter').on('change', function () {
        if($(this).val() == "any") {
            //show_table_columns();
        } else {
            //hide_table_columns();
        }
        recommendationRequestsDatatable.draw();
});

function hide_table_columns(){
    var column = recommendationRequestsDatatable.column( 4 ); column.visible( false );
    
}

function show_table_columns(){
    var column = recommendationRequestsDatatable.column( 4 ); column.visible( true );
   
}

function sendApproveRequest(requestId){
    $.ajax({
        method: "POST", 
        url : "{{ route('approveRecommendation') }}",
        data : {"_token" : "{{ csrf_token() }}", "recommendationId" : requestId, "corpId" : {{ $corpId }} }
    }).done( function (response){
        if(response == "approved") { 
            
                recommendationRequestsDatatable.draw();
                showSuccessAlert(" Recommendation successfully approved!");
                
        }
        else { 
            showDangerAlert("Something went wrong, please contact administration"); 
            
        }
    });
}

function sendDeleteRequest(requestId){
    $.ajax({
        method: "POST", 
        url : "{{ route('deleteRecommendation') }}",
        data : {"_token" : "{{ csrf_token() }}", "recommendationId" : requestId, corpId :  {{ $corpId }}
    }}).done(function (response){
        
        if(response == "deleted") { 
            
                
                showSuccessAlert(" Recommendation successfully deleted!");
                recommendationRequestsDatatable.draw();
                
        }
        else { 
            alert('Something Went Wrong with deleted');
            showDangerAlert("Something Went Wrong, Please Contact Administration");
            
        }
    });
}

function approveRequest(requestId , fromWage , toWage , userName ){
    
        
    bootbox.confirm({
    title: "Appove Recommendation",
    message: "You are about to approve recommendation of <b>"+fromWage+"</b> to <b>"+toWage+"</b> for <b>"+userName+"</b>.<br/>Are you sure?",
    buttons: {
        cancel: {
            label: 'Cancel', 
            className: "btn-default modal-back"
        },
        confirm: {
            label: 'Approve', 
            className: "btn-success"
        }
    },
    callback: function(result){ 
        if( result == true ) { sendApproveRequest(requestId)} }
    });
} 

function deleteRequest(requestId, fromWage , toWage , userName){
    
    bootbox.confirm({
    title: "Delete Recommendation",
    message: "You are about to delete recommendation of <b>"+fromWage+"</b> to <b>"+toWage+"</b> for <b>"+userName+"</b>.<br/>Are you sure?",
    buttons: {
        cancel: {
            label: 'Cancel',
            className: 'btn-default modal-back'
        },
        confirm: {
            label: 'DELETE',
            className: 'btn-danger'
        }
    },
    callback: function(result){ 
        if( result == true ) { sendDeleteRequest(requestId)} }
    });
}

$(document).ready(function (){
    $("#employee_filters").insertAfter("#recommendationRequestsDatatable_wrapper .dataTables_length");
    // hide_table_columns();
    // hide_table_columns();
});
</script>
@endsection

