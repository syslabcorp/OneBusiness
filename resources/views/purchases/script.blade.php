@section('pageJS')
  <script type="text/javascript">
    (() => {
      openTablePurchase = (event) => {
        $(event.target).parent('p').slideUp()
        $('.table-purchases').slideDown()
        // $('.table-purchases .btnAddRow').click()
      }

      // $('#equipDetail select[name="branch"]').change((event) => {
      //   updateDepartmentsSelect()
      // })

      // $('.editEquipment .form-control').prop('disabled', true)
      // $('.editEquipment .partRow input, .editEquipment .partRow select').attr('readonly', true)
      // $('.partRow input[type="checkbox"]').attr('onclick', 'return false;')
      
      $(window).keydown((event) => {
        if (event.which === 113) {
          $('.btnAddRow').click()
        }
        
        if (event.which == 13) {
          event.preventDefault();
        }
      })

      // // Table Parts

      // check length table

      $('.table-purchases').on('click', '.btnRemoveRow', (event) => {
        let $trParent = $(event.target).parents('tr')

        if ($trParent.hasClass('newPart')) {
          $trParent.find('input.form-control').val('')
          $trParent.css('display', 'none')
        } else {
          $trParent.remove()
        }
      })

      $('.table-purchases').on('click', '.btnAddRow', (event) => {
        $('.rowFocus').removeClass('rowFocus')
        let $trParent = $('.newPurchase')
        $trParent.find('.error').remove()

        let $trClone = $trParent.clone()
        $trClone.css('display', 'table-row')
        
        $trClone.removeClass('newPurchase').addClass('purchaseRow')
        // $trClone.find('.btnSaveRow').css('display', 'none')
        // $trClone.find('input, input').attr('readonly', false)
        // $trClone.find('input[name=""]').val($trParent.find('select[name=""]').val())
        
        if ($trParent.hasClass('newPurchase')) {
          let lastId = $('.table-purchases tbody tr').length;
        
          $trClone.find('.form-control, input[type="radio"]').each((index, element) => {
            $(element).attr('name', 'purchases[' + lastId + '][' + $(element).attr('name') + ']')
          })

          $trClone.find('label').text($('.table-purchases tbody tr').length)
       
          $trClone.insertBefore($trParent)
         
          $trParent.find('label .index').text($('.table-purchases tbody tr').length)
          // $trParent.css('display', 'none')
        } else {
          $trParent.find('.btnSaveRow').css('display', 'none')
          // $trParent.remove()
        }
      })

      $('body').on('change', '.quantity', (event) => {
        let $parent = $(event.target).parents('tr')
        
        if ($parent.find('td:eq(4) input').val() < 1){
          showAlertMessage('Duplicate entry detected...', 'Item Entry Error...')
          $parent.find('td:eq(4) input').val(1)
        }

        totalCost()
      })

      totalCost = () => {
        let $rows = $('.table-purchases tbody tr.purchaseRow')
        let total = 0
      
        for(let i = 0; i < $rows.length; i++) {
          let $tr = $($rows[i])
          
          if ($.isNumeric($tr.find('td:eq(4) input').val())) {
            total += parseFloat($tr.find('td:eq(4) input').val())
          }
        }
        
        $('.sumtotal').val(total.toFixed(2))
      }

      totalCost()

      checkradio = () => {
        let $value = true
        $('.table-purchases tbody tr.purchaseRow').each(function(){
          if($(this).find('input[type="radio"]:checked').length <= 0)
            {
              showAlertMessage('Not checked', 'Item Entry Error...')
              $value = false
              return false
            }   
        });
        return $value
      }

      $('.btn-save').on('click', function() {
        let $value = checkradio()
    
        if ( $value == true) {
          $('.form').submit()
        }
      })

      $('body').on('change', 'input:radio', function() {
        let $rows = $('.table-purchases tbody tr.purchaseRow')
        let $indexrow = $(this).parents('tr').index() + 1

        $.ajax({
          url: '{{ route('purchase_request.getBrands') }}?corpID={{ request()->corpID }}&radio='+ $(this).val() +'&value=' + event.target.checked,
          type: 'GET',
          success: (res) => {
            $('select[name="purchases['+ $indexrow +'][item_id]"] .brands').remove()
            for(let i = 0; i < res.length; i++) {
              if (res[i].asset_id) {
                $('select[name="purchases['+ $indexrow +'][item_id]"]').append('<option class="brands" value="'+ res[i].asset_id +'">'+ res[i].description +'</option>')
              }
              if (res[i].item_id) {
                $('select[name="purchases['+ $indexrow +'][item_id]"]').append('<option class="brands" value="'+ res[i].item_id +'">'+ res[i].description +'</option>')
              }

            }
          }
        });
      })

      // $('.editEquipment .btn-edit').click((event) => {
      //   $('.editEquipment .form-control').prop('disabled', false)
      //   $('.editEquipment .btnAddRow, .btnSaveRow, .btnRemoveRow').prop('disabled', false)
      //   $('.editEquipment .btn-edit').css('display', 'none')
      //   $('.editEquipment .btn-save, .editEquipment .addHere').css('display', 'inline-block')
      //   $('.editEquipment .equipActive').removeAttr('onclick')

      //   enablePart()
      //   // $('.partRow input, .partRow select').attr('readonly', true)
      // })

      // enablePart = () => {
      //   $('.btnRemoveRow').parents('tr').find('input[type="checkbox"]').attr('onclick', '')

      //   $('.btnRemoveRow').parents('tr').find('select, input').attr('readonly', false)
      // };

      // searchPart = () => {
      //   $('.listPart').remove();

      //   let params = {};
      //   let listFilters = $('.rowFocus input[data-column]')

      //   for(let i = 0; i < listFilters.length; i++) {
      //     params[$(listFilters[i]).attr('data-column')] =  $(listFilters[i]).val()
      //   }


      //   $.ajax({
      //     url: '{{ route('parts.searchPart') }}',
      //     type: 'GET',
      //     data: params,
      //     success: (res) => {
      //       $('#equipDetail').append(res)
      //       // $('.listPart').css('top', ($('.rowFocus').offset().top - 40) + 'px')
      //       $('.listPart').css('width', $('.table-parts').width())
      //       $('.listPart tbody tr:eq(0)').addClass('active')
      //     }
      //   });
      // }

      // $('.table-parts').on('keyup', '.showSuggest', (event) => {
      //   $parent = $(event.target);
        
      //   $('.table-parts tr').removeClass('rowFocus');
      //   $parent.parents('tr').addClass('rowFocus');
      //   if (event.which != 38 && event.which != 40 &&  event.which != 13) searchPart();
      // })

      // $('body').on('keyup', '.quantity', (event) => {
      //   let $parent = $(event.target).parents('tr')
      //   let total = 0.000000000001+ $parent.find('td:eq(6) input').val()*$parent.find('td:eq(7) input').val()

      //   if ($parent.find('td:eq(6) input').val()){
      //     $parent.find('td:eq(8) input').val(total.toFixed(2))
      //   }

      //   totalCost()
      // })

      // $('body').on('keyup', '.lastcost', (event) => {
      //   let $parent = $(event.target).parents('tr')
  
      //   let total = 0.000000000001+ $parent.find('td:eq(6) input').val()*$parent.find('td:eq(7) input').val()

      //   if ($parent.find('td:eq(6) input').val()){
      //     $parent.find('td:eq(8) input').val(total.toFixed(2))
      //   }

      //   totalCost()
      // })
      

      
      // $('body').on('click', '.listPart tbody tr', function(event)  {
      //   $('.listPart tbody tr').removeClass('active')
      //   $parent = $(this);
      //   $parent.addClass('active')
      // })

      
      // $('body').click((event) => {
      //   if (!$(event.target).parents('.listPart').length) {
      //     $('.listPart').remove()
      //   }
      // })

      // $('.table-parts').on('click', '.showSuggest', function(){
      //   $parent = $(event.target);
     
      //   $('.table-parts tr').removeClass('rowFocus');
      //   $parent.parents('tr').addClass('rowFocus');
        
      //   searchPart();
      // })

      // $(window).on('keyup', (event) => {
      //   if (event.which == 13) {
      //     setPart();
      //     return false;
      //   }
        
      //   if ($('.listPart tr.active').length) {
      //     let index = $('.listPart tbody tr.active').index();
        
      //     let position = $('.listPart tr.active').offset().top - $('.listPart').offset().top - 250.5 
        
      //     if (position > 0) {
      //       $('.listPart').scrollTop(position)
      //     }


      //     if (event.which == 38) {
      //       if ($('.listPart tbody tr.active').length) {
      //         if (index >= 1) {
      //           index -= 1;
      //           $('.listPart tbody tr:eq(' + index + ')').click()
      //         }
      //       } else {
      //         $('.listPart tbody tr:eq(0)').click()
      //       }
      //     } else if (event.which == 40) {
      //       if (($('.listPart tbody tr.active').length - 2)) {          
      //         if (index != $('.listPart tbody tr').length) {
      //           index += 1;
      //           $('.listPart tbody tr:eq(' + index + ')').click()
      //         }  
      //       } else {
      //         $('.listPart tbody tr:eq(0)').click()
      //       }
      //     }
      //   } else {
      //     $('.listPart tbody tr:eq(0)').click()
      //   }
      // })

      // // $('body').keypress(function(event) {
      // //   if(event.which == 13) {
      // //     event.preventDefault();
      // //     setPart();
      // //   }
      // // });

      // setPart = () => {
      //   $parent = $('.listPart tr.active')

      //   if ($('.table-parts .partRow').length) {
      //     for (let i = 0; i < $('.table-parts .partRow').length; i++) {
            
      //       let $row = $($('.table-parts .partRow')[i]);
        
      //       if ( $row.find('input.item_id').val() == $parent.find('td:eq(0)').attr('data-id') ) {
      //         showAlertMessage('Duplicate entry detected...', 'Item Entry Error...')
      //         break;
      //       }
            
      //       if ( i == $('.table-parts .partRow').length - 1 ) {
      //         $('.table-parts .rowFocus td:eq(0) input').val($parent.find('td:eq(0)').attr('data-id'))
      //         $('.table-parts .rowFocus td:eq(0) label').text($parent.find('td:eq(0)').text())
      //         $('.table-parts .rowFocus td:eq(1) input').val($parent.find('td:eq(1)').text())
      //         $('.table-parts .rowFocus td:eq(3) input').val($parent.find('td:eq(2)').attr('data-id'))
      //         $('.table-parts .rowFocus td:eq(4) input').val($parent.find('td:eq(3)').attr('data-id'))
      //         $('.table-parts .rowFocus td:eq(5) input').val($parent.find('td:eq(4)').attr('data-id'))
      //         $('.table-parts .rowFocus td:eq(6) input').val($parent.find('td:eq(8)').text())
              
      //         if ($.isNumeric($('.table-parts .rowFocus td:eq(6) input').val()*$('.table-parts .rowFocus td:eq(7) input').val())) {
      //           let total = 0.000000000001 + $('.table-parts .rowFocus td:eq(6) input').val()*$('.table-parts .rowFocus td:eq(7) input').val()
      //           $('.table-parts .rowFocus td:eq(8) input').val(total.toFixed(2))
      //         } else {
      //           $('.table-parts .rowFocus td:eq(8) input').val(0)
      //         }
              
      //         totalCost()
              
      //         $('.listPart').css('display','none')
      //       } 

      //     }  
      //   }

      // }
    })()
  </script>
@endsection