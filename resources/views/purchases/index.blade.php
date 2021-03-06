@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h5><strong>Purchase Request</strong></h5>
              </div>
              <div class="col-xs-3 text-right" style="margin-top: 10px;">
                @if(\Auth::user()->checkAccessByIdForCorp(request()->corpID, 58, 'A'))
                  <a class="" href="{{ route('purchase_request.create', ['corpID' => $company->corp_id]) }}">New Request</a>
                @endif              
              </div>
            </div>
          </div>
          <div class="modal fade" id="job_order" role="dialog">
            <div class="modal-dialog" style="font-style: normal;font-family:  Times;">
            
              <!-- Modal content-->
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal">&times;</button>
                  <h4 class="modal-title"><label for="">Job Order #000017</label></h4>
                </div>
                <div class="modal-body" style="padding-">
                  <div class="rown">
                    <div class="col-md-4 text-right">
                      <label for=""><strong>Subject:</strong></label>
                    </div>
                    <p class="col-md-8">Some text in the modal.</p>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
              </div>
            </div>
          </div>
          @if(\Auth::user()->checkAccessByIdForCorp(request()->corpID, 58, 'V'))
          <input type="hidden" name="checkAccess" value="1">
          @elseif(\Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, 'V'))
          <input type="hidden" name="checkAccess" value="2">
          @endif
          <div class="panel-body">
            <div class="bs-example">
              <div class="tablescroll">
                <div class="table-responsive">
                  <table class="table_purchase stripe table table-bordered nowrap table-purchases" width="100%" style="display:none">
                    <thead>
                      <tr>
                        <th>Id</th>
                        <th>Date Approved</th>
                        <th>Date Disapproved</th>
                        <th>Date</th>
                        <th>Job Order #</th>
                        <th>PR #</th>
                        <th>PO #</th>
                        <th>Requester</th>
                        <th>Description</th>
                        <th>Branch</th>
                        <th>Vendor</th>
                        <th>Total Qty</th>
                        <th>Total Cost</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Disapproved By</th>
                        <th>EQP</th>
                        <th>PRT</th>
                        <th>Approved By</th>
                        <th>Items Changed</th>
                        <th>PR Date</th>
                        <th>Action</th>
                      </tr>
                    </thead>
                    <tbody>
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
  
@endsection

@section('pageJS')
<script>
  (() => {
    $(document).ready(function() {
      var table = $('.table_purchase').DataTable();
      table.columns( [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,2,6,7,10,13,14,15,18,19,20,21] ).visible( false );// forPO requester
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,2,6,10,13,14,15,18,19,20] ).visible( false );
      }
      $('.table_purchase').css('display','')
    })
    let indexLink = "{{ route('purchase_request.index', ['corpID' => request()->corpID]) }}"

    let basePurchaseAPI
    if (localStorage.getItem('filter')) {
      basePurchaseAPI = '{{ route('api.purchase_request.index') }}?corpID=' + {{ $company->corp_id }} + '&branch=' + localStorage.getItem('filter')
    } else {
      basePurchaseAPI = '{{ route('api.purchase_request.index') }}?corpID=' + {{ $company->corp_id }} + '&branch=' + $('input[name="checkAccess"]').val()
    }

    let tablePurchase = $('.table-purchases').DataTable({
      dom: '<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f><"#customFilter">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      initComplete: ()  => {
        $("#customFilter").append('<div class="col-sm-9 " style="margin: 0px 0px; padding: 0px"> \
          Filter: \
          <label>\
          <select class="form-control branch-select" style="width: 150px;"> \
          <option value="1">For PO</option>\
          <option value="2">Requests</option>\
          <option value="3">All</option>\
          <option value="4">Disapproved</option>\
          <option value="5">For Verification</option> \
          <option value="6">Approved </option>\
          <option value="7">Served </option></select> \
        </div>')
        checkFilter()
      },
      ajax: basePurchaseAPI,
      columns: [
        {
          targets: 0,
          data: "id",
          class: 'text-center'
        },
        {
          targets: 1,
          data: "date_approved",
          class: 'text-center',
        },
        {
          targets: 2,
          data: "date_disapproved",
          class: 'text-center',
        },
        {
          targets: 3,
          data: "date",
          class: 'text-center'
        },
        {
          targets: 4,
          data: "job_order",
          class: 'text-center',
          render: (data, type, row, meta) => {
            return '<a href="javascript:void(0)" data-toggle="modal" data-target="#job_order">'+ data +'</a>'
          }
        },
        {
          targets: 5,
          data: "pr",
          class: 'text-center',
          render: (data, type, row, meta) => {
            if (row.status) {
              return '<a style="color:red" onClick="checkAccessID(event, '+ row.id +');" href="javascript:void(0)">'+ data +'</a>'
            } else {
              return '<a onClick="checkAccessID(event, '+ row.id +');" href="javascript:void(0)">'+ data +'</a>'
            }
          }
        },
        {
          targets: 6,
          data: "po",
          class: 'text-center',
        },
        {
          targets: 7,
          data: "requester_id",
          class: 'text-center'
        },
        {
          targets: 8,
          data: "description",
          class: 'text-center'
        },
        {
          targets: 9,
          data: "branch",
          class: 'text-center'
        },
        {
          targets: 10,
          data: "vendor",
          class: 'text-center',
        },
        {
          targets: 11,
          data: "total_qty",
          class: 'text-center'
        },
        {
          targets: 12,
          data: "total_cost",
          class: 'text-center'
        },
        {
          targets: 13,
          class: 'text-center',
          render: (data, type, row, meta) => {
            if (row.flag == 1) {
              return 'For PO'
            } else if (row.flag == 2) {
              return 'Requests'
            } else if (row.flag == 4) {
              return 'Disapproved'
            } else if (row.flag== 5) {
              return 'For Verification'
            } else if (row.flag == 6) {
              return 'Approved'
            } else if (row.flag == 7) {
              return 'Served'
            }
          }
        },
        {
          targets: 14,
          data: "remarks",
          class: 'text-center',
        },
        {
          targets: 15,
          data: "disapproved_by",
          class: 'text-center',
        },
        {
          targets: 16,
          data: "eqp",
          class: 'text-center',
          render: (data, type, row, meta) => {
            return '<input type="checkbox" '+ ( row.eqp == 'Equipment' ? " checked " : "" ) +' disabled>'
          }
        },
        {
          targets: 17,
          data: "prt",
          class: 'text-center',
          render: (data, type, row, meta) => {
            return '<input type="checkbox" '+ ( row.prt == 'Part' ? " checked " : "" ) +' disabled>'
          }
        },
        {
          targets: 18,
          data: "approved_by",
          class: 'text-center',
        },
        {
          targets: 19,
          data: "items_changed",
          class: 'text-center',
        },
        {
          targets: 20,
          data: "pr_date",
          class: 'text-center',
        },
        {
          targets: 21,
          class: 'text-center',
          render: (data, type, row, meta) => {
            @if(\Auth::user()->checkAccessByIdForCorp(request()->corpID, 58, "D") || \Auth::user()->checkAccessByIdForCorp(request()->corpID, 59, "D"))
              return '<button onClick="removePurchase('+ row.id +')" class="btn btn-md btn-danger"><i class="fas fa-trash-alt"></i></button>'
            @else
              return '<button disabled class="btn btn-md btn-danger"><i class="fas fa-trash-alt"></i></button>'
            @endif
          }
        },
      ],
      order: [
        [0, 'desc']
      ]
  })

  checkAccessID = (event, id) => {
    $.ajax({
      url: '{{ route('purchase_request.checkAccessID') }}?corpID={{ request()->corpID }}&id='+id,
      type: 'GET',
      success: (res) => {
        if (res.success) {
          $(event.target).attr('href','')
          location.href = "{{ route('purchase_request.index') }}/" + id + "/edit?corpID={{ request()->corpID }}"
        } else {
          showAlertMessage('This request is currently being evaluated. Please refresh and try again later.', 'PR Unavailable')
        }
      }
    });
  }

  checkFilter = () => {
    if (localStorage.getItem('filter')) {
      $('.branch-select').val(localStorage.getItem('filter'))
      showFilter(localStorage.getItem('filter'))
    } else {
      if ($('input[name="checkAccess"]').val() == 1) {
        $('.branch-select').val(1)
      } else if ($('input[name="checkAccess"]').val() == 2) {
        $('.branch-select').val(2)
      }
    }
  }

  showFilter = (filter) => {
    var table = $('.table_purchase').DataTable();
    table.ajax.url(basePurchaseAPI + '&branch=' + filter ).load()
    
    if (filter == '1') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,2,6,7,10,12,13,14,15,18,19,20,21] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,2,6,10,13,14,15,18,19,20,21] ).visible( false );
      }
    } else if (filter == '2') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,2,6,7,10,12,13,14,15,18,19,20] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,2,6,10,13,14,15,18,19,20] ).visible( false );
      }
    } else if (filter == '3') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,2,6,7,10,12,15,18,19,20,21] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,2,6,10,15,18,19,20,21] ).visible( false );
      }
    } else if(filter == '4') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,3,7,10,12,13,18,19,20,21] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,3,10,13,18,19,20,21] ).visible( false );
      }
    } 
    else if(filter == '5') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,2,6,10,12,13,14,15,18,20] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,2,6,10,12,13,14,15,18,20,21] ).visible( false );
      }
    }
    else if(filter == '6') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      table.columns( [0,2,3,7,13,14,15,19,20,21] ).visible( false );
    }
    else if(filter == '7') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      table.columns( [0,1,2,7,10,13,14,15,18,19,20,21] ).visible( false );
    }
  }

  $('body').on('change', '.branch-select', (event) => {
    localStorage.setItem('filter', $(event.target).val())
    var table = $('.table_purchase').DataTable();
    table.ajax.url(basePurchaseAPI + '&branch=' + $(event.target).val() ).load()

    if ($(event.target).val() == '1') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,2,6,7,10,12,13,14,15,18,19,20,21] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,2,6,10,13,14,15,18,19,20,21] ).visible( false );
      }
    } else if ($(event.target).val() == '2') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,2,6,7,10,12,13,14,15,18,19,20] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,2,6,10,13,14,15,18,19,20] ).visible( false );
      }
    } else if ($(event.target).val() == '3') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,2,6,7,10,12,15,18,19,20,21] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,2,6,10,15,18,19,20,21] ).visible( false );
      }
    } else if($(event.target).val() == '4') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,3,7,10,12,13,18,19,20,21] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,3,10,13,18,19,20,21] ).visible( false );
      }
    } 
    else if($(event.target).val() == '5') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,2,6,10,12,13,14,15,18,20] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,2,6,10,12,13,14,15,18,20,21] ).visible( false );
      }
    }
    else if($(event.target).val() == '6') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      table.columns( [0,2,3,7,13,14,15,19,20,21] ).visible( false );
    }
    else if($(event.target).val() == '7') {
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      table.columns( [0,1,2,7,10,13,14,15,18,19,20,21] ).visible( false );
    }
  })

  removePurchase = (id) => {
    swal({
      title: "<div class='delete-title'>Delete</div>",
      text:  "<div class='delete-text'>You are about to delete PR# ["+ id +"]. Continue?</strong></div>",
      html:  true,
      customClass: 'swal-wide',
      confirmButtonClass: 'btn-danger',
      confirmButtonText: 'Delete',
      showCancelButton: true,
      closeOnConfirm: true,
      allowEscapeKey: true
    }, (data) => {
      if(data) {
        $.ajax({
          url: '{{ route('api.purchase_request.index') }}/' + id + '?corpID={{ request()->corpID }}',
          type: 'POST',
          data: {
            '_method': 'DELETE'
          },
          success: (res) => {
            window.location.reload()
          }
      })
      }
    });
  }
})()
</script>
@endsection