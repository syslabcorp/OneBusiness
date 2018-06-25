@extends('layouts.app')

@section('header_styles')
  <link href="{{ asset('css/my.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
<div class="container-fluid">
<div class="row">

<div id="togle-sidebar-sec" class="active">

      <!-- Sidebar -->
       <div id="sidebar-togle-sidebar-sec">
          <div class="sidebar-nav">
            <ul></ul>
          </div>
        </div>

      <div id="page-content-togle-sidebar-sec">
    @if(Session::has('success'))
      <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('success') !!}</em></div>
    @elseif(Session::has('error'))
      <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="fa fa-close"></span><em> {!! session('error') !!}</em></div>
    @endif
    <div class="col-md-12">
      <div class="row">
            <div class="panel panel-default">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-9">
                    <h4>Employee Profile</h4>
                  </div>
                </div>
              </div>
              <div class="panel-body">
                <section class="content">
                  <div class="row">
                      <div class="col-md-12">
                        <div class="panel">
                          <div class="panel-body">
                            <div class="bs-example">
                              <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                                <li class="{{ $tab == 'auto' ? 'active' : '' }}">
                                  <a href="#personInfo" data-toggle="tab">Personal Information</a>
                                </li>
                                <li class="{{ $tab == 'stock' ? 'active' : '' }}">
                                  <a href="#document" data-toggle="tab">Document</a>
                                </li>
                                <li class="{{ $tab == 'stock' ? 'active' : '' }}">
                                  <a href="#shortages" data-toggle="tab">Shortages</a>
                                </li>
                                <li class="{{ $tab == 'stock' ? 'active' : '' }}">
                                  <a href="#tardiness" data-toggle="tab">Tardiness</a>
                                </li>
                                <li class="{{ $tab == 'stock' ? 'active' : '' }}">
                                  <a href="#position" data-toggle="tab">Position-Branch Movement</a>
                                </li>
                                <li class="{{ $tab == 'stock' ? 'active' : '' }}">
                                  <a href="#wage" data-toggle="tab">Wage Movement</a>
                                </li>
                              </ul>
                              <div  class="tab-content" style="padding: 1em;">
                                @include('employees.personInfo', ['user'=> $user])
                                @include('employees.document')
                                @include('employees.shortages')
                                @include('employees.tardiness')
                                @include('employees.positionBranch')
                                @include('employees.wage')
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </section>
              </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/table-edits.min.js"></script>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/momentjs.min.js"></script>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/bootstrap-datetimepicker.min.js"></script>

<script>

$(document).ready(function() {

  });

  deleteStock = (id) => {
    let self = $(event.target)

    swal({
      title: "<div class='delete-title'>Confirm Delete</div>",
      text:  "<div class='delete-text'>Are you sure you want to delete DR#" + id + "?</strong></div>",
      html:  true,
      customClass: 'swal-wide',
      showCancelButton: true,
      confirmButtonClass: 'btn-success',
      closeOnConfirm: false,
      closeOnCancel: true
    },(confirm) => {
      $.ajax({
        url : 'stocktransfer/' + id + '?corpID={{ $corpID }}' ,
        type : 'DELETE',
        success: (res) => {
          showAlertMessage('DR#' + id + ' has been deleted!', 'Success')
          self.parents('tr').remove()
        }
      })
    })
  }



function onEditRow(param){
    if($('#editable'+param).hasClass('glyphicon-pencil')){

         $(".rcvdCheckbox"+param).attr("disabled", false);
         $(".uploadCheckbox"+param).attr("disabled", false);
    }
    else{

         $(".rcvdCheckbox"+param).attr("disabled", true);
         $(".uploadCheckbox"+param).attr("disabled", true);

    }

}

</script>

<script>
    var tmasterId;
    var urlmarkToserved;

    filterStatusStock = () => {
      let path = location.search.replace(/&stockStatus=[0-9]+/g, '').replace(/&status=[0-9]+/g, '')
      path = path.replace(/&tab=[a-z]+/g, '') + "&tab=stock"
      path +=  "&stockStatus=" + $('#stockStatus select').val()
      window.location = location.pathname + path
    }

    function filterStatus(event) {
      let path = location.search.replace(/&statusStock=[0-9]+/g, '').replace(/&status=[0-9]+/g, '')
      path = path.replace(/&tab=[a-z]+/g, '') + "&tab=auto"
      path +=  "&status=" + $('#selectId select').val()
      window.location = location.pathname + path
    }

  showAlertMessage = (message, title = "Alert", isReload = false) => {
    swal({
      title: "<div class='delete-title'>" + title + "</div>",
      text:  "<div class='delete-text'>" + message + "</strong></div>",
      html:  true,
      customClass: 'swal-wide',
      showCancelButton: false,
      closeOnConfirm: true,
      allowEscapeKey: !isReload
    }, (data) => {
      if(isReload) {
        window.location.reload()
      }
    });
  }

  markToserved = (event, id) => {
    let self = $(event.target)

    swal({
      title: "<div class='delete-title'>Mark to served</div>",
      text:  "<div class='delete-text'>Serve PO: Are you sure you want to mark " + id + " as served?</strong></div>",
      html:  true,
      customClass: 'swal-wide',
      showCancelButton: true,
      confirmButtonClass: 'btn-success',
      closeOnConfirm: false,
      closeOnCancel: true
    },(confirm) => {
      $.ajax({
        url : 'stocktransfer/' + id + '/served?corpID={{ $corpID }}' ,
        type : 'POST',
        success: (res) => {
          showAlertMessage('P.O.# ' + id + ' has been served', 'Success')
          self.parents('tr').remove()
        }
      })
    })
  }

</script>

<script>
    $(function() {
      var pickers = {};

      $('table tr').editable({

        dropdowns: {
          sex: ['Male', 'Female']
        },
        edit: function(values) {

          $(".edit span", this)
            .removeClass('glyphicon-pencil')
            .addClass('glyphicon-ok')
            .attr('title', 'Save');
        },
        save: function(values) {
          $(".edit span", this)
            .removeClass('glyphicon-ok')
            .addClass('glyphicon-pencil')
            .attr('title', 'Edit');



          if (this in pickers) {
            pickers[this].destroy();
            delete pickers[this];
          }
        },
        cancel: function(values) {
          $(".edit i", this)
            .removeClass('glyphicon-ok')
            .addClass('glyphicon-pencil')
            .attr('title', 'Edit');

          if (this in pickers) {
            pickers[this].destroy();
            delete pickers[this];
          }
        }
      });
    });
  </script>
@endsection
