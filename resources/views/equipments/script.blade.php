@section('pageJS')
  <script type="text/javascript">
    (() => {
      openTablePart = (event) => {
        $(event.target).parent('p').slideUp()
        $('.table-parts').slideDown()
      }

      $('#equipDetail select[name="branch"]').change((event) => {
        updateDepartmentsSelect()
      })

      $('.editEquipment .form-control').prop('disabled', true)
      $('.partRow input, .partRow select').attr('readonly', true)
      $('.partRow input[type="checkbox"]').attr('onclick', 'return false;')

      $(window).keydown((event) => {
        if (event.which === 113) {
          $('.btnAddRow').click()
        }
      })

      // Table Parts
   
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
        $('.rowFocus').removeClass('rowFocus')
        let $trParent = $('.newPart')
        $trParent.find('.error').remove()


        let $trClone = $trParent.clone()
        $trClone.css('display', 'table-row')
        $trClone.removeClass('newPart').addClass('partRow')
        $trClone.find('.btnSaveRow').css('display', 'none')
        $trClone.find('input, input').attr('readonly', false)
        $trClone.find('input[name=""]').val($trParent.find('select[name=""]').val())
        $trClone.find('input[name="brand_id"]').val($trParent.find('select[name="brand_id"]').val())
        $trClone.find('input[name="cat_id"]').val($trParent.find('select[name="cat_id"]').val())
        $trClone.find('input[name="supplier_id"]').val($trParent.find('select[name="supplier_id"]').val())

        if ($trParent.hasClass('newPart')) {
          let lastId = $('.table-parts tbody tr').length;
        
          $trClone.find('.form-control, input[type="checkbox"]').each((index, element) => {
            $(element).attr('name', 'parts[' + lastId + '][' + $(element).attr('name') + ']')
          })
          $trClone.insertBefore($trParent)
          $trParent.find('input').val('')
          $trParent.find('input[name="qty"]').val(1)
          $trParent.find('.label-table').text('')
        } else {
          $trParent.find('.btnSaveRow').css('display', 'none')
          // $trParent.remove()
        }
      })

      $('.editEquipment .btn-edit').click((event) => {
        $('.editEquipment .form-control').prop('disabled', false)
        $('.editEquipment .btnAddRow, .btnSaveRow, .btnRemoveRow').prop('disabled', false)
        $('.editEquipment .btn-edit').css('display', 'none')
        $('.editEquipment .btn-save, .editEquipment .addHere').css('display', 'inline-block')
        $('.editEquipment .equipActive').removeAttr('onclick')

        enablePart()
        // $('.partRow input, .partRow select').attr('readonly', true)
      })

      enablePart = () => {
        $('.btnRemoveRow').parents('tr').find('input[type="checkbox"]').attr('onclick', '')

        $('.btnRemoveRow').parents('tr').find('select, input').attr('readonly', false)
      };

      searchPart = () => {
        $('.listPart').remove();

        let params = {};
        let listFilters = $('.rowFocus input[data-column]')

        for(let i = 0; i < listFilters.length; i++) {
          params[$(listFilters[i]).attr('data-column')] =  $(listFilters[i]).val()
        }


        $.ajax({
          url: '{{ route('parts.searchPart') }}',
          type: 'GET',
          data: params,
          success: (res) => {
            $('#equipDetail').append(res)
            // $('.listPart').css('top', ($('.rowFocus').offset().top - 40) + 'px')
            $('.listPart').css('width', $('.table-parts').width())
          }
        });
      }

      $('.table-parts').on('keyup', '.showSuggest', (event) => {
        $parent = $(event.target);
     
        $('.table-parts tr').removeClass('rowFocus');
        $parent.parents('tr').addClass('rowFocus');
        
        searchPart();
      })

      $('body').on('keyup', '.quantity', (event) => {
        let $parent = $(event.target).parents('tr')
        if ($parent.find('td:eq(6) input').val()){
          $parent.find('td:eq(8) input').val($parent.find('td:eq(6) input').val()*$parent.find('td:eq(7) input').val())
        }

        totalCost()
      })

      totalCost = () => {
        let $rows = $('.table-parts tbody tr')
        let total = 0

        for(let i = 0; i < $rows.length; i++) {
          let $tr = $($rows[i])
          if ($.isNumeric($tr.find('td:eq(8) input').val())) {
            total += parseFloat($tr.find('td:eq(8) input').val())
          }
        }

        $('.sumtotal').val(total)
      }

      totalCost()

      $('body').on('click', '.listPart tbody tr', (event) => {
        $parent = $(event.target).parents('tr');

        $('.table-parts .rowFocus td:eq(0) input').val($parent.find('td:eq(0)').attr('data-id'))
        $('.table-parts .rowFocus td:eq(0) label').text($parent.find('td:eq(0)').text())
        $('.table-parts .rowFocus td:eq(1) input').val($parent.find('td:eq(1)').text())
        $('.table-parts .rowFocus td:eq(3) input').val($parent.find('td:eq(2)').attr('data-id'))
        $('.table-parts .rowFocus td:eq(4) input').val($parent.find('td:eq(3)').attr('data-id'))
        $('.table-parts .rowFocus td:eq(5) input').val($parent.find('td:eq(4)').attr('data-id'))
        $('.table-parts .rowFocus td:eq(6) input').val($parent.find('td:eq(8)').attr('data-id'))
        $('.table-parts .rowFocus td:eq(8) input').val($('.table-parts .rowFocus td:eq(6) input').val()*$('.table-parts .rowFocus td:eq(7) input').val())

        totalCost()
      })

      $('body').click((event) => {
        if (!$(event.target).parents('.listPart').length) {
          $('.listPart').remove()
        }
      })

      $('.table-parts').on('click', '.showSuggest', function(){
        $parent = $(event.target);
     
        $('.table-parts tr').removeClass('rowFocus');
        $parent.parents('tr').addClass('rowFocus');
        
        searchPart();
      })
    })()
  </script>
@endsection