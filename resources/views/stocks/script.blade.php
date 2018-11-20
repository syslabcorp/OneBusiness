@section('pageJS')
	<script type="text/javascript">
		(() => {
			openTablePart = (event) => {
				$('.table-parts').slideDown()
			}

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

			$(document).on('click', '.btnAddRow', (event) => {
				$('.rowFocus').removeClass('rowFocus')
				let $trParent = $('.newPart')
				$trParent.find('.error').remove()

				let $trClone = $trParent.clone()
				$trClone.css('display', 'table-row')
				$trClone.removeClass('newPart').addClass('partRow')

				if ($trParent.hasClass('newPart')) {
          let lastId = $('.table-parts tbody tr').length;
					
          $trClone.find('.form-control, input[type="checkbox"]').each((index, element) => {
            $(element).attr('name', 'parts[' + lastId + '][' + $(element).attr('name') + ']')
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
				
				$('.table-parts').on('keyup', '.showSuggest', (event) => {
					$parent = $(event.target)

					$('.table-parts tr').removeClass('rowFocus')
					$parent.parents('tr').addClass('rowFocus')
					if (event.which != 38 && event.which != 40 && event.which != 13) searchPart()
				})
				
			})

			$('.table-parts').on('click', '.showSuggest', function(){
        $parent = $(event.target);
     
        $('.table-parts tr').removeClass('rowFocus');
        $parent.parents('tr').addClass('rowFocus');
        
        searchPart();
			})
			
			searchPart = () => {
        $('.listPart').remove();

				let params = 'corpID='+{{ $corpID }};
				
        $.ajax({
          url: '{{ route('api.stocks.index') }}',
          type: 'GET',
          data: params,
          success: (res) => {
						console.log(res)
            $('#equipDetail').append(res)
            // $('.listPart').css('top', ($('.rowFocus').offset().top - 40) + 'px')
            $('.listPart').css('width', $('.table-parts').width())
            $('.listPart tbody tr:eq(0)').addClass('active')
          }
        });
			}
			

		})()
	</script>
@endsection