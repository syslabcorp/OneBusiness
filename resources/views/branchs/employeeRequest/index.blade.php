@extends('layouts.custom')

@section('content')
<head>
<style>
table { 
  width: 100%; 
  border-collapse: collapse; 
}
/* Zebra striping */
tr:nth-of-type(odd) { 
  background: #eee; 
}
th { 
  background: #333; 
  color: white; 
  font-weight: bold; 
}
td, th { 
  padding: 6px; 
  border: 1px solid #ccc; 
  text-align: left; 
}
/* 
Max width before this PARTICULAR table gets nasty
This query will take effect for any screen smaller than 760px
and also iPads specifically.
*/
@media 
only screen and (max-width: 760px),
(min-device-width: 768px) and (max-device-width: 1024px)  {

    /* Force table to not be like tables anymore */
    table, thead, tbody, th, td, tr { 
        display: block; 
    }
    
    /* Hide table headers (but not display: none;, for accessibility) */
    thead tr { 
        position: absolute;
        top: -9999px;
        left: -9999px;
    }
    
    tr { border: 1px solid #ccc; }
    
    td { 
        /* Behave  like a "row" */
        border: none;
        border-bottom: 1px solid #eee; 
        position: relative;
        padding-left: 50%; 
    }
    
    td:before { 
        /* Now like a table header */
        position: absolute;
        /* Top/left values mimic padding */
        top: 6px;
        left: 6px;
        width: 45%; 
        padding-right: 10px; 
        white-space: nowrap;
    }
    
    /*
    Label the data
    */
    td:nth-of-type(1):before { content: "First Name"; }
    td:nth-of-type(2):before { content: "Last Name"; }
    td:nth-of-type(3):before { content: "Job Title"; }
    td:nth-of-type(4):before { content: "Favorite Color"; }
    td:nth-of-type(5):before { content: "Wars of Trek?"; }
    td:nth-of-type(6):before { content: "Porn Name"; }
    td:nth-of-type(7):before { content: "Date of Birth"; }
    td:nth-of-type(8):before { content: "Dream Vacation City"; }
    td:nth-of-type(9):before { content: "GPA"; }
    td:nth-of-type(10):before { content: "Arbitrary Data"; }
}
</style>
</head>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <table>
    <thead>
    <tr>
        <th>Name</th>
        <th>From Branch</th>
        <th>To Branch</th>
        <th style="text-align: center;">Action</th>
    </tr>
    </thead>
    <tbody id="tableBody">
    </tbody>
</table>
        </div>
      </div>
</section>
<script>
function sendRequest(){
    $.ajax({
    method: "POST",
    url: '{{ url("branchRequest/getEmployeeRequests") }}',
    data : {"_token": "{{ csrf_token() }}", corpId:{{Request::segment(2)}}}
    }).done(function (response){
        populateTable(response);
    });
}
function populateTable(arr){
    $("#tableBody").html("");
    arr.forEach(function(item) {
        $("#tableBody").append('<tr><td>'+item.user.uname+'</td><td>'+item.from_branch.ShortName+'</td><td>'+item.to_branch.ShortName+'</td><td style="text-align: center;"><img src="https://cdn3.iconfinder.com/data/icons/musthave/24/Check.png"><img src="https://cdn3.iconfinder.com/data/icons/musthave/24/Cancel.png"></td></tr>');
    });
}
sendRequest();
</script>
@endsection
