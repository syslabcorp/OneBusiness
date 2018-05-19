@extends('layouts.custom')
@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="row">
      <div class="col-sm-6">
        <strong>Payroll Masterfile</strong>
      </div>
      <div class="col-sm-6 text-right">
        @if($action != 'new')
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
  @if($action == 'new')
  <div class="panel-footer">
    <a class="btn btn-default" href="{{ route('payrolls.index', ['corpID' => $corpID, 'tab' => $tab, 'status' => $status]) }}">
      <i class="glyphicon glyphicon-arrow-left"></i>
      Back
    </a>
  </div>
  @endif
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

      $('.table-wages').on('click', '.btn-edit-row', function(event) {
        $(this).closest('tr').find('input').prop('readonly', false)
      })

      $('.table-wages').on('keyup', '.form-control', function(event) {
        if($(this).parent('td').index() == 0) {
          checkTableWages()
        }
      })

      $(document).keyup(function(event) {
        if(event.which == 113 && $('.tab-pane.active .btn-edit').is(':hidden') &&
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

      addRowToTableWage = () => {
        $('.tab-pane.active .table-wages').find('tbody .empty').remove()

        let lastIndex = $('.tab-pane.active .table-wages').find('tbody tr').length

        let fromField = 'range1'
        let toField = 'range1'
        let shareField = 'multi'

        if(!$('.tab-pane.active').hasClass('deductions-tab')) {
          fromField = 'range_1'
          toField = 'range_2'
          shareField = 'emp_share'
        }

        let rowHTML = '<tr> \
              <td> \
                <input type="text" class="form-control" value="0.00" \
                  readonly name="details[' + lastIndex + '][' + fromField + ']"> \
              </td> \
              <td> \
                <input type="text" class="form-control" value="0.00" \
                readonly name="details[' + lastIndex + '][' + toField + ']"> \
              </td> \
              <td> \
                <input type="text" class="form-control" value="0.00" \
                readonly name="details[' + lastIndex + '][' + shareField + ']"> \
              </td>'

        if($('.tab-pane.active').hasClass('expense-tab')) {
          rowHTML += '<td> \
              <input type="text" class="form-control" value="0.00" \
                readonly name="details[' + lastIndex + '][empr_share]"> \
              </td>'
        }

        rowHTML += '<td> \
                <button class="btn btn-sm btn-primary btn-edit-row" title="Edit" type="button"> \
                  <i class="glyphicon glyphicon-pencil"></i> \
                </button> \
                <button class="btn btn-sm btn-danger btn-remove-row" title="Delete" type="button"> \
                  <i class="glyphicon glyphicon-trash"></i> \
                </button> \
              </td> \
            </tr>'

        $('.tab-pane.active .table-wages').find('tbody').append(rowHTML)
        checkTableWages()
      }

      checkTableWages = () => {
        let tableRows = $('.table-wages tbody tr').length

        for(let index = 0; index < tableRows; index++) {
          let checkElement = $($('.table-wages tbody tr')[index]).find('td:eq(0) .form-control')
          checkElement.parent('td').find('.error').remove()

          if(!$.isNumeric(checkElement.val())) {
            checkElement.parent('td').append('<span class="error">Input invalid</span>')
            break
          }

          for(let subIndex = index + 1; subIndex < tableRows; subIndex++) {
            let targetElement = $($('.table-wages tbody tr')[subIndex]).find('td:eq(0) .form-control')

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

      $('.tab-pane .btn-save').click(function(event) {
        $(this).closest('.tab-pane').find('input[name="description"]').keyup()

        if($(this).closest('.tab-pane').find('.error').length > 0) {
          showAlertMessage('Please check form errors', 'Error')
        }else {
          $(this).closest('.tab-pane').find('form').submit()
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
    })()
  </script>
@endsection