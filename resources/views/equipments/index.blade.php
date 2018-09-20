@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h5>Equipment Inventory</h5>
              </div>
              <div class="col-xs-3 text-right" style="margin-top: 10px;">
                <a href="{{ route('equipments.create', ['corpID' => $company->corp_id]) }}">Add Item</a>
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="bs-example">
              <div class="tablescroll">
                <div class="table-responsive">
                  <table class="stripe table table-bordered nowrap table-equipments" width="100%">
                    <thead>
                      <tr>
                        <th>Asset No.</th>
                        <th>Equipment</th>
                        <th>Type</th>
                        <th>Branch</th>
                        <th>Department</th>
                        <th>Status</th>
                        <th>Qty</th>
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
@endsection

@section('pageJS')
<script>
  (() => {
    let baseEquipmentAPI = '{{ route('api.equipments.index', ['corpID' => $company->corp_id]) }}'

    let tableEquipment = $('.table-equipments').DataTable({
      dom: '<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f><"#customFilter">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      initComplete: ()  => {
        $("#customFilter").append('<div class="col-sm-12" style="margin: 15px 0px;"> \
          Company: \
          <select class="form-control company-select" style="width: 200px;margin-right: 20px;"></select> \
          Filter: \
          <label style="font-weight: normal;"><input checked name="document-filter" value="all" type="radio" /> Show All </label> \
          <label style="font-weight: normal;padding-left: 30px;"><input name="document-filter" value="branch" type="radio" /> Branch </label> \
          <select disabled class="form-control branch-select" style="width: 150px;"> </select> \
          <label style="font-weight: normal;padding-left: 30px;"><input name="document-filter" value="department" type="radio" /> Department </label> \
          <select disabled class="form-control department-select" style="width: 150px;"> </select> \
        </div>')

        @foreach($deptItems as $item)
          $('.department-select').append('<option value="{{ $item->dept_ID }}">{{ $item->department }}</option>')
        @endforeach

        @foreach($branches as $item)
          $('.branch-select').append('<option value="{{ $item->Branch }}">{{ $item->ShortName }}</option>')
        @endforeach

        @foreach($companies as $item)
          $('.company-select').append('<option value="{{ $item->corp_id }}">{{ $item->corp_name }}</option>')
        @endforeach
      },
      ajax: baseEquipmentAPI,
      columns: [
        {
          targets: 0,
          data: "asset_id",
          class: 'text-center'
        },
        {
          targets: 1,
          data: 'description',
          render: (data, type, row, meta) => {
            return '<a href="{{ route('equipments.index') }}/' + row.asset_id + '?corpID={{ $company->corp_id }}">' + row.description + '</a>'
          }
        },
        {
          targets: 2,
          data: "type"
        },
        {
          targets: 3,
          data: "branch"
        },
        {
          targets: 4,
          data: "department"
        },
        {
          targets: 5,
          data: 'status',
          class: 'text-center'
        },
        {
          targets: 6,
          data: 'qty',
          class: 'text-center'
        },
        {
          targets: 7,
          class: 'text-center',
          render: (data, type, row, meta) => {
            return '<button onclick="removeEquipment(' + row.asset_id +',\'' + row.description + '\')" class="btn btn-md btn-danger fas fa-trash-alt"> </button>'
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

    removeEquipment = (id, description) => {
      showConfirmMessage('Are you sure you want to delete equipment #' + id + ' ' + description + '?', 'Confirm', () => {
        $.ajax({
          url: '{{ route('api.equipments.index') }}/' + id,
          type: 'DELETE',
          success: (res) => {
            tableEquipment.ajax.reload()
          },
          error: (res) => {

          }
        })
      })
    }

    $('body').on('change', 'input[name="document-filter"]', (event) => {
      switch(event.target.value) {
        case 'branch':
          $('.branch-select').prop('disabled', false)
          $('.department-select').prop('disabled', true)
          reloadEquipmentTable(baseEquipmentAPI + '&branch=' + $('.branch-select').val())
          break;
        case 'department':
          $('.branch-select').prop('disabled', true)
          $('.department-select').prop('disabled', false)
          reloadEquipmentTable(baseEquipmentAPI + '&department=' + $('.department-select').val())
          break;
        default:
          $('.branch-select, .department-select').prop('disabled', true)
          reloadEquipmentTable(baseEquipmentAPI)
          break;
      }
    })

    $('body').on('change', '.branch-select', (event) => {
      reloadEquipmentTable(baseEquipmentAPI + '&branch=' + $('.branch-select').val())
    })

    $('body').on('change', '.department-select', (event) => {
      reloadEquipmentTable(baseEquipmentAPI + '&department=' + $('.department-select').val())
    })

    reloadEquipmentTable = (url) => {
      tableEquipment.ajax.url(url).load()
    }
  })()
</script>
@endsection