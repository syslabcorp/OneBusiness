@extends('layouts.custom')

@section('content')
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="rown">
        <div class="col-md-6">
          <h4>Wage Templates</h4>
        </div>
        <div class="col-md-6 text-right">
          @if(\Auth::user()->checkAccessByIdForCorp(request()->corpID, 45, 'A'))
            <a href="{{ route('wage-templates.create', ['corpID' => request()->corpID]) }}">Add Template</a>
          @endif
        </div>
      </div>
    </div>
    <div class="panel-body">
      <table class="table table-departments table-striped table-bordered">
        <thead>
          <tr>
            <th>Department</th>
            <th>Wage ID</th>
            <th>Code</th>
            <th>Position</th>
            <th>Active</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
@endsection

@section('pageJS')
<script type="text/javascript">
  (() => {
    $('.table-departments').DataTable({
      ajaxSource: '{{ route('root') }}/api/v1/wage-templates?corpID=' + {{ request()->corpID }},
      columnDefs: [
        {
          name: 'department',
          targets: 0,
          data: "department",
          className: 'text-center'
        },
        {
          targets: 1,
          data: "wage_tmpl8_id"
        },
        {
          targets: 2,
          data: "code"
        },
        {
          targets: 3,
          data: "position"
        },
        {
          targets: 4,
          data: 'active',
          className: 'text-center',
          render: (data, type, row, meta) => {
            return '<input type="checkbox" onclick="return false;" ' + (data == 1 ? 'checked' : '') + '/>'
          }
        },
        {
          targets: 5,
          data: "total"
        },
        {
          targets: 6,
          data: '',
          className: 'text-center',
          render: (data, type, row, meta) => {
            return '<a class="btn btn-primary btn-md edit" title="Edit" \
                {{ \Auth::user()->checkAccessByIdForCorp(request()->corpID, 45, 'E') ? '' : 'disabled' }} \
                href="{{ route('wage-templates.index')}}/' + row.wage_tmpl8_id + '/edit?corpID={{ request()->corpID }}">\
                <i class="fas fa-pencil-alt"></i>\
              </a>\
              <a class="btn btn-danger btn-md" title="Delete" onclick="deleteTemplate(event,' + row.wage_tmpl8_id + ', \'' + row.code + '\')" \
                {{ \Auth::user()->checkAccessByIdForCorp(request()->corpID, 45, 'D') ? '' : 'disabled' }}> \
                <i class="fas fa-trash-alt"></i> \
              </a>';
          }
        }
      ],
      order: [
        [0, 'asc']
      ],
      rowsGroup: [
        'department:name'
      ]
    })

    deleteTemplate = (event, id, name) => {
      swal({
        title: "<div class='delete-title'>Confirm Delete</div>",
        text:  "<div class='delete-text'>You are about to delete template " + id + " - " + name + " <br>Are you sure?</strong></div>",
        html:  true,
        customClass: 'swal-wide',
        showCancelButton: true,
        confirmButtonClass: 'btn-danger',
        confirmButtonText: 'DELETE',
        closeOnConfirm: false,
        closeOnCancel: true
      },
      (isConfirm) => {
        $.ajax({
          url: '{{ route('wage-templates.index') }}/' + id + '?corpID={{ request()->corpID }}',
          type: 'DELETE',
          success: (res) => {
            location.reload();
          }
        })
      })
    }
  })()
</script>
@endsection