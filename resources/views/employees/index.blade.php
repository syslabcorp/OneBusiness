@extends('layouts.custom')

@section('header_styles')
  <link href="{{ asset('css/my.css') }}" rel="stylesheet" type="text/css"/>
@endsection

@section('content')
  <div class="box-content">
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
            <div class="bs-example">
              <div class="row">
                <div class="table-responsive">
                  <table id="table-deliveries" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                      <tr>
                        <th style="min-width: 50px">ID</th>
                        <th style="min-width: 70px">Employee Name</th>
                        <th style="min-width: 70px">Address</th>
                        <th style="min-width: 70px">Date of Birth</th>
                        <th style="min-width: 40px">Age</th>
                        <th style="min-width: 30px">Sex</th>
                        <th style="min-width: 70px">Branch</th>
                        <th style="min-width: 70px">Department</th>
                        <th style="min-width: 70px">Position</th>
                        <th style="min-width: 70px">Date Hired</th>
                        <th style="min-width: 70px">Base Salary</th>
                        <th style="min-width: 70px">Pay Code</th>
                        <th style="min-width: 90px">SSS#</th>
                        <th style="min-width: 90px">PHIC#</th>
                        <th style="min-width: 90px">HDMF#</th>
                        <th style="min-width: 70px">Account#</th>
                        <th style="min-width: 70px">Type</th>
                      </tr>
                    </thead>
                    <tbody >
                    </tbody>
                  </table>
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
    var table = $('#table-deliveries').DataTable({

      "dom": '<"row"<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f>><"col-md-3 branch-filter"><"col-md-3 empStatus"><"col-md-3 lvl"><"col-md-3 sort">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      initComplete: function() {
      $(".branch-filter").append('<div class="row"><label class="filterLabel1"> <input value="hasBranch" type="checkbox" class="check-branch" name="selectBranch" /> Select Branch</label></div><div class="row"><select name="branch" class="select-branch form-control"></select></div>')
      $(".branch-filter select").val('{{ $branch }}')
      $('.branch-filter .check-branch').prop('checked', {{$branchSelect ? "true" : "false"}})
      $('.select-branch').prop('disabled', {{$branchSelect ? "false" : "true"}} )

      $('.empStatus').append('<div class="row"><label class="filterLabel1"> Employee Status</label></div><div class="row"><label class="filterLabel1"><input type="radio" value="1" name="status">Active</label><label class="filterLabel1"><input type="radio" value="2" name="status">Inactive</label><label class="filterLabel1"><input type="radio" name="status"  value="all">All</label></div>')
      $(".empStatus input[name=status][value='{{ $status }}']").prop("checked",true)

      $('.lvl').append('<div class="row"><label class="filterLabel1"> Level</label></div><div class="row"><label class="filterLabel1"><input value="non-branch" type="radio" name="level">Non-branch</label><label class="filterLabel1"><input type="radio" value="branch" name="level">Branch</label><label class="filterLabel1"><input type="radio" value="all" name="level">All</label></div>')
      $(".lvl input[name=level][value='{{ $level }}']").prop("checked",true);
      $('.sort').append('<div class="row"><a> Sort Order</a></div><div class="row">Branch > Position > Department</div>')
      @foreach($branches as $branch)
        $(".branch-filter select").append("<option value='{{$branch->Branch}}'>{{$branch->ShortName}}</option>")
      @endforeach

      },

      ajax: '{{ route('employee.deliveryItems', ['corpID' => $corpID, 'branchSelect' => $branchSelect, 'branch' => $branch, 'status' => $status, 'level'=>$level]) }}',
      columns: [
        {
          targets: 0,
          data: "UserID"
        },
        {
          targets: 1,
          data: "UserName",
          render: (data, type, row, meta) => {
            return "<a href='{{ route('employee.index') }}/" + row.UserID + "?corpID={{ $corpID }}'>"+ data +"</a>";
          }
        },
        {
          targets: 2,
          data: "Address"
        },
        {
          targets: 3,
          data: "BDay"
        },
        {
          targets: 4,
          data: "Age"
        },
        {
          targets: 5,
          data: "Sex"
        },
        {
          targets: 6,
          data: "Branch"
        },
        {
          targets: 7,
          data: "Department"
        },
        {
          targets: 8,
          data: "Position"
        },
        {
          targets: 9,
          data: "DateHired"
        },
        {
          targets: 10,
          data: "BaseSalary"
        },
        {
          targets: 11,
          data: "PayCode"
        },
        {
          targets: 12,
          data: "SSS"
        },
        {
          targets: 13,
          data: "PHCI"
        },
        {
          targets: 14,
          data: "HDMF"
        },
        {
          targets: 15,
          data: "Account"
        },
        {
          targets: 16,
          data: "Type"
        }
      ],
      order: [
        [0, 'desc']
      ]
    });

    $('body').on('change', 'input[type=radio][name=status], input[type=radio][name=level], .select-branch, .check-branch',
      () => {
        let url = "<?php echo route('employee.deliveryItems', ['corpID' => $corpID, 'branchSelect' => 'targetBranchSelect', 'branch' => 'targetBranch', 'status' => "targetStatus", 'level'=>"targetLevel"]) ?>"
        let status = $('input[type=radio][name=status]:checked').val()
        let branchSelect = $('input[name=selectBranch]').prop('checked') ? "hasBranch" : ""
        let branch = $('.select-branch').val();
        let level = $('input[type=radio][name=level]:checked').val();
        url = url.replace('targetStatus', status).replace('targetBranchSelect', branchSelect)
                  .replace('targetBranch', branch).replace('targetLevel', level)
        table.ajax.url( url ).load()
      }
    )

    $('body').on( "change", ".check-branch",
      (event) => {
        if ($('.check-branch').is(":checked"))
        {
          $('.select-branch').prop('disabled', false);
        }
        else
        {
          $('.select-branch').prop('disabled', true);
        }
      }
    )


  });

  // table.columns().flatten().each( function ( Active ) {
  //     // Create the select list and search operation

  //         .on( 'change', function () {
  //             table
  //                 .column( Active )
  //                 .search( $(this).val() )
  //                 .draw();
  //         } );

  //     // Get the search data for the first column and add to the select list
  //     table
  //         .column( colIdx )
  //         .cache( 'search' )
  //         .sort()
  //         .unique()
  //         .each( function ( d ) {
  //             select.append( $('<option value="'+d+'">'+d+'</option>') );
  //         } );
  // } );


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

showHidden = (isShow) => {
  if(isShow)
    $('#addNewTransfer').append('<a href="{{route('stocktransfer.create' , ['corpID' => $corpID] )}}"  class="pull-right">New Stock Transfer</a>')
  else
    $('#addNewTransfer').empty()
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
