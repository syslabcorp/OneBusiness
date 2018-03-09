@extends('layouts.custom')

@section('content')
<head>
  <link href="//cdn.datatables.net/1.10.7/css/jquery.dataTables.min.css" rel="stylesheet">
  <script src="//cdn.datatables.net/1.10.7/js/jquery.dataTables.min.js"></script>
  <script src="{{ url('public/js/bootbox.min.js') }}"></script>
  <link rel="stylesheet" type="text/css" href="{{ url("public/css/branches/table_styles.css") }}">
  <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
</head>

<div class="row">
  <div class="col-md-12">
    <div style="" class="alert-dismissible alert alert-success alertfade"><span class="fa fa-close"> </span><em> </em></div>
    <div style="" class="alert alert-danger alertfade"><span class="fa fa-close"> </span><em> </em></div>
  </div>
</div>
<section class="content">
    <div class="row">
        <div class="col-md-12">
          <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                        <h4>Employee Requests</h4>
                </div>
            </div>
            <div class="panel-body">
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
            </div>
            
          </div>
        </div>
      </div>
</section>


  <script type="text/javascript">
  function showAlertModal(title, message){
      bootbox.alert({
          title: title,
          message: message
      });
  }

  function showSuccessAlert(message){
    $(".alert-success em").html(message);
    $(".alert-success").fadeIn();
    $("html, body").animate({ scrollTop: 0 }, "fast");
    setTimeout(function (){ $(".alert-success").fadeOut(); }, 3000);
  }

  function showDangerAlert(message){
    $(".alert-danger em").html(message);
    $(".alert-danger").fadeIn();
    $("html, body").animate({ scrollTop: 0 }, "normal");
    setTimeout(function (){ $(".alert-danger").fadeOut(); }, 3000);
  }
  
  $(document).ready(function (){
    $("[type='search']").addClass("form-control");
    $("[name='employeeRequestsDatatable_length'],[name='reactivateEmployeeDatatable_length']").addClass("form-control");
  });
  </script>
@endsection
