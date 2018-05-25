@extends('layouts.custom')

@section('content')
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="rown">
        <div class="col-md-6">
          <h4>Departments</h4>
        </div>
        <div class="col-md-6 text-right">
          <a href="#">Add Departments</a>
        </div>
      </div>
    </div>
    <div class="panel-body">
      <table class="table table-departments table-striped table-bordered">
        <thead>
          <tr>
            <th>Depts.ID</th>
            <th>Department</th>
            <th>Main</th>
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
              href="{{ route('stocktransfer.index') }}/' + row.dept_ID + '/edit">\
                <i class="glyphicon glyphicon-pencil"></i>\
              </a>\
              <a class="btn btn-danger btn-sm" title="Delete" onclick="deleteStock(' + row.dept_ID + ')"> \
                <i class="glyphicon glyphicon-trash"></i> \
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