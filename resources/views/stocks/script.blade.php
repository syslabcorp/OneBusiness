@section('pageJS')
	<script type="text/javascript">
		(() => {
      @if(isset($stock) && $stock->check_transfered())
        showAlertMessage('Some or all of the items on this DR have been transferred already. You cannot edit or delete this anymore...', 'Error')
      @endif

			openTableStock = (event) => {
				$('.table-stocks').slideDown()
			}

			$(window).keydown((event) => {
				if (event.which === 113) {
					$('.btnAddRow').click()
          $('.btnEditRow').click()
				}
			})

			// Table Stocks

			$('.table-stocks').on('click', '.btnRemoveRow', (event) => {
				let $trParent = $(event.target).parents('tr')

				if ($trParent.hasClass('newStock')) {
					$trParent.find('input.form-control').val('')
					$trParent.css('display', 'none')
				} else {
					$trParent.remove()
				}

        totalCost()
			})

			$(document).on('click', '.btnAddRow', (event) => {
				$('.rowFocus').removeClass('rowFocus')
				let $trParent = $('.newStock')

				let $trClone = $trParent.clone()
				$trClone.css('display', 'table-row')
				$trClone.removeClass('newStock').addClass('stockRow')

				if ($trParent.hasClass('newStock')) {
          let lastId = $('.table-stocks tbody tr').length;
					
          $trClone.find('.form-control, input[type="checkbox"]').each((index, element) => {
            $(element).attr('name', 'stocks[' + lastId + '][' + $(element).attr('name') + ']')
          })
          $trClone.insertBefore($trParent)
          $trParent.find('input').val('')
          $trParent.find('input[name="qty"]').val(1)
          $trParent.find('input[name="cost"]').val(0)
          $trParent.find('input[name="subtotal"]').val(0)

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
				let $trParent = $('.newStock')

				let $trClone = $trParent.clone()
				$trClone.css('display', 'table-row')
				$trClone.removeClass('newStock').addClass('stockRow')

				if ($trParent.hasClass('newStock')) {
          let lastId = $('.table-stocks tbody tr').length;
					
          $trClone.find('.form-control, input[type="checkbox"]').each((index, element) => {
            $(element).attr('name', 'stocks[' + lastId + '][' + $(element).attr('name') + ']')
          })
          $trClone.insertBefore($trParent)
          $trParent.find('input').val('')
          $trParent.find('input[name="qty"]').val(1)
          $trParent.find('input[name="cost"]').val(0)
          $trParent.find('input[name="subtotal"]').val(0)

          $trParent.find('.label-table').text('')
					$trParent.css('display', 'none')
        } else {
          $trParent.find('.btnSaveRow').css('display', 'none')
          // $trParent.remove()
				}
				
        showMessage()
			})

      $('.table-stocks').on('click', '.showSuggest', function(){
        $parent = $(event.target);
     
        $('.table-stocks tr').removeClass('rowFocus');
        $parent.parents('tr').addClass('rowFocus');
        searchStock()
      });


			// $('.table-stocks').on('keyup', '.showSuggest', function(){
      //   $parent = $(event.target);
     
      //   $('.table-stocks tr').removeClass('rowFocus');
      //   $parent.parents('tr').addClass('rowFocus');
        
      //   if (event.which != 38 && event.which != 40 && event.which != 13) searchStock()
			// })
			
      $('body').on('keyup', '.table-stocks .rowFocus td:eq(0) input.item_code', function() {
 
        if (event.which != 38 && event.which != 40 && event.which != 13) {
          let params = {};
        
          if ($('.table-stocks .rowFocus td:eq(0) input.item_code').val()) {
            let listFilters = $('.rowFocus input[data-column]')
            for(let i = 0; i < listFilters.length; i++) {
              params[$(listFilters[i]).attr('data-column')] =  $(listFilters[i]).val()
            }
          } else {
            $('.table-stocks .rowFocus td:eq(0) input.item_id').val('')
            $('.table-stocks .rowFocus td:eq(0) input.item_code').val('')
            $('.table-stocks .rowFocus td:eq(1) input').val('')
            $('.table-stocks .rowFocus td:eq(2) input').val('')
            let params = {product_line: '', brand: ''};
          }
          searchStock(params)
        }
      })

      $('body').on('keyup', '.table-stocks .rowFocus td:eq(1) input', function() {
 
        if (event.which != 38 && event.which != 40 && event.which != 13) {
          let params = {};
        
          if ($('.table-stocks .rowFocus td:eq(1) input').val()) {
            let listFilters = $('.rowFocus input[data-column]')
            for(let i = 0; i < listFilters.length; i++) {
              params[$(listFilters[i]).attr('data-column')] =  $(listFilters[i]).val()
            }
          } else {
            $('.table-stocks .rowFocus td:eq(0) input.item_id').val('')
            $('.table-stocks .rowFocus td:eq(0) input.item_code').val('')
            $('.table-stocks .rowFocus td:eq(1) input').val('')
            $('.table-stocks .rowFocus td:eq(2) input').val('')
            let params = {item_code: '', brand: ''};
          }
          searchStock(params)
        }
      })

      $('body').on('keyup', '.table-stocks .rowFocus td:eq(2) input', function() {
 
        if (event.which != 38 && event.which != 40 && event.which != 13) {
          let params = {};
        
          if ($('.table-stocks .rowFocus td:eq(2) input').val()) {
            let listFilters = $('.rowFocus input[data-column]')
            for(let i = 0; i < listFilters.length; i++) {
              params[$(listFilters[i]).attr('data-column')] =  $(listFilters[i]).val()
            }
          } else {
            $('.table-stocks .rowFocus td:eq(0) input.item_id').val('')
            $('.table-stocks .rowFocus td:eq(0) input.item_code').val('')
            $('.table-stocks .rowFocus td:eq(1) input').val('')
            $('.table-stocks .rowFocus td:eq(2) input').val('')
            let params = {item_code: '', product_line: ''};
          }
          searchStock(params)
        }
      })

			searchStock = (params) => {
      
        $.ajax({
          url: '{{ route('stocks.searchStock', ['corpID' => $corpID]) }}' ,
          type: 'GET',
          data:params,
          success: (res) => {
            if (res.length == 432) {
              $('.errorSuggest').remove()
              $('.show_errorSuggest').remove()
              $('.listStock').remove()
              $('.table-stocks').append('<div class="show_errorSuggest"><div class="row errorSuggest" align="right" style="background:#ed7a82; padding: 5px 0px; font-size: 16px">&zwnj;</div><div class="row errorSuggest" align="left" style="background:#f3b2b6; padding: 5px 10px; font-size: 16px; color:red;font-style: italic;">No active items for this branch</div></div>')
            } else {
              $('.errorSuggest').remove()
              $('.show_errorSuggest').remove()
              $('.listStock').remove()
              $('.table-stocks').append(res)
              $('.listStock tbody tr:eq(0)').addClass('active')
              $('.listStock').css('width', $('.table-stocks').width()) 
            }
          }
        });
			}

      $('#PO').on('change', function() {
        searchPO()
      })

      searchPO = () => {     
        $.ajax({
          url: '{{ route('stocks.searchPO', ['corpID' => $corpID]) }}&po=' +  $('#PO').val(),
          type: 'GET',
          success: (res) => {
            if (res.length == 124) {
              $('.errorDR').remove()
              $('.error_PO').append('<div class="errorDR" align="center" style="color:red; font-size: 16px">No active items for this branch</div>')
              $('.stockRow').remove()
            } else {
              $('.errorDR').remove()
              $('.Qty').remove()
              $('.servedQty').remove()
              $('.stockRow').remove()
              $('.listPO').remove()
              $('.table-stocks tbody').append(res)
              totalCostPO() 
            }       
          }
        });
			}
			// Hiden listStock
			$('body').click((event) => {
        if (!$(event.target).parents('.listStock').length) {
          $('.listStock').remove()
        }
      })

			$(window).on('keyup', (event) => {
        if (event.which == 13) {
          setStock();
          return;
        }
        
        if ($('.listStock tr.active').length) {
          let index = $('.listStock tbody tr.active').index();
        
          let position = $('.listStock tr.active').offset().top - $('.listStock').offset().top -250.5 
        
          if (position > 0) {
            $('.listStock').scrollTop(position)
          }

          if (event.which == 38) {
            if ($('.listStock tbody tr.active').length) {
              if (index >= 1) {
                index -= 1;
                $('.listStock tbody tr:eq(' + index + ')').click()
              }
            } else {
              $('.listStock tbody tr:eq(0)').click()
            }
          } else if (event.which == 40) {
            if (($('.listStock tbody tr.active').length - 2)) {          
              if (index != $('.listStock tbody tr').length) {
                index += 1;
                $('.listStock tbody tr:eq(' + index + ')').click()
              }  
            } else {
              $('.listStock tbody tr:eq(0)').click()
            }
          }
        } else {
          $('.listStock tbody tr:eq(0)').click()
        }
      })

			$('body').on('click', '.listStock tbody tr', function(event)  {
        $('.listStock tbody tr').removeClass('active')
        $parent = $(this);
        $parent.addClass('active')
      })
      
      $('body').keypress(function(event) {
        if(event.which == 13) {
          event.preventDefault();
          setStock();
        }
      });

			setStock = () => {
        $parent = $('.listStock tr.active')
        $('.table-stocks .rowFocus td:eq(0) input.item_id').val($parent.find('input[name="item_id"]').val())
        $('.table-stocks .rowFocus td:eq(0) input.item_code').val($parent.find('td:eq(0)').attr('data-id'))
        $('.table-stocks .rowFocus td:eq(1) input').val($parent.find('td:eq(1)').attr('data-id'))
        $('.table-stocks .rowFocus td:eq(2) input').val($parent.find('td:eq(2)').attr('data-id'))
        $('.table-stocks .rowFocus td:eq(3) label').text($parent.find('td:eq(3)').attr('data-id'))
        $('.table-stocks .rowFocus td:eq(8) label').text($parent.find('td:eq(4)').text())
        
        let cost = $parent.find('td:eq(5)').attr('data-id')
   
        if (!$.isNumeric(cost)) {
          cost = 0
        }

        $('.table-stocks .rowFocus td:eq(5) input').val(cost)

        let total = 0.000000000001 + $('.table-stocks .rowFocus td:eq(5) input').val()*$('.table-stocks .rowFocus td:eq(6) input').val()
        $('.table-stocks .rowFocus td:eq(7) input').val(total.toFixed(2))
        
        totalCost()
        
        showMessage()

        $('.listStock').css('display','none')
      }

      $('body').on('keyup', '.table-stocks .quantity', (event) => {
        let $parent = $(event.target).parents('tr')

        if ($parent.find('td:eq(6) input').val() < 1) {
          $parent.find('td:eq(0) input.item_id').val() ? showAlertMessage('Zero quantity detected on ItemCode '+$parent.find('td:eq(0) input.item_code').val(), 'Error') : showAlertMessage('Zero quantity detected on ItemCode ', 'Error') ;
          $parent.find('td:eq(6) input').val(1).keyup()
        } else {
          let total = 0.000000000001+ $parent.find('td:eq(5) input').val()*$parent.find('td:eq(6) input').val()

          if ($parent.find('td:eq(5) input').val()){
            $parent.find('td:eq(7) input').val(total.toFixed(2))
          }

          totalCost()
        }   
      })

      $('body').on('keyup', '.table-stocks .cost', (event) => {
    
        let $parent = $(event.target).parents('tr')
  
        let total = 0.000000000001+ $parent.find('td:eq(5) input').val()*$parent.find('td:eq(6) input').val()

        if ($parent.find('td:eq(5) input').val()){
          $parent.find('td:eq(7) input').val(total.toFixed(2))
        }

        totalCost()
      })

      $('body').on('keyup', '.table-stocks .subtotal', (event) => {
        
        let $parent = $(event.target).parents('tr')
        let total = 0.000000000001+ $parent.find('td:eq(7) input').val()/$parent.find('td:eq(6) input').val()

        if ($parent.find('td:eq(7) input').val()){
          $parent.find('td:eq(5) input').val(total.toFixed(2))
        }

        totalCost()
      })

      totalCost = () => {
        let $rows = $('.table-stocks tbody tr')
        let total = 0.000000000001

        for(let i = 0; i < $rows.length; i++) {
          let $tr = $($rows[i])
          if ($.isNumeric($tr.find('td:eq(7) input').val())) {
            total += parseFloat($tr.find('td:eq(7) input').val())
          }
        }
        $('#total_amt').val(total.toFixed(2))       
        $('#total_amount').text(total.toFixed(2))
      }

      totalCostPO = () => {
        let $rows = $('.table-stocks tbody tr.PO')
        let Qty = $('.Qty').val() - $('.servedQty').val()
        
        for(let i = 0; i < $rows.length; i++) {
          let $tr = $($rows[i])
          if ($.isNumeric($tr.find('td:eq(5) input').val())) {
            let a = $tr.find('td:eq(5) input').val()*Qty;
            $tr.find('td:eq(7) input').val(a.toFixed(2))
          }
        }
        totalCost()
      }      

      totalCost()

      $('body').on('click', '.save_button', (event) => {
        showMessage()
        if (!$('.DR').val()) {
          $('.errorDR').remove()
          $('.error_DR').append('<div class="errorDR" align="center" style="color:red; font-size: 16px">D.R.# is required</div>')
        } else {
          if ($('.showMessage').length == 0) {
            $('.submit_form').submit()
          }
        }
        
      })

      showMessage = () => {
        $('.table-stocks .showMessage').remove()
        if($('.table-stocks tbody .stockRow').length > 0) {
          for(let i = 0; i < $('.table-stocks tbody .stockRow').length; i++) {
            let row = $($('.table-stocks tbody .stockRow')[i]);

            if (row.find('.item_id').val() == '') {
              row.find('td:eq(0)').append('<div class="showMessage" align="center" style="color:red; font-size: 16px">Please select an item</div>')
            } 
          }
        } else {  
          $('.table-stocks ').append('<div class="showMessage" align="center" style="color:red; font-size: 16px">Please select an item</div>')   
          showAlertMessage('Nothing to save...', 'Error')
        }
      }
      
      $('.DR').on('keyup', function() {
        if ($('.DR').val()) {
          $('.errorDR').remove()
        } else {
          $('.error_DR').append('<div class="errorDR" align="center" style="color:red; font-size: 16px">D.R.# is required</div>')
        }
      })

      $('body').on('change', '.filter-select', (event) => {
        let requestAPI = basePartAPI + '?type=' +  $('.branch-select').val() + '&id=' + $('.filter-select').val()
        
        localStorage.setItem('partsType', $('.branch-select').val())
          
        localStorage.setItem('partsTypeId', $('.filter-select').val())

        tablePart.ajax.url(requestAPI).load()
      })
		})()
	</script>
@endsection