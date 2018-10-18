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
                @if(\Auth::user()->checkAccessById(57, 'A'))
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
    let basePartAPI = '{{ route('api.parts.index') }}'
    
    let tablePart = $('.table-parts').DataTable({
      dom: '<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f><"#customFilter">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      initComplete: ()  => {
        $("#customFilter").append('<div class="col-sm-12" style="margin: 15px 0px;"> \
          Filter: \
          <label style="font-weight: normal;"><input checked name="document-filter" value="all" type="radio" /> Show All </label> \
          <label style="font-weight: normal;padding-left: 30px;"><input name="document-filter" value="by" type="radio" /> Filter By: </label> \
          <select disabled class="form-control branch-select" style="width: 150px;"> <option value="brand">Brand</option><option value="category">Category</option><option value="vendor">Vendor</option></select> \
          <select disabled class="form-control filter-select" style="width: 150px;"> </select> \
        </div>')
        if (localStorage.getItem('partsType')) {
          $('.branch-select').val(localStorage.getItem('partsType'))
        }
        if (localStorage.getItem('partsFilter')) {
          $('input[name="document-filter"][value="' + localStorage.getItem('partsFilter') + '"]').prop('checked', true)
          $('input[name="document-filter"][value="' + localStorage.getItem('partsFilter') + '"]').change();
        }
      },
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
              return '<button onclick="editPart(' + row.item_id + ')" {{ \Auth::user()->checkAccessById(57, 'E') ? '' : 'disabled' }}\
              class="btn btn-md btn-primary fas fa-pencil-alt" data-toggle="modal" data-target=".edit-part-modal"> </button> \
              <button onclick="removePart(' + row.item_id +',\''+ row.description + '\')" {{ \Auth::user()->checkAccessById(57, 'D') ? '' : 'disabled' }}\
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

  $('body').on('change', 'input[name="document-filter"]', (event) => {
    if (event.target.value == 'all') {
      $('.branch-select').prop('disabled', true);
      $('.filter-select').prop('disabled', true);
    } else {
      $('.branch-select').prop('disabled', false);
      $('.filter-select').prop('disabled', false);
    }
    $('.branch-select').change()
  })

  $('body').on('change', '.branch-select', (event) => {

    if (event.target.value != localStorage.getItem('partsType')) {
      localStorage.removeItem('partsTypeId')
    }

    $.ajax({
      url:'{{ route('parts.getFilters') }}?type=' +  event.target.value,
      type: 'GET',
      success: (res) => {
        $('.filter-select option').remove()

        for (i = 0; i < res.items.length; i++) {
          let item = res.items[i]

          $('.filter-select').append('<option value="' + item.id + '">' + item.label + '</option>')
        }

        if (localStorage.getItem('partsTypeId')) {
          $('.filter-select').val(localStorage.getItem('partsTypeId'))
        }

        $('.filter-select').change();
      }
    });
  })

  $('body').on('change', '.filter-select', (event) => {
    let requestAPI = basePartAPI + '?type=' +  $('.branch-select').val() + '&id=' + $('.filter-select').val()
    
    localStorage.setItem('partsType', $('.branch-select').val())
    localStorage.setItem('partsTypeId', $('.filter-select').val())

    tablePart.ajax.url(requestAPI).load()
  })
})()
</script>
@endsection