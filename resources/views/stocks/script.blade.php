@section('pageJS')
	<script type="text/javascript">
		(() => {
			openTableStock = (event) => {
				$('.table-stocks').slideDown()
			}

			$(window).keydown((event) => {
				if (event.which === 113) {
					$('.btnAddRow').click()
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
				
				$('.table-stocks').on('keyup', '.showSuggest', (event) => {
					$parent = $(event.target)

					$('.table-stocks tr').removeClass('rowFocus')
					$parent.parents('tr').addClass('rowFocus')
					if (event.which != 38 && event.which != 40 && event.which != 13) searchStock()
				})
				
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
				
				$('.table-stocks').on('keyup', '.showSuggest', (event) => {
					$parent = $(event.target)

					$('.table-stocks tr').removeClass('rowFocus')
					$parent.parents('tr').addClass('rowFocus')
					if (event.which != 38 && event.which != 40 && event.which != 13) searchStock()
				})
				
			})

			$('.table-stocks').on('click', '.showSuggest', function(){
        $parent = $(event.target);
     
        $('.table-stocks tr').removeClass('rowFocus');
        $parent.parents('tr').addClass('rowFocus');
        
        searchStock();
			})
			
			searchStock = () => {
        $('.listStock').remove();

				let params = 'corpID='+{{ $corpID }};
				
        $.ajax({
          url: '{{ route('stocks.searchStock') }}',
          type: 'GET',
          data: params,
          success: (res) => {
            $('.table-stocks').append(res)
            // $('.listPart').css('top', ($('.rowFocus').offset().top - 40) + 'px')
            $('.listStock').css('width', $('.table-stocks').width())
            $('.listStock tbody tr:eq(0)').addClass('active')
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
        $('.table-stocks .rowFocus td:eq(0) input.item_id').val($parent.find('td:eq(0)').attr('data-id'))
        $('.table-stocks .rowFocus td:eq(0) input.item_code').val($parent.find('td:eq(1)').attr('data-id'))
        $('.table-stocks .rowFocus td:eq(0) label').text($parent.find('td:eq(1)').text())
        $('.table-stocks .rowFocus td:eq(1) input').val($parent.find('td:eq(2)').attr('data-id'))
        $('.table-stocks .rowFocus td:eq(2) input').val($parent.find('td:eq(3)').attr('data-id'))
        $('.table-stocks .rowFocus td:eq(3) input').val($parent.find('td:eq(4)').attr('data-id'))
        $('.table-stocks .rowFocus td:eq(8) input').val($parent.find('td:eq(5)').attr('data-id'))
        
        let cost = $parent.find('td:eq(12)').attr('data-id')

        if (!$.isNumeric(cost)) {
          cost = 0
        }

        $('.table-stocks .rowFocus td:eq(5) input').val(cost)

        let total = 0.000000000001 + $('.table-stocks .rowFocus td:eq(5) input').val()*$('.table-stocks .rowFocus td:eq(6) input').val()
        $('.table-stocks .rowFocus td:eq(7) input').val(total.toFixed(2))
        
        totalCost()
        
        $('.listStock').css('display','none')
      }

      $('body').on('keyup', '.table-stocks .quantity', (event) => {
        
        let $parent = $(event.target).parents('tr')
        let total = 0.000000000001+ $parent.find('td:eq(5) input').val()*$parent.find('td:eq(6) input').val()

        if ($parent.find('td:eq(5) input').val()){
          $parent.find('td:eq(7) input').val(total.toFixed(2))
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
               
        $('#total_amount').text(total.toFixed(2))
      }     

      totalCost()
      
		})()
	</script>
@endsection