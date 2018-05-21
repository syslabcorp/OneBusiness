@extends('layouts.custom')
@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="row">
      <div class="col-sm-6">
        <strong>Payroll Masterfile</strong>
      </div>
      <div class="col-sm-6 text-right">
        @if($action != 'new' && \Auth::user()->checkAccessByIdForCorp($corpID, 39, 'A'))
        <a href="{{ route('payrolls.index', ['corpID' => $corpID, 'tab' => $tab, 'action' => 'new', 'status' => $status]) }}"
          class="addCategory">
          Add Category
        </a>
        @endif
      </div>
    </div>
    
  </div>
  <div class="panel-body">
    <div>
      <ul class="nav nav-tabs" role="tablist">
        @if(!$action || $tab == 'deduct')
        <li role="presentation" class="{{ $tab == 'deduct' ? 'active' : '' }}">
          <a href="#deduct" aria-controls="deduct" role="tab" data-toggle="tab">Deductions</a>
        </li>
        @endif
        @if(!$action || $tab == 'benefit')
        <li role="presentation" class="{{ $tab == 'benefit' ? 'active' : '' }}">
          <a href="#benefit" aria-controls="benefit" role="tab" data-toggle="tab">Benefits</a>
        </li>
        @endif
        @if(!$action || $tab == 'expense')
        <li role="presentation" class="{{ $tab == 'expense' ? 'active' : '' }}">
          <a href="#expense" aria-controls="expense" role="tab" data-toggle="tab">Expense</a>
        </li>
        @endif
      </ul>

      <div class="tab-content" style="margin-top: 30px;">
        <div role="tabpanel" class="tab-pane deductions-tab {{ $tab == 'deduct' ? 'active' : '' }}" id="deduct">
          @include('payrolls.deductions-tab')
        </div>
        <div role="tabpanel" class="tab-pane benefit-tab {{ $tab == 'benefit' ? 'active' : '' }}" id="benefit">
          @include('payrolls.benefits-tab')
        </div>
        <div role="tabpanel" class="tab-pane benefit-tab expense-tab {{ $tab == 'expense' ? 'active' : '' }}" id="expense">
          @include('payrolls.expense-tab')
        </div>
      </div>
    </div>
  </div>
  <div class="panel-footer">
    <div class="rown">
      <div class="col-md-6">
        @if($action == 'new')
        <a class="btn btn-default" href="{{ route('payrolls.index', ['corpID' => $corpID, 'tab' => $tab, 'status' => $status]) }}">
          <i class="glyphicon glyphicon-arrow-left"></i>
          Back
        </a>
        @else
        <button class="btn btn-default btn-cancel" onclick="cancelEdit()"
          style="display: none;">
          <i class="glyphicon glyphicon-remove"></i> Cancel
        </button>
        @endif
      </div>
      <div class="col-md-6 text-right">
        <button class="btn btn-info btn-edit"
          {{ !\Auth::user()->checkAccessByIdForCorp($corpID, 39, 'E') ? 'disabled' : '' }}>
          <i class="glyphicon glyphicon-pencil"></i> Edit
        </button>
        <button class="btn btn-success btn-save" style="display: none;">
          <i class="glyphicon glyphicon-floppy-disk"></i>
          {{ $action == 'new' ? 'Create' : 'Save' }}
        </button>
      </div>
    </div>
  </div>
<div>
@endsection

@section('pageJS')
  @include('payrolls.deductions-js')
  @include('payrolls.benefit-js')

  <script type="text/javascript">
    (function() {
      @if($action == 'new')
        $('.btn-edit').click()
      @endif

      $('.btn-reset').click(function(event) {
        $(this).closest('.table-wages').find('tbody').html(' \
          <tr class="empty"><td colspan="5">Not found any items</td></tr> \
        ')
      })

      $('.table-wages').on('click', '.btn-remove-row', function(event) {
        $(this).closest('tr').remove()
        checkTableWages()
      })

      $('.table-wages').on('keyup', '.form-control', function(event) {
        if($(this).parent('td').index() == 0) {
          checkTableWages()
        }
      })

      $(document).keyup(function(event) {
        if(event.which == 113 && $('.btn-edit').is(':hidden') &&
          $('.tab-pane.active .table-wages').is(':visible')) {
          addRowToTableWage()
        }
      })

      $('.nav.nav-tabs li a').click(function(event) {
        let link = $('.addCategory').attr('href')
        link = link.replace(/&tab=[a-z]*/g, '')
        link += '&tab=' + $(this).attr('aria-controls')

        $('.addCategory').attr('href', link)
      })

      $('.btn-add').click(function(event) {
        addRowToTableWage()
      })

      cancelEdit = () => {
        let path = location.search
        path = path.replace(/&tab=[a-z]*/g, '')
        path += '&tab=' + $('.nav.nav-tabs li.active a').attr('aria-controls')

        window.location = location.pathname + path
      }

      addRowToTableWage = () => {
        $('.tab-pane.active .table-wages').find('tbody .empty').remove()

        let lastIndex = $('.tab-pane.active .table-wages').find('tbody tr').length

        let fromField = 'range1'
        let toField = 'range2'
        let shareField = 'multi'

        if(!$('.tab-pane.active').hasClass('deductions-tab')) {
          fromField = 'range_1'
          toField = 'range_2'
          shareField = 'emp_share'
        }

        let rowHTML = '<tr> \
              <td> \
                <input type="text" class="form-control" value="0.00" validation="number"\
                  name="details[' + lastIndex + '][' + fromField + ']"> \
              </td> \
              <td> \
                <input type="text" class="form-control" value="0.00" validation="number" \
                  name="details[' + lastIndex + '][' + toField + ']"> \
              </td> \
              <td> \
                <input type="text" class="form-control" value="0.00" validation="number" \
                  name="details[' + lastIndex + '][' + shareField + ']"> \
              </td>'

        if($('.tab-pane.active').hasClass('expense-tab')) {
          rowHTML += '<td> \
              <input type="text" class="form-control" value="0.00" data-validation="number" \
                name="details[' + lastIndex + '][empr_share]"> \
              </td>'
        }

        rowHTML += '<td> \
                <button class="btn btn-sm btn-danger btn-remove-row" title="Delete" type="button"> \
                  <i class="glyphicon glyphicon-trash"></i> \
                </button> \
              </td> \
            </tr>'

        $('.tab-pane.active .table-wages').find('tbody').append(rowHTML)
        checkTableWages()
      }

      checkTableWages = () => {
        let tableRows = $('.deductions-tab .table-wages tbody tr').length

        for(let index = 0; index < tableRows; index++) {
          let checkElement = $($('.deductions-tab .table-wages tbody tr')[index]).find('td:eq(0) .form-control')
          checkElement.removeAttr('validation')
          checkElement.parent('td').find('.error').remove()

          if(!$.isNumeric(checkElement.val())) {
            checkElement.parent('td').append('<span class="error">Input invalid</span>')
            break
          }

          for(let subIndex = index + 1; subIndex < tableRows; subIndex++) {
            let targetElement = $($('.deductions-tab .table-wages tbody tr')[subIndex]).find('td:eq(0) .form-control')

            if(!$.isNumeric(targetElement.val())) {
              break
            }

            if(parseFloat(checkElement.val()) == parseFloat(targetElement.val())) {
              checkElement.parent('td').append('<span class="error">Duplicate value</span>')
              break
            }
          }
        }
      }

      showAlertMessage = (message, title = "Alert", isReload = false) => {
        swal({
          title: "<div class='delete-title'>" + title + "</div>",
          text:  "<div class='delete-text'>" + message + "</strong></div>",
          html:  true,
          customClass: 'swal-wide',
          showCancelButton: false,
          closeOnConfirm: true,
          allowEscapeKey: !isReload
        }, (data) => {
          if(isReload) {
            window.location.reload()
          }
        });
      }

      $('.btn-save').click(function(event) {
        $('.tab-pane.active').find('input[name="description"]').keyup()

        if($('.tab-pane.active').find('.error').length > 0) {
          showAlertMessage('Please check form errors', 'Error')
        }else {
          if($('.tab-pane.active .table-wages tbody tr:not(.empty)').length == 0 &&
            $('.tab-pane.active').find('.table-wages').is(':visible')) {
              showAlertMessage('Please add a row', 'Error')
          }else {
            $('.tab-pane.active').find('form').submit()
          }
        }
      })

      statusChange = (event, tab) => {
        let path = location.search.replace(/&tab=[a-z]+/g, '').replace(/&item=[0-9]+/g, '') + "&tab=" + tab
        path = path.replace(/&action=[a-z]*/g, '')
        path = path.replace(/&status=[0-9]*/g, '') + "&status=" + $(event.target).val()
        window.location = location.pathname + path
      }

      itemChange = (event, tab) => {
        let path = location.search.replace(/&tab=[a-z]+/g, '') + "&tab=" + tab
        path = path.replace(/&action=[a-z]*/g, '')
        path = path.replace(/&item=[0-9]*/g, '') + "&item=" + $(event.target).val()
        window.location = location.pathname + path
      }

      $('body').on('keyup', '.form-control[validation]', function(event) {
        $(this).closest('div, td').find('.error').remove()
        let validations = $(this).attr('validation').split('|')
        let messageLabel = $(this).attr('validation-label')
        let messageErr

        for(let index = 0; index < validations.length; index++) {
          if(validations[index].match(/required/) && $(this).val().trim() == '') {
            messageErr = messageLabel + ' required'
            break
          }else if(validations[index].match(/max:[0-9]+/) && 
            $(this).val().length >= parseInt(validations[index].replace(/[^0-9]*/, ''))){
            messageErr = messageLabel + ' should not exceed 50 characters'
            break
          }else if(validations[index].match(/number/) && !$.isNumeric($(this).val())) {
            messageErr = 'Input invalid'
            break
          }
        }

        if(messageErr) {
          $(this).closest('div, td').append(' <span class="error">' + messageErr + '</span>')
        }
      })

      $('.table-wages').on('keyup', '.form-control', function(event) {
        let currentCol = $(this).parents('td').index();
        let currentRow = $(this).parents('tr').index();
        let parentTable = $(this).closest('.table-wages')
        let minCol = 0;
        let maxCol = parentTable.find('tbody tr:eq(0) td').length - 2;
        let maxRow = parentTable.find('tbody').length;

        switch(event.which) {
          case 37:
            currentCol -= 1;
            break;
          case 38:
            currentRow -= 1;
            break;
          case 39:
            currentCol += 1;
            break;
          case 40:
            currentRow += 1;
            break;
          default:
            break;
        }

        if(currentCol < minCol) {
          currentCol = maxCol;
          currentRow -= 1;
        }else if(currentCol > maxCol) {
          currentCol = minCol;
          currentRow += 1;
        }
        if(parentTable.find('tbody tr:eq(' + currentRow + ') td:eq(' + currentCol + ') .form-control').length) {
          parentTable.find('tbody tr:eq(' + currentRow + ') td:eq(' + currentCol + ') .form-control')[0].focus();
        }
      })
    })()
  </script>
@endsection