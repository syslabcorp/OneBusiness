@section('pageJS')
<script type="text/javascript">
  (() => {
    openTableStocktransfer = (event) => {
      $('.table-stocks').slideDown()
    }

    $(window).keydown((event) => {
      if (event.which === 113) {
        $('.btnAddRow').click()
        $('.btnEditRow').click()
      }
    })

    // Table Stocktransfer

    $('.table-stocktransfer').on('click', '.btnRemoveRow', (event) => {
      let $trParent = $(event.target).parents('tr')

      if ($trParent.hasClass('newStocktransfer')) {
        $trParent.find('input.form-control').val('')
        $trParent.css('display', 'none')
      } else {
        $trParent.remove()
      }
    })

    $(document).on('click', '.btnAddRow', (event) => {
      $('.rowFocus').removeClass('rowFocus')
      let $trParent = $('.newStocktransfer')

      let $trClone = $trParent.clone()
      $trClone.css('display', 'table-row')
      $trClone.removeClass('newStocktransfer').addClass('stocktransferRow')

      if ($trParent.hasClass('newStocktransfer')) {
        let lastId = $('.table-stocktransfer tbody tr').length;
        
        $trClone.find('.form-control, input[type="checkbox"]').each((index, element) => {
          $(element).attr('name', 'details[' + lastId + '][' + $(element).attr('name') + ']')
        })
        $trClone.insertBefore($trParent)
        $trParent.find('input').val('')
        $trParent.find('input[name="qty"]').val(1)

        $trParent.find('.label-table').text('')
        $trParent.css('display', 'none')
      } else {
        $trParent.find('.btnSaveRow').css('display', 'none')
        // $trParent.remove()
      }
      
      showMessage()
    })
      
    $(document).on('click', '.btnEditRow', (event) => {
      $('.rowFocus').removeClass('rowFocus')
      let $trParent = $('.newStocktransfer')

      let $trClone = $trParent.clone()
      $trClone.css('display', 'table-row')
      $trClone.removeClass('newStocktransfer').addClass('stocktransferRow')

      if ($trParent.hasClass('newStocktransfer')) {
        let lastId = $('.table-stocktransfer tbody tr').length;
        
        $trClone.find('.form-control, input[type="checkbox"]').each((index, element) => {
          $(element).attr('name', 'details[' + lastId + '][' + $(element).attr('name') + ']')
        })
        $trClone.insertBefore($trParent)
        $trParent.find('input').val('')
        $trParent.find('input[name="qty"]').val(1)

        $trParent.find('.label-table').text('')
        $trParent.css('display', 'none')
      } else {
        $trParent.find('.btnSaveRow').css('display', 'none')
        // $trParent.remove()
      }
      
      showMessage()
    })

    $('.table-stocktransfer').on('click', '.showSuggest', function(){
      $parent = $(event.target);
    
      $('.table-stocktransfer tr').removeClass('rowFocus');
      $parent.parents('tr').addClass('rowFocus');
      searchStocktransfer()
    });


    $('.table-stocktransfer').on('keyup', '.showSuggest', function(){
      $parent = $(event.target);
    
      $('.table-stocktransfer tr').removeClass('rowFocus');
      $parent.parents('tr').addClass('rowFocus');
      
      if (event.which != 38 && event.which != 40 && event.which != 13) searchStocktransfer()
    })

    searchStocktransfer = () => {
      let params = {};
      
      
      if ($('.table-stocktransfer .rowFocus td:eq(0) input.item_code').val()) {
        let listFilters = $('.rowFocus input[data-column]')

        for(let i = 0; i < listFilters.length; i++) {
          params[$(listFilters[i]).attr('data-column')] =  $(listFilters[i]).val()
        }
      } else {
        $('.table-stocktransfer .rowFocus td:eq(0) input.item_id').val('')
        $('.table-stocktransfer .rowFocus td:eq(1) input').val('')
        $('.table-stocktransfer .rowFocus td:eq(2) input').val('')
        let params = {product_line: '', brand: ''};
      }
   
      $.ajax({
        url: '{{ route('stocktransfer.searchStocktransfer', ['corpID' => $corpID]) }}&branch=' + $('.Txfr_To_Branch').val(),
        type: 'GET',
        data:params,
        success: (res) => {
          if ( res.length == 458) {
            $('.errorSuggest').remove()
            $('.show_errorSuggest').remove()
            $('.listStocktransfer').remove()
            $('.table-stocktransfer').append('<div class="show_errorSuggest"><div class="row errorSuggest" align="right" style="background:#ed7a82; padding: 5px 0px; font-size: 16px">&zwnj;</div><div class="row errorSuggest" align="left" style="background:#f3b2b6; padding: 5px 10px; font-size: 16px; color:red;">No active items for this branch</div><br></div>')
          } else {
            $('.errorSuggest').remove()
            $('.show_errorSuggest').remove()
            $('.listStocktransfer').remove()
            $('.table-stocktransfer').append(res)
            $('.listStocktransfer tbody tr:eq(0)').addClass('active')
            $('.listStocktransfer').css('width', $('.table-stocktransfer').width()) 
          }
        }
      });
    }

    // Hiden listStocktransfer
    $('body').click((event) => {
      if (!$(event.target).parents('.listStocktransfer').length) {
        $('.listStocktransfer').remove()
      }
    })

    $(window).on('keyup', (event) => {
      if (event.which == 13) {
        setStocktransfer();
        return;
      }
      
      if ($('.listStocktransfer tr.active').length) {
        let index = $('.listStocktransfer tbody tr.active').index();
      
        let position = $('.listStocktransfer tr.active').offset().top - $('.listStocktransfer').offset().top -250.5 
      
        if (position > 0) {
          $('.listStocktransfer').scrollTop(position)
        }

        if (event.which == 38) {
          if ($('.listStocktransfer tbody tr.active').length) {
            if (index >= 1) {
              index -= 1;
              $('.listStocktransfer tbody tr:eq(' + index + ')').click()
            }
          } else {
            $('.listStocktransfer tbody tr:eq(0)').click()
          }
        } else if (event.which == 40) {
          if (($('.listStocktransfer tbody tr.active').length - 2)) {          
            if (index != $('.listStocktransfer tbody tr').length) {
              index += 1;
              $('.listStocktransfer tbody tr:eq(' + index + ')').click()
            }  
          } else {
            $('.listStocktransfer tbody tr:eq(0)').click()
          }
        }
      } else {
        $('.listStocktransfer tbody tr:eq(0)').click()
      }
    })

    $('body').on('click', '.listStocktransfer tbody tr', function(event)  {
      $('.listStocktransfer tbody tr').removeClass('active')
      $parent = $(this);
      $parent.addClass('active')
    })
    
    // $('body').keypress(function(event) {
    //   if(event.which == 13) {
    //     event.preventDefault();
    //     setStocktransfer();
    //   }
    // });

    setStocktransfer = () => {
      $parent = $('.listStocktransfer tr.active')

      if ($('.table-stocktransfer .stocktransferRow').length) {

        for (let i = 0; i < $('.table-stocktransfer .stocktransferRow').length; i++) {
          
          let $row = $($('.table-stocktransfer .stocktransferRow')[i]);
          // console.log($row.find('input.item_id').val())
          // console.log($parent.find('input[name="item_id"]').val())
          if ( $row.find('input.item_id').val() == $parent.find('input[name="item_id"]').val() ) {
            showAlertMessage('Duplicate entry detected...', 'Item Entry Error...')
            break;
          } else if ($parent.find('td:eq(4)').attr('data-id') == 0) {
            showAlertMessage('Qty exceeds stock on hand...', 'Error in Qty')
            break;
          }
          
          if ( i == $('.table-stocktransfer .stocktransferRow').length - 1 ) {
            $('.table-stocktransfer .rowFocus td:eq(0) input.item_id').val($parent.find('input[name="item_id"]').val())
            $('.table-stocktransfer .rowFocus td:eq(0) input.item_code').val($parent.find('td:eq(0)').attr('data-id'))
            $('.table-stocktransfer .rowFocus td:eq(1) input').val($parent.find('td:eq(1)').attr('data-id'))
            $('.table-stocktransfer .rowFocus td:eq(2) input').val($parent.find('td:eq(2)').attr('data-id'))
            $('.table-stocktransfer .rowFocus td:eq(3) label').text($parent.find('td:eq(3)').text())
            $('.table-stocktransfer .rowFocus td:eq(4) input').attr('data-hand', $parent.find('td:eq(4)').attr('data-id'))
            $('.table-stocktransfer .rowFocus td:eq(5) label').text($parent.find('td:eq(5)').text())
            
            showMessage()

            $('.listStocktransfer').css('display','none')
          } 

        }  
      }

      
    }

    $('body').on('keyup', '.table-stocktransfer .rowFocus td:eq(4) input', (event) => {

      if (parseInt($('.table-stocktransfer .rowFocus td:eq(4) input').val()) > parseInt($('.table-stocktransfer .rowFocus td:eq(4) input').attr('data-hand'))) {
        showAlertMessage('Qty exceeds stock on hand...', 'Error in Qty')
        $('.table-stocktransfer .rowFocus td:eq(4) input').val(1)
      }
    }) 

    $('body').on('keyup', '.table-stocktransfer tr.stocktransferRow td:eq(4) input', (event) => {

      if (parseInt($('.table-stocktransfer tr.stocktransferRow td:eq(4) input').val()) > parseInt($('.table-stocktransfer tr.stocktransferRow td:eq(4) input').attr('data-hand'))) {
        showAlertMessage('Qty exceeds stock on hand...', 'Error in Qty')
        $('.table-stocktransfer tr.stocktransferRow td:eq(4) input').val(1)
      }
    }) 


    $('body').on('click', '.save_button', (event) => {
      showMessage()
      if ($('.showMessage').length == 0) {
        $('.submit_form').submit()
      }
      
    })

    showMessage = () => {
      $('.table-stocktransfer .showMessage').remove()
      if($('.table-stocktransfer tbody .stocktransferRow').length > 0) {
        for(let i = 0; i < $('.table-stocktransfer tbody .stocktransferRow').length; i++) {
            let row = $($('.table-stocktransfer tbody .stocktransferRow')[i]);

            if (row.find('.item_id').val() == '') {
            row.find('td:eq(0)').append('<div class="showMessage" align="center" style="color:red; font-size: 16px">Please select an item</div>')
            } 
        }
      } else {  
        $('.table-stocktransfer ').append('<div class="showMessage" align="center" style="color:red; font-size: 16px">Please select an item</div>')   
        showAlertMessage('Nothing to save...')
      }
    }
    
    $('body').on('keyup', '.table-stocktransfer .quantity', (event) => {
      let $parent = $(event.target).parents('tr')
     
      if ($parent.find('td:eq(4) input').val() < 1) {
        $parent.find('td:eq(0) input.item_id').val() ? showAlertMessage('Zero quantity detected on ItemCode '+$parent.find('td:eq(0) input.item_code').val()) : showAlertMessage('Zero quantity detected on ItemCode ') ;
        $parent.find('td:eq(4) input').val(1)
      } 
    })
  })()
</script>
@endsection