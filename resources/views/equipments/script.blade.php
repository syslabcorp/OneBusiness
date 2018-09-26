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

        $.ajax({
          type: 'GET',
          url: '{{ url('/') }}/api/v1/branches/' + branchID + '/depts?corpID={{ request()->corpID }}',
          success: (res) => {
            for(let i = 0; i < res.depts.length; i++) {
              $('select[name="dept_id"] option[value="' + res.depts[i] + '"]').css('display', 'block')
              $('select[name="jo_dept"] option[value="' + res.depts[i] + '"]').css('display', 'block')
            }
            
            $('select[name="dept_id"] option[value="' + res.depts[0] + '"]').prop('selected', true)
            $('select[name="jo_dept"] option[value="' + res.depts[0] + '"]').prop('selected', true)

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
        if (event.which === 113) {
          addNewPart()
        }
      })

      addNewPart = () => {
        let lastId = $('.table-parts tbody tr').length;
        if (lastId > 1) {
          lastId = 1 + parseInt($('.table-parts tbody tr:eq(' + (lastId - 2) + ') td:eq(0)').text()) 
        }

        $('.table-parts .newPart td:eq(0)').html(lastId)
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

      $('.table-parts').on('click', '.btnSaveRow', (event) => {
        let $trParent = $(event.target).parents('tr')
        $trParent.find('.error').remove()
        
        if ($trParent.find('td:eq(1) input').val().trim() == '') {
          $trParent.find('td:eq(1)').append('<span class="error">Name required</span>')
          return;
        }


        let $trClone = $trParent.clone()
        $trClone.removeClass('newPart').addClass('partRow')
        $trClone.find('.btnSaveRow').css('display', 'none')
        $trClone.find('.btnEditRow').css('display', 'inline-block')
        $trClone.find('select, input').attr('readonly', true)
        $trClone.find('select[name="status"]').val($trParent.find('select[name="status"]').val())
        $trClone.find('select[name="brand_id"]').val($trParent.find('select[name="brand_id"]').val())
        $trClone.find('select[name="cat_id"]').val($trParent.find('select[name="cat_id"]').val())
        $trClone.find('select[name="supplier_id"]').val($trParent.find('select[name="supplier_id"]').val())

        if ($trParent.hasClass('newPart')) {
          let lastId = $('.table-parts tbody tr').length;
          if (lastId > 1) {
            lastId = 1 + parseInt($('.table-parts tbody tr:eq(' + (lastId - 2) + ') td:eq(0)').text()) 
          }

          $trClone.find('.form-control').each((index, element) => {
            $(element).attr('name', 'parts[' + lastId + '][' + $(element).attr('name') + ']')
          })
          $trClone.insertBefore($trParent)
          $trParent.css('display', 'none')
          $trParent.find('select, input').val('')
        } else {
          $trParent.find('input, select').attr('readonly', true)
          $trParent.find('.btnSaveRow').css('display', 'none')
          $trParent.find('.btnEditRow').css('display', 'inline-block')
          // $trParent.remove()
        }
      })

      $('.editEquipment .btn-edit').click((event) => {
        $('.editEquipment .form-control').prop('disabled', false)
        $('.editEquipment .btnAddRow, .btnEditRow, .btnSaveRow, .btnRemoveRow').prop('disabled', false)
        $('.editEquipment .btn-edit').css('display', 'none')
        $('.editEquipment .btn-save').css('display', 'inline-block')
        // $('.partRow input, .partRow select').attr('readonly', true)
      })
    })()
  </script>
@endsection