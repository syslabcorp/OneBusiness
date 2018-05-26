@extends('layouts.custom')

@section('content')
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="rown">
        <div class="col-md-6">
          <h4>Wage Templates</h4>
        </div>
        <div class="col-md-6 text-right">
          <a onclick="addDepartment()" href="#">Add Template</a>
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
      ajaxSource: '{{ route('root') }}/api/v1/departments?corpID=' + {{ request()->corpID }},
      columnDefs: [
        {
          targets: 0,
          data: "dept_ID",
          className: 'text-center'
        },
        {
          targets: 1,
          data: "department"
        },
        {
          targets: 2,
          data: 'main',
          className: 'text-center',
          render: (data, type, row, meta) => {
            return '<input type="checkbox" onclick="return false;" ' + (data == 1 ? 'checked' : '') + '/>'
          }
        },
        {
          targets: 3,
          data: '',
          className: 'text-center',
          render: (data, type, row, meta) => {
            return '<a class="btn btn-primary btn-sm edit" title="Edit" \
                onclick="editDepartment(event,' + row.dept_ID + ')">\
                <i class="fas fa-pencil-alt"></i>\
              </a>\
              <a class="btn btn-danger btn-sm" title="Delete" onclick="deleteDepartment(event,' + row.dept_ID + ', \'' + row.department + '\')"> \
                <i class="fas fa-trash-alt"></i> \
              </a>';
          }
        }
      ],
      order: [
        [0, 'desc']
      ]
    })
  })()
</script>
@endsection