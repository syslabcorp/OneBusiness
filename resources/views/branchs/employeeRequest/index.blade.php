@extends('layouts.custom')

@section('content')
<head>
<link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
</head>

  <ul class="nav nav-tabs" style="margin-bottom:15px;">
    <li class="active"><a data-toggle="tab" href="#EmployeeRequests">Employee Requests</a></li>
    <li><a data-toggle="tab" href="#ReactivateEmployee">Reactivate Employee</a></li>
  </ul>

  <div class="tab-content">
    <div id="EmployeeRequests" class="tab-pane fade in active">
        @include("branchs.employeeRequest.includes.employeeRequests")
    </div>
    <div id="ReactivateEmployee" class="tab-pane fade">
        @include("branchs.employeeRequest.includes.reactivateEmployee")
    </div>
  </div>
@endsection
