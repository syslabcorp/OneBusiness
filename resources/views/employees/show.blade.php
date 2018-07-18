@extends('layouts.custom')

@section('header_styles')
  <link href="{{ asset('css/my.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')

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
          <div class="row">
            <div class="col-md-12">
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
                  @include('employees.document', ['user'=> $user])
                  @include('employees.shortages')
                  @include('employees.tardiness')
                  @include('employees.positionBranch', ['user'=> $user])
                  @include('employees.wage', ['user'=> $user])
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <a href="{{ URL::previous() }}" class="btn btn-default">Back</a>
          <a class="btn btn-primary pull-right" id="save_employee" style="display: none;">Save</a>
        </div>
      </div>
    </div>
  </div>

<script src="http://onebusiness.shacknet.biz/OneBusiness/js/table-edits.min.js"></script>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/momentjs.min.js"></script>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/bootstrap-datetimepicker.min.js"></script>

<script>

$(document).ready(function() {
  var tableDocument = $('#table-document-deliveries').DataTable({

      initComplete: function() {

      },

      ajax: '{{ route('employee.deliveryDocuments', ['id' => $user->UserID,'corpID' => $corpID]) }}',
      columns: [
        {
          targets: 0,
          data: "txn_id"
        },
        {
          targets: 1,
          data: "Series",
        },
        {
          targets: 2,
          data: "Approval"
        },
        {
          targets: 3,
          data: "Branch"
        },
        {
          targets: 4,
          data: "Category"
        },
        {
          targets: 5,
          data: "Document"
        },
        {
          targets: 6,
          data: "Notes"
        },
        {
          targets: 7,
          data: "Expiry"
        },
        {
          targets: 8,
          data: "Image",
          render: (data, type, row, meta) => {
            return `<a href='{{ route('employee.index') }}/`+ {{$user->UserID}} +`?corpID={{ $corpID }}'>${data}</a>`;
          }
        },
        {
          targets: 9,
          data: "DateArchived"
        },
        {
          targets: 10,
          render: (data, type, row, meta) => {
            return `<button class="btn btn-primary fa fa-pencil-alt"> </button>`
          }
        }
      ],
      order: [
        [0, 'desc']
      ]
  });

  var tablePosition = $('#table-position-deliveries').DataTable({

      initComplete: function() {

      },

      ajax: '{{ route('employee.deliveryPositions', ['id' => $user->UserID,'corpID' => $corpID]) }}',
      columns: [
        {
          targets: 0,
          data: "Branch"
        },
        {
          targets: 1,
          data: "StartDate",
        },
        {
          targets: 2,
          data: "SeparationDate"
        },
        {
          targets: 3,
          data: "Position"
        },
        {
          targets: 4,
          data: "Status"
        }
      ],
      order: [
        [0, 'desc']
      ]
  });

  var tableWage = $('#table-wage-deliveries').DataTable({

    initComplete: function() {

    },

    ajax: '{{ route('employee.deliveryWages', ['id' => $user->UserID,'corpID' => $corpID]) }}',
    columns: [
      {
        targets: 0,
        data: "EffectiveDate"
      },
      {
        targets: 1,
        data: "BaseRate",
      },
      {
        targets: 2,
        data: "PayCode"
      },
      {
        targets: 3,
        data: "PayBasic"
      }
    ],
    order: [
      [0, 'desc']
    ]
  });

  $("#edit_employee").click(function(){
    $(this).attr("disabled", true);
    $('.disabled').removeAttr('disabled');
    $('#save_employee').show();
  })

  $("#save_employee").click(function(){
    $('#employee_form').submit();
  })

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
