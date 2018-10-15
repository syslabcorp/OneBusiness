@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h5><strong>Equipment Parts</strong></h5>
              </div>
              <div class="col-xs-3 text-right" style="margin-top: 10px;">
                @if(\Auth::user()->checkAccessById(56, 'A'))
                  <a class="addPart" data-toggle="modal" data-target=".create-part-modal">Add Part</a>
                @endif
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="bs-example">
              <div class="tablescroll">
                <div class="table-responsive">
                  <table class="stripe table table-bordered nowrap table-parts" width="100%">
                    <thead>
                      <tr>
                        <th>Part ID</th>
                        <th>Part Name</th>
                        <th>Brand</th>
                        <th>Category</th>
                        <th>Vendor</th>
                        <th>Consumable</th>
                        <th>With Serial#</th>
                        <th>Active</th>
                        <th>Action</th>
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
  @include('parts.modal-create')
  
@endsection

@section('pageJS')
<script>
  (() => {
    let basePartAPI = '{{ route('api.parts.index') }}?corpID=' + ( localStorage.getItem('partCompany') )
    
    let tablePart = $('.table-parts').DataTable({
      ajax: basePartAPI,
      columns: [
          {
          targets: 0,
          data: "item_id",
          class: 'text-center'
          },
          {
          targets: 1,
          data: 'description',
          class: 'text-center'
          },
          {
          targets: 2,
          data: "brand_name",
          class: 'text-center'
          },
          {
          targets: 3,
          data: "cat_name",
          class: 'text-center'
          },
          {
          targets: 4,
          data: "supplier__name",
          class: 'text-center'
          },
          {
          targets: 5,
          data: 'consumable',
          class: 'text-center',
          render: (data, type, row, meta) => {
              return '<input type="checkbox" onclick="return false;" ' + (data == 1 ? 'checked' : '') + '/>'
          }
          },
          {
          targets: 6,
          data: 'with_serialno',
          class: 'text-center',
          render: (data, type, row, meta) => {
              return '<input type="checkbox" onclick="return false;" ' + (data == 1 ? 'checked' : '') + '/>'
          }
          },
          {
          targets: 7,
          data: 'isActive',
          class: 'text-center',
          render: (data, type, row, meta) => {
              return '<input type="checkbox" onclick="return false;" ' + (data == 1 ? 'checked' : '') + '/>'
          }
          },
          {
          targets: 8,
          class: 'text-center',
          render: (data, type, row, meta) => {
              return '<button onclick="editPart(' + row.item_id + ')" \
              class="btn btn-md btn-primary fas fa-pencil-alt" data-toggle="modal" data-target=".edit-part-modal"> </button> \
              <button onclick="removePart(' + row.item_id +',\''+ row.description + '\')" \
              class="btn btn-md btn-danger fas fa-trash-alt" data-toggle="modal" data-target=".edit-part-modal"> </button>'
          }
          }
      ],
      createdRow: (row, data, dataIndex) => {
          var $dateCell = $(row).find('td:eq(1)')
          $dateCell.attr('data-order', data.start_date_order)
                  .text(data.StartDate)
      },
     
      order: [
          [0, 'desc']
      ],
  })

  editPart = (itemId) => {
    $('.edit-part-modal').remove();
    $.ajax({
      url: '{{ route('parts.index') }}/' + itemId + '/edit',
      type: 'GET',
      success: (res) => {
        $('body').append(res);
        $('.edit-part-modal').modal('show');
      }
    })
  }

  removePart = (itemId, description) => {
    swal({
    title: "<div class='delete-title'>Delete</div>",
    text:  "<div class='delete-text'>You are about to delete PartID ["+ itemId +"] - ["+ description +"]</strong></div>",
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
      url: '{{ route('parts.index') }}/' + itemId,
      type: 'DELETE',
      success: (res) => {
        tablePart.ajax.reload()
      }
    })
    }
  });
  }
})()
</script>
@endsection