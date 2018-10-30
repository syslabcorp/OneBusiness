@extends('layouts.custom')

@section('content')
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="rown">
        <div class="col-md-6">
          <h4>Departments</h4>
        </div>
        <div class="col-md-6 text-right">
          @if(\Auth::user()->checkAccessByIdForCorp($corpID, 44, 'A'))
          <a onclick="addDepartment()" href="#">Add Departments</a>
          @endif
        </div>
      </div>
    </div>
    <div class="panel-body">
      <div class="table-responsive">
        <table class="table table-departments table-striped table-bordered">
          <thead>
            <tr>
              <th>Dept_ID</th>
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
  </div>
  @include('departments.edit-modal')
  @include('departments.add-modal')
@endsection

@section('pageJS')
<script type="text/javascript">
  (() => {
    $('.table-departments').DataTable({
      ajaxSource: '{{ route('root') }}/api/v1/departments?corpID=' + {{ $corpID }},
      dom: '<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f><"#listCorps">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      initComplete: () => {
        $('#listCorps').append('<div class="rown"><div class="col-xs-12" style="margin: 10px 0px;"> \
          <label>Filters:</label>\
          <select name="corpID" class="form-control changePageCompany">\
            @foreach($companies as $corp)\
            <option value="{{ $corp->corp_id }}"\
              {{ $corp->corp_id == $corpID ? 'selected' : '' }}>{{ $corp->corp_name }}</option>\
            @endforeach \
          </select></div></div>')
      },
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
            return '<a class="btn btn-primary btn-md edit" title="Edit" \
                {{ \Auth::user()->checkAccessByIdForCorp($corpID, 44, 'E') ? '' : 'disabled' }}\
                onclick="editDepartment(event,' + row.dept_ID + ')">\
                <i class="fas fa-pencil-alt"></i>\
              </a>\
              <a class="btn btn-danger btn-md" title="Delete" onclick="deleteDepartment(event,' + row.dept_ID + ', \'' + row.department + '\')" \
                {{ \Auth::user()->checkAccessByIdForCorp($corpID, 44, 'D') ? '' : 'disabled' }}> \
                <i class="fas fa-trash-alt"></i> \
              </a>';
          }
        }
      ],
      order: [
        [0, 'desc']
      ]
    })

    addDepartment = () => {
      $('.modalAddDepartment').modal('show')
    }

    editDepartment = (event, deptID) => {
      let departmentName = $(event.target).closest('tr').find('td:eq(1)').text()
      let mainChecked = $(event.target).closest('tr').find('input[type="checkbox"]').is(':checked')

      $('.modalEditDepartment').find('.departmentName').text(departmentName)
      $('.modalEditDepartment').find('input[name="department"]').val(departmentName)
      $('.modalEditDepartment').find('input[type="checkbox"]').prop('checked', mainChecked)
      $('.modalEditDepartment').find('form').attr('action', '{{ route('departments.index')}}/' + deptID)

      $('.modalEditDepartment').modal('show')
    }

    $('.modal .btn-save').click(function(event) {
      let modalElement = $(this).closest('.modal')
      let parentElement = modalElement.find('input[name="department"]').closest('div')

      if(modalElement.find('input[name="department"]').val().trim() == "") {
        parentElement.find('.error').remove()
        parentElement.append(
          '<span class="error">Input can\'t be blank</span>'
        )

        event.preventDefault()
      }
    })

    deleteDepartment = (event, deptID, deptName) => {
      swal({
        title: "<div class='delete-title'>Confirm Delete</div>",
        text:  "<div class='delete-text'>You are about to delete department " + deptID + " - " + deptName + " <br>Are you sure?</strong></div>",
        html:  true,
        customClass: 'swal-wide',
        showCancelButton: true,
        confirmButtonClass: 'btn-danger',
        confirmButtonText: 'Delete',
        closeOnConfirm: false,
        closeOnCancel: true
      },
      (isConfirm) => {
        $.ajax({
          url: '{{ route('departments.index') }}/' + deptID + '?corpID={{ $corpID }}',
          type: 'DELETE',
          success: (res) => {
            location.reload()
          }
        })
      })
    }
  })()
</script>
@endsection