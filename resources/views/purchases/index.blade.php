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
                @if(\Auth::user()->checkAccessById(58, 'A'))
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
          @if(\Auth::user()->checkAccessById(59 , 'V'))
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
                        <th>Date</th>
                        <th>Job Order #</th>
                        <th>PR #</th>
                        <th>Description</th>
                        <th>Requester</th>
                        <th>Branch</th>
                        <th>Total Qty</th>
                        <th>Total Cost</th>
                        <th>Status</th>
                        <th>Remarks</th>
                        <th>Date Disapproved</th>
                        <th>PO #</th>
                        <th>Disapproved By</th>
                        <th>PR Date</th>
                        <th>Items Changed</th>
                        <th>Vendor</th>
                        <th>Date Approved</th>
                        <th>Approved By</th>
                        <th>EQP</th>
                        <th>PRT</th>
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
      table.columns( [0,5,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( false );
      $('.table_purchase').css('display','')
    })

    let basePurchaseAPI
    if (localStorage.getItem('filter')) {
      basePurchaseAPI = '{{ route('api.purchase_request.index') }}?corpID=' + {{ $company->corp_id }} + '&branch=' + localStorage.getItem('filter')
    } else {
      basePurchaseAPI = '{{ route('api.purchase_request.index') }}?corpID=' + {{ $company->corp_id }} + '&branch=' + $('input[name="checkAccess"]').val()
    }

    let tablePurchase = $('.table-purchases').DataTable({
      colReorder: true,
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
          data: "date",
          class: 'text-center'
        },
        {
          targets: 2,
          data: "job_order",
          class: 'text-center',
          render: (data, type, row, meta) => {
            return '<a href="javascript:void(0)" data-toggle="modal" data-target="#job_order">'+ data +'</a>'
          }
        },
        {
          targets: 3,
          data: "pr",
          class: 'text-center',
          render: (data, type, row, meta) => {
            if (row.status) {
              return '<a style="color:red" href="{{ route('purchase_request.index') }}/' + row.id + '/edit?corpID={{ request()->corpID }}">'+ data +'</a>'
            } else {
              return '<a href="{{ route('purchase_request.index') }}/' + row.id + '/edit?corpID={{ request()->corpID }}">'+ data +'</a>'
            }
          }
        },
        {
          targets: 4,
          data: "description",
          class: 'text-center'
        },
        {
          targets: 5,
          data: "requester_id",
          class: 'text-center'
        },
        {
          targets: 6,
          data: "branch",
          class: 'text-center'
        },
        {
          targets: 7,
          data: "total_qty",
          class: 'text-center'
        },
        {
          targets: 8,
          data: "total_cost",
          class: 'text-center'
        },
        {
          targets: 9,
          data: "status",
          class: 'text-center'
        },
        {
          targets: 10,
          data: "remarks",
          class: 'text-center',
        },
        {
          targets: 11,
          data: "date_disapproved",
          class: 'text-center',
        },
        {
          targets: 12,
          data: "po",
          class: 'text-center',
        },
        {
          targets: 13,
          data: "disapproved_by",
          class: 'text-center',
        },
        {
          targets: 14,
          data: "pr_date",
          class: 'text-center',
        },
        {
          targets: 15,
          data: "items_changed",
          class: 'text-center',
        },
        {
          targets: 16,
          data: "vendor",
          class: 'text-center',
        },
        {
          targets: 17,
          data: "date_approved",
          class: 'text-center',
        },
        {
          targets: 18,
          data: "approved_by",
          class: 'text-center',
        },
        {
          targets: 19,
          data: "eqp",
          class: 'text-center',
          render: (data, type, row, meta) => {
            return '<input type="checkbox" '+ ( row.eqp == 'equipment' ? " checked " : "" ) +' disabled>'
          }
        },
        {
          targets: 20,
          data: "prt",
          class: 'text-center',
          render: (data, type, row, meta) => {
            return '<input type="checkbox" '+ ( row.prt == 'parts' ? " checked " : "" ) +' disabled>'
          }
        },
        {
          targets: 21,
          class: 'text-center',
          render: (data, type, row, meta) => {
            return '<button onclick="removePurchase(' + row.id + ')" class="btn btn-md btn-danger fas fa-trash-alt"> </button>'
          }
        },
      ],
      order: [
        [0, 'desc']
      ]
  })

  checkFilter = () => {
    if (localStorage.getItem('filter')) {
      $('.branch-select').val(localStorage.getItem('filter'))
    } else {
      if ($('input[name="checkAccess"]').val() == 1) {
        $('.branch-select').val(1)
      } else if ($('input[name="checkAccess"]').val() == 2) {
        $('.branch-select').val(2)
      }
    }
  }

  $('body').on('change', '.branch-select', (event) => {
    localStorage.setItem('filter', $(event.target).val())
    if ($(event.target).val() == '1') {
      var table = $('.table_purchase').DataTable();
      table.ajax.url(basePurchaseAPI + '&branch=' + $(event.target).val() ).load()
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        // table.columns( [0,5,8,9,10,11,12,13,14,15,16,17,18,21] ).visible( false );
        table.colReorder.order( [1,3,1,4], true );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        // table.columns( [0,9,10,11,12,13,14,15,16,17,18,21] ).visible( false );
      }
    } else if ($(event.target).val() == '2') {
      var table = $('.table_purchase').DataTable();
      table.ajax.url(basePurchaseAPI + '&branch=' + $(event.target).val() ).load()
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,5,9,10,11,12,13,14,15,16,17,18] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,9,10,11,12,13,14,15,16,17,18] ).visible( false );
      }
    } else if ($(event.target).val() == '3') {
      var table = $('.table_purchase').DataTable();
      table.ajax.url(basePurchaseAPI + '&branch=' + $(event.target).val() ).load()
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,5,8,11,12,13,14,15,16,17,18,21] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,11,12,13,14,15,16,17,18,21] ).visible( false );
      }
    } else if($(event.target).val() == '4') {
      var table = $('.table_purchase').DataTable();
      table.ajax.url(basePurchaseAPI + '&branch=' + $(event.target).val() ).load()
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,1,5,8,9,14,15,16,17,18,21] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,1,9,14,15,16,17,18,21] ).visible( false );
      }
    } 
    else if($(event.target).val() == '5') {
      var table = $('.table_purchase').DataTable();
      table.ajax.url(basePurchaseAPI + '&branch=' + $(event.target).val() ).load()
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      if ($('input[name="checkAccess"]').val() == 1) {
        table.columns( [0,8,9,10,11,12,13,14,16,17,18] ).visible( false );
      } else if ($('input[name="checkAccess"]').val() == 2) {
        table.columns( [0,8,9,10,11,12,13,14,16,17,18,21] ).visible( false );
      }
    }
    else if($(event.target).val() == '6') {
      var table = $('.table_purchase').DataTable();
      table.ajax.url(basePurchaseAPI + '&branch=' + $(event.target).val() ).load()
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      table.columns( [0,1,5,9,10,11,13,14,15,21] ).visible( false );
    }
    else if($(event.target).val() == '7') {
      var table = $('.table_purchase').DataTable();
      table.ajax.url(basePurchaseAPI + '&branch=' + $(event.target).val() ).load()
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21] ).visible( true );
      table.columns( [0,5,9,10,11,13,14,15,16,17,18,21] ).visible( false );
    }
  })

  // editPart = (itemId) => {
  //   $('.edit-part-modal').remove();
  //   $.ajax({
  //     url: '{{ route('parts.index') }}/' + itemId + '/edit',
  //     type: 'GET',
  //     success: (res) => {
  //       $('body').append(res);
  //       $('.edit-part-modal').modal('show');
  //     }
  //   })
  // }

  removePurchase = (id) => {
    swal({
      title: "<div class='delete-title'>Delete</div>",
      text:  "<div class='delete-text'>You are about to delete PurchaseID ["+ id +"]</strong></div>",
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
        url: '{{ route('purchase_request.index') }}/' + id + '/?corpID={{ request()->corpID }}',
        type: 'DELETE',
        success: (res) => {
          tablePurchase.ajax.reload()
          swal({
            title: "<div class='delete-title'>Deleted </div>",
            text:  "<div class='delete-text'>PR#["+ id +"] has been cancelled and deleted.</strong></div>",
            html:  true,
            customClass: 'swal-wide',
            confirmButtonClass: 'btn-primary',
            confirmButtonText: 'Delete',
            showCancelButton: true,
            closeOnConfirm: true,
            allowEscapeKey: true
          })
        }
      })
      }
    });
  }

  // $('body').on('change', 'input[name="document-filter"]', (event) => {
  //   if (event.target.value == 'all') {
  //     $('.branch-select').prop('disabled', true);
  //     $('.filter-select').prop('disabled', true);
  //   } else {
  //     $('.branch-select').prop('disabled', false);
  //     $('.filter-select').prop('disabled', false);
  //   }
  //   localStorage.setItem('partsFilter', event.target.value)
    
  //   $('.branch-select').change()
  // })

  // $('body').on('change', '.branch-select', (event) => {
 
  //   if (event.target.value != localStorage.getItem('partsType')) {
  //     localStorage.removeItem('partsTypeId')
  //   }

  //   $.ajax({
  //     url:'{{ route('parts.getFilters') }}?type=' +  event.target.value,
  //     type: 'GET',
  //     success: (res) => {
  //       $('.filter-select option').remove()

  //       for (i = 0; i < res.items.length; i++) {
  //         let item = res.items[i]

  //         $('.filter-select').append('<option value="' + item.id + '">' + item.label + '</option>')
  //       }
  //       if (localStorage.getItem('partsTypeId')) {
  //         $('.filter-select').val(localStorage.getItem('partsTypeId'))
  //       }

  //       $('.filter-select').change();
  //     }
  //   });
  // })

  // $('body').on('change', '.filter-select', (event) => {
  //   let requestAPI = basePartAPI + '?type=' +  $('.branch-select').val() + '&id=' + $('.filter-select').val()
    
  //   localStorage.setItem('partsType', $('.branch-select').val())
      
  //   localStorage.setItem('partsTypeId', $('.filter-select').val())

  //   tablePart.ajax.url(requestAPI).load()
  // })
})()
</script>
@endsection