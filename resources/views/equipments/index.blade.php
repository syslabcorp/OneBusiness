@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="row">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="row">
              <div class="col-xs-9">
                <h5><strong>Equipment Inventory</strong></h5>
              </div>
              <div class="col-xs-3 text-right" style="margin-top: 10px;">
                @if(\Auth::user()->checkAccessById(56, 'A'))
                  <a class="addEquipment" href="{{ route('equipments.create', ['corpID' => $company->corp_id]) }}">Add Item</a>
                @endif
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

        @foreach($companies as $item)
          $('.company-select').append('<option value="{{ $item->corp_id }}">{{ $item->corp_name }}</option>')
        @endforeach

        reloadBranchesAndDepts()
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
            return '<button {{ \Auth::user()->checkAccessById(56, 'D') ? '' : 'disabled' }} onclick="removeEquipment(' + row.asset_id +',\'' + row.description + '\')" class="btn btn-md btn-danger fas fa-trash-alt"> </button>'
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
      swal({
        title: "<div class='delete-title'>Confirm Delete <a onclick='swal.close()' class='close'><i class='fas fa-times'></i></a></div>",
        text:  "<div class='delete-text'>You are about to delete <strong>Equipment</strong> #" + id + "- <strong>" + description + "</strong> \
          and its parts and other details. Would you like to set it as <strong>Inactive</strong> instead?</div>",
        html:  true,
        customClass: 'swal-wide',
        confirmButtonClass: 'btn-danger',
        confirmButtonText: 'Delete anyway',
        cancelButtonText: 'Set to Inactive',
        showCancelButton: true,
        closeOnConfirm: false,
        closeOnCancel: false,
        allowEscapeKey: false,
      }, function(isConfirm) {
        $('.alert-nothing').remove()
        $.ajax({
          url: '{{ route('api.equipments.index') }}/' + id + '?delete=' + (isConfirm ? 1 : 0),
          type: 'DELETE',
          success: (res) => {
            if (res.success) {
              tableEquipment.ajax.reload()
              if (res.message) {
                $('#page-content-togle-sidebar-sec').prepend('\
                <div class="row alert-nothing">\
                  <div class="alert alert-success col-md-8 col-md-offset-2" style="border-radius: 3px;">\
                    <span class="fa fa-close"></span> <em>' + res.message + '</em>\
                  </div>\
                </div>\
                ');
                setTimeout(function() {
                  $('.alert-nothing').slideUp()
                }, 3000);
              }
            } else {
              $('#page-content-togle-sidebar-sec').prepend('\
              <div class="row alert-nothing">\
                <div class="alert alert-danger col-md-8 col-md-offset-2" style="border-radius: 3px;">\
                  <span class="fa fa-close"></span> <em>' + res.message + '</em>\
                </div>\
              </div>\
              ');
              setTimeout(function() {
                $('.alert-nothing').slideUp()
              }, 3000);
            }
            
            swal.close()
          },
          error: (res) => {
            swal.close()
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

    $('body').on('change', '.company-select', (event) => {
      reloadBranchesAndDepts()
      baseEquipmentAPI = baseEquipmentAPI.replace(/corpID=[0-9]+/, '') + 'corpID=' + $('.company-select').val()
      let createLink = $('.addEquipment').attr('href')
      $('.addEquipment').attr('href', createLink.replace(/corpID=[0-9]+/, '') + 'corpID=' + $('.company-select').val())
    })

    reloadBranchesAndDepts = () => {
      $('.department-select option').remove()
      $('.branch-select option').remove()

      $.ajax({
        url: '{{ url('/') }}/api/v1/branches/depts?corpID=' + $('.company-select').val(),
        type: 'GET',
        success: (res) => {
          for(let i = 0; i < res.depts.length; i++) {
            $('.department-select').append('<option value="' + res.depts[i].dept_ID + '">' + res.depts[i].department + '</option>')
          }

          for(let i = 0; i < res.branches.length; i++) {
            $('.branch-select').append('<option value="' + res.branches[i].Branch + '">' + res.branches[i].ShortName + '</option>')
          }
        }
      })
    }

    reloadEquipmentTable = (url) => {
      tableEquipment.ajax.url(url).load()
    }
  })()
</script>
@endsection