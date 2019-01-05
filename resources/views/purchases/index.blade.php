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
                  <a class="addEquipment" href="{{ route('purchases.create', ['corpID' => $company->corp_id]) }}">New Request</a>
                @endif              
              </div>
            </div>
          </div>
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
                        <th>PO</th>
                        <th>Disapproved By</th>
                        <th>PR Date</th>
                        <th>Items Changed</th>
                        <th>Vendor</th>
                        <th>Date Approved</th>
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
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18] ).visible( true );
      table.columns( [5,8,9,10,11,12,13,14,15,16,17,18] ).visible( false );
      $('.table_purchase').css('display','')
    })

    let basePurchaseAPI = '{{ route('api.purchases.index') }}'
    
    let tablePurchase = $('.table-purchases').DataTable({
      dom: '<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f><"#customFilter">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      initComplete: ()  => {
        $("#customFilter").append('<div class="col-sm-9 " style="margin: 0px 0px; padding: 0px"> \
          Filter: \
          <label>\
          <select class="form-control branch-select" style="width: 150px;"> \
          <option value="forpo">For PO</option>\
          <option value="requests">Requests</option>\
          <option value="all">All</option>\
          <option value="disapproved">Disapproved</option>\
          <option value="verify_request">Verify </option></select> \
        </div>')
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
          class: 'text-center'
        },
        {
          targets: 3,
          data: "pr",
          class: 'text-center',
        },
        {
          targets: 4,
          data: "description",
          class: 'text-center'
        },
        {
          targets: 5,
          data: "requester",
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
          class: 'text-center',
          render: (data, type, row, meta) => {
            return '<button {{ \Auth::user()->checkAccessById(56, 'D') ? '' : '' }} class="btn btn-md btn-danger fas fa-trash-alt"> </button>'
          }
        },
      ],
      order: [
        [0, 'desc']
      ]
  })

  $('body').on('change', '.branch-select', (event) => {
    if ($(event.target).val() == 'forpo') {
      var table = $('.table_purchase').DataTable();
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18] ).visible( true );
      table.columns( [5,8,9,10,11,12,13,14,15,16,17,18] ).visible( false );
    } else if ($(event.target).val() == 'requests') {
      var table = $('.table_purchase').DataTable();
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18] ).visible( true );
      table.columns( [9,10,11,12,13,14,15,16,17] ).visible( false );
    } else if($(event.target).val() == 'all') {
      var table = $('.table_purchase').DataTable();
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18] ).visible( true );
      table.columns( [8,11,12,13,14,15,16,17,18] ).visible( false );
    } else if($(event.target).val() == 'disapproved') {
      var table = $('.table_purchase').DataTable();
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18] ).visible( true );
      table.columns( [1,5,9,11,14,15,16,17] ).visible( false );
    } 
    else if($(event.target).val() == 'verify_request') {
      var table = $('.table_purchase').DataTable();
      table.columns( [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18] ).visible( true );
      table.columns( [1,8,9,10,11,12,13,16,17] ).visible( false );
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

  // removePart = (itemId, description) => {
  //   swal({
  //     title: "<div class='delete-title'>Delete</div>",
  //     text:  "<div class='delete-text'>You are about to delete PartID ["+ itemId +"] - ["+ description +"]</strong></div>",
  //     html:  true,
  //     customClass: 'swal-wide',
  //     confirmButtonClass: 'btn-danger',
  //     confirmButtonText: 'Delete',
  //     showCancelButton: true,
  //     closeOnConfirm: true,
  //     allowEscapeKey: true
  //   }, (data) => {
  //     if(data) {
  //       $.ajax({
  //       url: '{{ route('parts.index') }}/' + itemId,
  //       type: 'DELETE',
  //       success: (res) => {
  //         tablePart.ajax.reload()
  //       }
  //     })
  //     }
  //   });
  // }

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