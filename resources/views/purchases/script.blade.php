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
          $('tr[data-parent="'+ $trParent.attr('data-id') +'"]').remove()
        }

        indexs()
      })

      $('.table-purchases').on('click', '.btnAddRow', (event) => {
        if ($('input[name="eqp_prt"]:checked').val() == 'parts') {
          $.ajax({
            url: '{{ route('purchase_request.getBrands') }}?corpID={{ request()->corpID }}&radio='+ $('input[name="eqp_prt"]:checked').val() ,
            type: 'GET',
            success: (res) => {
              $('.table-purchases tbody').prepend(res)
              // $('select[name="purchases['+ $indexrow +'][item_id]"] .brands').remove()
              // for(let i = 0; i < res.length; i++) {
              //   if (res[i].asset_id) {
              //     $('select[name="purchases['+ $indexrow +'][item_id]"]').append('<option class="brands" value="'+ res[i].asset_id +'">'+ res[i].description +'</option>')
              //   }
              //   if (res[i].item_id) {
              //     $('select[name="purchases['+ $indexrow +'][item_id]"]').append('<option class="brands" value="'+ res[i].item_id +'">'+ res[i].description +'</option>')
              //   }

              // }
              indexs()
            }
          });
          
        } else if ($('input[name="eqp_prt"]:checked').val() == 'equipment') {
          $.ajax({
            url: '{{ route('purchase_request.getBrands') }}?corpID={{ request()->corpID }}&radio='+ $('input[name="eqp_prt"]:checked').val() ,
            type: 'GET',
            success: (res) => {
              $('.table-purchases tbody').prepend(res)
              // $('select[name="purchases['+ $indexrow +'][item_id]"] .brands').remove()
              // for(let i = 0; i < res.length; i++) {
              //   if (res[i].asset_id) {
              //     $('select[name="purchases['+ $indexrow +'][item_id]"]').append('<option class="brands" value="'+ res[i].asset_id +'">'+ res[i].description +'</option>')
              //   }
              //   if (res[i].item_id) {
              //     $('select[name="purchases['+ $indexrow +'][item_id]"]').append('<option class="brands" value="'+ res[i].item_id +'">'+ res[i].description +'</option>')
              //   }

              // }
              indexs()
            }
          }); 
        }
      })

      $('body').on('change', '.quantity', (event) => {
        let $parent = $(event.target).parents('tr')
      
        if ($parent.find('td:eq(2) input').val() < 1){
          showAlertMessage('Duplicate entry detected...', 'Item Entry Error...')
          $parent.find('td:eq(2) input').val(1)
        }
        if ($parent.find('td:eq(1) input').val() < 1){
          showAlertMessage('Duplicate entry detected...', 'Item Entry Error...')
          $parent.find('td:eq(1) input').val(1)
        }
        indexs()
        // totalCost()
      })

      // totalCost = () => {
      //   let $rows = $('.table-purchases tbody tr.purchaseRow')
      //   let total = 0
      
      //   for(let i = 0; i < $rows.length; i++) {
      //     let $tr = $($rows[i])
          
      //     if ($.isNumeric($tr.find('td:eq(4) input').val())) {
      //       total += parseFloat($tr.find('td:eq(4) input').val())
      //     }
      //   }
        
      //   $('.sumtotal').val(total.toFixed(2))
      // }

      // totalCost()

      $('.btn-save').on('click', function() {
        if ($('input[type="radio"]:checked').length > 0 ) {
          $('.form').submit()
        } else {
          showAlertMessage('Not checked', 'Item Entry Error...')
        }
      })

      $('body').on('change', 'input:radio', function(event) {
        // let $rows = $('.table-purchases tbody tr.purchaseRow')
        // let $indexrow = $(this).parents('tr').index() + 1
        let $trParent = $(event.target).parents('tr')
        $('tr.purchaseRow').remove()
        $('tr.rowTR').remove()
        $.ajax({
          url: '{{ route('purchase_request.getBrands') }}?corpID={{ request()->corpID }}&radio='+ $(this).val() ,
          type: 'GET',
          success: (res) => {
            $('.table-purchases tbody').prepend(res)
            indexs()
          }
        });
      })

      checkEQP = (self) => {
        let $value = true
        $('.table-purchases tbody tr.purchaseRow').each(function(){
          if($(this).attr('data-id') == self.val())
            {
              showAlertMessage('Not checked', 'Item Entry Error...')
              self.parents('tr').remove()
              $('tr[data-parent="'+ self.parents('tr').attr('data-id') +'"]').remove()
              $value = false
              return false
            }   
        });
        return $value
      }

      indexs = () => {
        let a = 1
        let sum = 0
        
        if ($('input[type="radio"]:checked').val() == 'equipment') {
          $('.table-purchases tbody tr.purchaseRow').each(function(index, element){
            $(this).find('label.index').text(a)
            $(this).find('select').attr('name', 'purchases['+ a++ +'][item_id]')
          });
          
          $('.table-purchases tbody tr.rowTR').each(function(index, element){
            sum += parseFloat($(this).find('input[type="number"]').val())
          });
        }

        if ($('input[type="radio"]:checked').val() == 'parts') {
          $('.table-purchases tbody tr.purchaseRow').each(function(index, element){
            $(this).find('label.index').text(a)
            $(this).find('select').attr('name', 'purchases['+ a +'][item_id]')
            $(this).find('input[type="number"]').attr('name', 'purchases['+ a++ +'][qty]')
            sum += parseFloat($(this).find('input[type="number"]').val())   
          });
        }
  
        $('.sumtotal').val(sum.toFixed(2))
      }

      $('body').on('change', 'select', function(event) {
        let self = $(this)
        let tr = $('.table-purchases tbody tr.purchaseRow') 
        let value = checkEQP(self)

        if (value == true) {
          $('tr.purchaseRow').removeClass('active')
          self.parents('tr.purchaseRow').addClass('active')
          $.ajax({
            url: '{{ route('purchase_request.getParts') }}?corpID={{ request()->corpID }}&equipmentID='+ $(this).val() ,
            type: 'GET',
            success: (res) => {
              $('tr[data-parent="'+ self.parents('tr').attr('data-id') +'"]').remove() 
              $(res).insertAfter(self.parents('tr'))
              self.parents('tr').attr('data-id', self.val())
              for (let i = 0; i < tr.length; i++) {
                let num = $('.count').val()
                self.parents('tr').find('td:eq(0)').attr('rowspan', num);
                self.parents('tr').find('td:eq(3)').attr('rowspan', num);
              }
              $('.count').remove()
              indexs()
            }
          });
        } 
      })

      $('body').on('change', 'select.parts', function(event) {
        let self = $(this)
        let tr = $('.table-purchases tbody tr.purchaseRow') 
        let value = checkEQP(self)
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