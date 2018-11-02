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
			 <h3 class="text-center">Stock Transfer</h3>
    	<div class="row">
            <div class="panel panel-default">
              <div class="panel-heading">
                <div class="row">
                  <div class="col-xs-9">
                    <h4>Stock Transfer</h4>
                  </div>
                  @if(\Auth::user()->checkAccessByIdForCorp($corpID, 42, 'A'))
                  <div class="col-xs-3"  id="addNewTransfer" >
                  </div>
                  @endif
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
                                            <a href="#access" data-toggle="tab"  onclick="showHidden(false)">Auto stock transfer</a>
                                          </li>
                                          <li class="{{ $tab == 'stock' ? 'active' : '' }}">
                                            <a href="#tasks" data-toggle="tab" onclick="showHidden(true)">Stock Delivery</a>
                                          </li>
                                      	</ul>
                                        <div  class="tab-content" style="padding: 1em;">
                                          <div class="tab-pane fade {{ $tab == 'auto' ? 'active in' : '' }} in" id="access" >
                                            @if(\Auth::user()->checkAccessByIdForCorp($corpID, 43, 'V'))
                                            <div class="row">
                                              <div class="table-responsive">
                                                <table id="list_menu" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                                    <thead>
                                                        <tr>
                                                            <th>P.O.#</th>
                                                            <th>P.O.Date</th>
                                                            <th>P.O.Template</th>
                                                            <th>Status</th>
                                                            <th>Total Count</th>
                                                            <th>Total Amount</th>
                                                            <th>Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    </tbody>
                                                  </table>
                                              </div>
                                            </div>
                                            @else
                                            <div class="alert alert-danger no-close">
                                              You don't have permission
                                            </div>
                                            @endif
                                          </div>
                                            <div class="tab-pane fade {{ $tab == 'stock' ? 'active in' : '' }}" id="tasks" >
                                              @if(\Auth::user()->checkAccessByIdForCorp($corpID, 42, 'V'))
                                              <div class="row">
                                                <div class="table-responsive">
                                                <table id="table-deliveries" class="col-sm-12 table table-striped table-bordered" cellspacing="0" width="100%">
                                                  <thead>
                                                    <tr>
                                                      <th>D.R.No</th>
                                                      <th>Date</th>
                                                      <th>Destination</th>
                                                      <th>Rcvd</th>
                                                      <th>Uploaded</th>
                                                      <th>Received By</th>
                                                      <th>Date Received</th>
                                                      <th>Action</th>
                                                    </tr>
                                                  </thead>
                                                  <tbody >
                                                  </tbody>
                                                  </table>
                                                </div>
                                              </div>
                                              @else
                                              <div class="alert alert-danger no-close">
                                                You don't have permission
                                              </div>
                                              @endif
                                            </div>
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
    $('#table-deliveries').DataTable({
      "dom": '<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f><"#stockStatus">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      initComplete: function() {
        $("#stockStatus").append('<div class="filterDiv1"><label class="filterLabel1"><strong>Filters:</strong> </label><select onChange="filterStatusStock()" class="form-control"><option value="1">In-transit</option><option value="2">Received</option><option value="3">All </option></select></div>');
        $("#stockStatus select").val('{{ $stockStatus }}');
      },
      ajaxSource: '{{ route('stocktransfer.deliveryItems', ['corpID' => $corpID]) }}&stockStatus= {{ $stockStatus }}',
      columnDefs: [
        {
          targets: 0,
          data: "Txfr_ID"
        },
        {
          targets: 1,
          data: "Txfr_Date"
        },
        {
          targets: 2,
          data: "Txfr_To_Branch"
        },
        {
          targets: 3,
          data: 'Rcvd',
          className: 'text-center',
          render: (data, type, row, meta) => {
            return '<input type="checkbox" onclick="return false;" ' + (data == 1 ? 'checked' : '') + '/>'
          }
        },
        {
          targets: 4,
          data: 'Uploaded',
          className: 'text-center',
          render: (data, type, row, meta) => {
            return '<input type="checkbox" onclick="return false;" ' + (data == 1 ? 'checked' : '') + '/>'
          }
        },
        {
          targets: 5,
          data: 'ReceivedBy'
        },
        {
          targets: 6,
          data: 'DateRcvd'
        },
        {
          targets: 7,
          data: '',
          render: (data, type, row, meta) => {
            return '<a class="btn btn-primary btn-md edit" title="Edit" \
              {{ \Auth::user()->checkAccessByIdForCorp($corpID, 42, 'E') ? "" : "disabled" }} \
              href="{{ route('stocktransfer.index') }}/' + row.Txfr_ID + '/edit?corpID={{ $corpID }}&stockStatus={{ $stockStatus }}">\
                <i class="fas fa-pencil-alt"></i>\
              </a>\
              <a class="btn btn-danger btn-md" title="Delete" onclick="deleteStock(' + row.Txfr_ID + ')" \
                {{ \Auth::user()->checkAccessByIdForCorp($corpID, 42, 'D') ? "" : "disabled" }} >\
                <i class="glyphicon glyphicon-trash"></i> \
              </a>';
          }
        }
      ],
      order: [
        [0, 'desc']
      ]
    });
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

showHidden = (isShow) => {
  if(isShow)
    $('#addNewTransfer').append('<a href="{{route('stocktransfer.create' , ['corpID' => $corpID] )}}"  class="pull-right">New Stock Transfer</a>')
  else
    $('#addNewTransfer').empty()
}

@if($tab == 'stock')
  showHidden(true)
@endif

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

@section('footer-scripts')
<script type="text/javascript">
  (function() {
    $('#list_menu').DataTable({
      "dom": '<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f><"#selectId">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      order: [[0, 'desc']],
      ajaxSource: '{{ route('stocktransfer.autoItems') }}' + location.search,
      initComplete: function() {
        $("#selectId").append('<div class="filterDiv1"><label class="filterLabel1"><strong>Filters:</strong> </label><select onChange="filterStatus()" class="form-control"><option value="1">Unserved</option><option value="2">Served </option><option value="3">All </option></select></div>');
        $("#selectId select").val('{{ $status }}');
      },
      columnDefs: [
        {
          targets: 0,
          data: "po_no"
        },
        {
          targets: 1,
          data: "po_date"
        },
        {
          targets: 2,
          data: "template"
        },
        {
          targets: 3,
          data: 'served',
          render: (data, type, row, meta) => {
            return data == 1 ? 'Served' : 'Unserved'
          }
        },
        {
          targets: 4,
          data: 'tot_pcs',
          className: 'text-center'
        },
        {
          targets: 5,
          data: 'total_amt',
          className: 'text-right'
        },
        {
          targets: 6,
          data: '',
          render: (data, type, row, meta) => {
            let resultHTML =  '<a class="btn btn-primary btn-md {{ \Auth::user()->checkAccessByIdForCorp($corpID, 43, 'E') ? "" : "disabled" }}" title="View PO Details" \
              href="{{ route('stocktransfer.index') }}/' + row.po_no + '?corpID={{$corpID}}"> \
                <span class="far fa-eye"></span> \
            </a> \
            <a class="btn btn-warning btn-md {{ \Auth::user()->checkAccessByIdForCorp($corpID, 43, 'E') ? "" : "disabled" }}" title="View original Details"  \
              href="{{ route('stocktransfer.index') }}/' + row.po_no + '/original?corpID={{$corpID}}"> \
              <span class="glyphicon glyphicon-inbox"></span> \
            </a> ';

            if(row.served == '0') {
              resultHTML += '<a class="btn btn-success btn-md {{ \Auth::user()->checkAccessByIdForCorp($corpID, 43, 'E') ? "" : "disabled" }}" title="Edit" \
              onclick="markToserved(event,' + row.po_no + ')">\
               <span class="glyphicon glyphicon-ok"></span>\
              </a>'
            }

            return resultHTML
          }
        }
      ],
    })
  })()
</script>
@endsection
