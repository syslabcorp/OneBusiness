@extends('layouts.custom')

@if( auth()->user()->isAdmin() && !session()->get('error') )

@section('head')

    <!--Stile from jquery.dataTables.min.css-->
    <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">
    <!--END stile from jquery.dataTables.min.css-->
    
    <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">
    <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js"></script>
    

@endSection

@section('content')
<div class="row">
    
    <div class="col-md-12">
        <div style="display: none;" class="alert-dismissible alert alert-success alertfade"><span class="fa fa-close"> </span><em> </em></div>
        <div style="display: none;" class="alert alert-danger alertfade"><span class="fa fa-close"> </span><em> </em></div>
    </div>
    
</div>

<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <div class="row">
                        <h4>Recommendation Requests</h4>
                    </div>
                </div>
                <div class="panel-body">
                    
                    <div class="tab-content">
                        @include('branchs.recommendationRequest.includes.recommendationRequests')
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
</section>
@endsection

@section('indexpageScripts')

<script type="text/javascript">
function showAlertModal(title, message) {
  bootbox.alert({
      title: title,
      message: message
  });
}

function showSuccessAlert(message) {
   
  $(".alert-success em").html(message);
  $(".alert-success").fadeIn();
  $("html, body").animate({scrollTop: 0}, "fast");
  setTimeout(function () {
      $(".alert-success").fadeOut();
  }, 3000);
}

function showDangerAlert(message) {
  $(".alert-danger em").html(message);
  $(".alert-danger").fadeIn();
  $("html, body").animate({scrollTop: 0}, "normal");
  setTimeout(function () {
      $(".alert-danger").fadeOut();
  }, 3000);
}

$(document).ready(function () {
  $("[type='search']").addClass("form-control");
  $("[name='employeeRequestsDatatable_length'],[name='reactivateEmployeeDatatable_length']").addClass("form-control");
});
</script>
@endsection

@endif