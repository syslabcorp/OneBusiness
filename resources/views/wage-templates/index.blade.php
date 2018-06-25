@extends('layouts.custom')

@section('content')
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="rown">
        <div class="col-md-6">
          <h4>
            Wage Templates 
            <small>
              ({{ $company->category() ? $company->category()->description : 'No Category' }} - 
              {{ $company->subcategory() ? $company->subcategory()->description : 'No Subcategory' }}) 
              @if(\Auth::user()->checkAccessByIdForCorp($corpID, 45, 'E'))
                <a href="#" onclick="openWageDocument()">Change</a>
              @endif
            </small>
          </h4>
        </div>
        <div class="col-md-6 text-right">
          @if(\Auth::user()->checkAccessByIdForCorp($corpID, 45, 'A'))
            <a href="{{ route('wage-templates.create', ['corpID' => $corpID]) }}">Add Template</a>
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
            <th>Entry Level</th>
            <th>Total</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
  @include('wage-templates.modal-wage-document')
@endsection

@section('pageJS')
<script type="text/javascript">
  (() => {
    openWageDocument = () => {
      $('.modalWageDocument').modal('show')
    }

    updateSubCategoryDocument = () => {
      $('select[name="wt_doc_subcat"]').val('')
      $('select[name="wt_doc_subcat"] option').css('display', 'none')
      $('select[name="wt_doc_subcat"] option[data-cat="' + $('select[name="wt_doc_cat"]').val() + '"]').css('display', 'block')
      if($('select[name="wt_doc_subcat"] option[selected]').attr('style') == 'display: block;') {
        $('select[name="wt_doc_subcat"]').val($('select[name="wt_doc_subcat"] option[selected]').val())
      }
    }
    
    updateSubCategoryDocument()

    $('select[name="wt_doc_cat"]').change(function() {
      updateSubCategoryDocument();
    })
    

    $('.table-departments').DataTable({
      dom: '<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f><"#listCorps">rt<"pull-left m-t-10"i><"m-t-10 pull-right"p>',
      ajaxSource: '{{ route('root') }}/api/v1/wage-templates?corpID=' + {{ $corpID }},
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
          data: 'entry_level',
          className: 'text-center',
          render: (data, type, row, meta) => {
            return '<input type="checkbox" onclick="return false;" ' + (data == 1 ? 'checked' : '') + '/>'
          }
        },
        {
          targets: 6,
          data: "total"
        },
        {
          targets: 7,
          data: '',
          className: 'text-center',
          render: (data, type, row, meta) => {
            return '<a class="btn btn-primary btn-md edit" title="Edit" \
                {{ \Auth::user()->checkAccessByIdForCorp($corpID, 45, 'E') ? '' : 'disabled' }} \
                href="{{ route('wage-templates.index')}}/' + row.wage_tmpl8_id + '/edit?corpID={{ $corpID }}">\
                <i class="fas fa-pencil-alt"></i>\
              </a>\
              <a class="btn btn-info btn-md" title="View/Edit Document" \
                href="{{ route('wage-templates.index')}}/' + row.wage_tmpl8_id + '/edit-contract?corpID={{ $corpID }}"\
                {{ \Auth::user()->checkAccessByIdForCorp($corpID, 45, 'E') ? '' : 'disabled' }}> \
                <i class="far fa-file-alt"></i> \
              </a>\
              <a class="btn btn-danger btn-md" title="Delete" onclick="deleteTemplate(event,' + row.wage_tmpl8_id + ', \'' + row.code + '\')" \
                {{ \Auth::user()->checkAccessByIdForCorp($corpID, 45, 'D') ? '' : 'disabled' }}> \
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
          url: '{{ route('wage-templates.index') }}/' + id + '?corpID={{ $corpID }}',
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