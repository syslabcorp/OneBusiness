@section('pageJS')
  <script type="text/javascript">
    (() => {
      openTablePart = (event) => {
        addNewPart()
        $(event.target).parent('p').slideUp()
        $('.table-parts').slideDown()
      }

      $('#equipDetail select[name="branch"]').change((event) => {
        updateDepartmentsSelect()
      })

      updateDepartmentsSelect = () => {
        let branchID = $('#equipDetail select[name="branch"]').val()
        $('select[name="dept_id"] option').css('display', 'none')
        $('select[name="jo_dept"] option').css('display', 'none')
        $('select[name="dept_id"]').val('')
        $('select[name="jo_dept"]').val('')

        $.ajax({
          type: 'GET',
          url: '{{ url('/') }}/api/v1/branches/' + branchID + '/depts?corpID={{ request()->corpID }}',
          success: (res) => {
            for(let i = 0; i < res.depts.length; i++) {
              $('select[name="dept_id"] option[value="' + res.depts[i] + '"]').css('display', 'block')
              $('select[name="jo_dept"] option[value="' + res.depts[i] + '"]').css('display', 'block')
            }
            
            if (branchID == $('#equipDetail select[name="branch"]').attr('data-branch')) {
              $('select[name="dept_id"] option[value="{{ $equipment->dept_id }}"]').prop('selected', true)
              $('select[name="jo_dept"] option[value="{{ $equipment->jo_dept }}"]').prop('selected', true)
            } else if (res.depts.length) {
              $('select[name="dept_id"] option[value="' + res.depts[0] + '"]').prop('selected', true)
              $('select[name="jo_dept"] option[value="' + res.depts[0] + '"]').prop('selected', true)
            }
          },
          error: (res) => {

          }
        })
      }

      updateDepartmentsSelect()

      $('.editEquipment .form-control').prop('disabled', true)
      $('.partRow input, .partRow select').attr('readonly', true)
      $('.partRow input[type="checkbox"]').attr('onclick', 'return false;')

      $(window).keydown((event) => {
        if (event.which === 113 && !$('.btn-edit').is(':visible')) {
          addNewPart()
        }
      })

      addNewPart = () => {
        $('.table-parts .newPart').css('display', 'table-row')
        $('.table-parts .newPart .error').remove()
      }

      // Table Parts
      $('.table-parts').on('click', '.btnEditRow', function(event) {
        $(this).css('display', 'none')
        $(this).parents('tr').find('.btnSaveRow').css('display', 'inline-block')
        $(this).parents('tr').find('input[type="checkbox"]').attr('onclick', '')

        $(this).parents('tr').find('select, input').attr('readonly', false)
      })
      $('.table-parts').on('click', '.btnRemoveRow', (event) => {
        let $trParent = $(event.target).parents('tr')

        if ($trParent.hasClass('newPart')) {
          $trParent.find('input.form-control').val('')
          $trParent.css('display', 'none')
        } else {
          $trParent.remove()
        }
      })

      $('.table-parts').on('click', '.btnAddRow', (event) => {
        let $trParent = $('.newPart')
        $trParent.find('.error').remove()
        

        let $trClone = $trParent.clone()
        $trClone.removeClass('newPart').addClass('partRow')
        $trClone.find('.btnSaveRow').css('display', 'none')
        $trClone.find('select, input').attr('readonly', false)
        $trClone.find('select[name=""]').val($trParent.find('select[name=""]').val())
        $trClone.find('select[name="brand_id"]').val($trParent.find('select[name="brand_id"]').val())
        $trClone.find('select[name="cat_id"]').val($trParent.find('select[name="cat_id"]').val())
        $trClone.find('select[name="supplier_id"]').val($trParent.find('select[name="supplier_id"]').val())
        
        if ($trParent.hasClass('newPart')) {
          let lastId = $('.table-parts tbody tr').length;
        
          $trClone.find('.form-control, input[type="checkbox"]').each((index, element) => {
            $(element).attr('name', 'parts[' + lastId + '][' + $(element).attr('name') + ']')
          })
          $trClone.insertBefore($trParent)
          $trParent.find('input').val('')
          $trParent.find('.label-table').text('')
        } else {
          $trParent.find('.btnSaveRow').css('display', 'none')
          // $trParent.remove()
        }
      })

      $('.editEquipment .btn-edit').click((event) => {
        $('.editEquipment .form-control').prop('disabled', false)
        $('.editEquipment .btnAddRow, .btnEditRow, .btnSaveRow, .btnRemoveRow').prop('disabled', false)
        $('.editEquipment .btn-edit').css('display', 'none')
        $('.editEquipment .btn-save, .editEquipment .addHere').css('display', 'inline-block')
        $('.editEquipment .equipActive').removeAttr('onclick')
        // $('.partRow input, .partRow select').attr('readonly', true)
      })


      searchPart = (column, value) => {
        $('.listPart').remove();  
        $.ajax({
          url: '{{ route('parts.searchPart') }}?' + column + '=' + value,
          type: 'GET',
          success: (res) => {
            $('.editEquipment').append(res)
          }
        });
      }

      $('.table-parts').on('keyup', '.showSuggest', (event) => {
        $parent = $(event.target);
        console.log($parent.offset().top)
        
        $('.table-parts tr').removeClass('rowFocus');
        $parent.parents('tr').addClass('rowFocus');
  
        searchPart($parent.attr('data-column'), $parent.val())
      })

      $('body').on('click', '.listPart tbody tr', (event) => {
        $parent = $(event.target).parents('tr');

        $('.table-parts .rowFocus td:eq(0) input').val($parent.find('td:eq(0)').attr('data-id'))
        $('.table-parts .rowFocus td:eq(0) label').text($parent.find('td:eq(0)').text())
        $('.table-parts .rowFocus td:eq(1) input').val($parent.find('td:eq(1)').text())
        $('.table-parts .rowFocus td:eq(3) input').val($parent.find('td:eq(2)').attr('data-id'))
        $('.table-parts .rowFocus td:eq(3) label').text($parent.find('td:eq(2)').text())
        $('.table-parts .rowFocus td:eq(4) input').val($parent.find('td:eq(3)').attr('data-id'))
        $('.table-parts .rowFocus td:eq(4) label').text($parent.find('td:eq(3)').text())
        $('.table-parts .rowFocus td:eq(5) input').val($parent.find('td:eq(4)').attr('data-id'))
        $('.table-parts .rowFocus td:eq(5) label').text($parent.find('td:eq(4)').text())
      })

    })()
  </script>
@endsection