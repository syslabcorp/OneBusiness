@section('pageJS')
  <script type="text/javascript">
    (() => {
      let indexLink = "{{ route('purchase_request.index', ['corpID' => request()->corpID]) }}"

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
      $('.table-purchases').css('min-height', '230px')

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
              $('.table-purchases tbody').append(res)
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
              $('.table-purchases tbody').append(res)
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

        // if ($parent.find('td:eq(5) input').val() < 1){
        //   showAlertMessage('Duplicate entry detected...', 'Item Entry Error...')
        //   $parent.find('td:eq(5) input').val(1)
        // }
        indexs()
      })

      // totalCost = () => {
      //   let $rows = $('.table-purchases tbody tr.purchaseRow')
      //   let total = 0
     
      //   for(let i = 0; i < $rows.length; i++) {
      //       let $tr = $($rows[i])
      //       if ($.isNumeric($tr.find('td input.total').val())) {
      //         total += parseFloat($tr.find('td input.total').val())
      //       }
      //     }
         
      //   $('.sumtotal').val(total.toFixed(2))
      // }
      // totalCost()
      // $('.cost-mask').mask("###,##0,00", {reverse: true})
      $('.cost-mask').mask("0000000.00", {placeholder: "0000000.00"})
      
      // totals = () => {
      //   console.log(1)
      //   let $rows = $('.table-purchases tbody tr.purchaseRow')
      //   let sumtotal = 0
      //   for(let i = 0; i < $rows.length; i++) {
      //     let $tr = $($rows[i])
      //     if ($.isNumeric($tr.find('td input.cost').val())) {
      //       let total = parseFloat($tr.find('td input.qty').val()*parseFloat($tr.find('td input.cost').val()))
      //       sumtotal += total
      //       $tr.find('td input.total').val(total.toFixed(2))
      //     }
      //   }
      //   $('.sumtotal').val(sumtotal.toFixed(2))
      // }

      // totals()

      totalQty = () => {
        let $rows = $('.table-purchases tbody tr')
        let total = 0
      
        for(let i = 0; i < $rows.length; i++) {
          let $tr = $($rows[i])
          if ($.isNumeric($tr.find('td input.qty').val())) {
            total += parseFloat($tr.find('td input.qty').val())
          }
        }
        
        $('.sumtotal').val(total.toFixed(2))
      }

      totalQty()

      totalCostMarkForPO = () => {
        let $rows = $('.table-purchases tbody tr')
        let totalCost = 0
        
        for(let i = 0; i < $rows.length; i++) {
          let $tr = $($rows[i])
          if ($tr.find('td input.cost').val()) {
            if ($.isNumeric($tr.find('td input.cost').val())) {
              let total = parseFloat($tr.find('td input.qty').val())*parseFloat($tr.find('td input.cost').val())
              $tr.find('td input.total').val(total.toFixed(2))
              totalCost += total
            }
          }
        }

        $('.sumtotalCost').val(totalCost.toFixed(2))
      }
      totalCostMarkForPO()

      $('.cost').on('keyup', function () {
        let total = $(this).parents('tr').find('input.cost').val()*parseFloat($(this).parents('tr').find('input.qty').val())
        $(this).parents('tr').find('input.total').val(total.toFixed(2))
        totalCostMarkForPO()
      })

      $('.qty').on('keyup', function () {
        let total = $(this).parents('tr').find('input.cost').val()*parseFloat($(this).parents('tr').find('input.qty').val())
        $(this).parents('tr').find('input.total').val(total.toFixed(2))
        // totals()
      })

      $('.btn-save').on('click', function() {
        if ($('input[type="radio"]:checked').length > 0 && checkSelect() ) {
          if ($('body tr').hasClass('purchaseRow') == false) {
            showAlertMessage('Nothing to save...', 'Alert:')
          } else {
            $('.form').submit()
          }
        } else {
          showAlertMessage('Nothing to save...', 'Alert:')
        }
      })

      $('.btn-markforpo').on('click', function() {
        if (checkSelect()) {
          $('.form').submit()
        } else {
          showAlertMessage('Not checked', 'Item Entry Error...')
        }
      })
      
      $('.access_delete').on('click', function() {
        if ($(this).parents('tr').find('td:eq(0) input').val()) {
          partID = $(this).parents('tr').find('td:eq(0) input').val() 
        } else {
          partID = $(this).parents('tr').find('td:eq(1) input').val()
        }
      
        $('.index_pr').text($(this).parents('tr').find('td label.index').text())
        $('.pr_id').val(partID)
      })

      $('.delete_row').on('click', function (){
        let partID = $('.pr_id').val()
        let reason = $('.reason').val()
        $.ajax({
            url: '{{ route('purchase_request.removePart') }}?corpID={{ request()->corpID }}&partID='+  partID + '&reason=' + reason,
            type: 'GET',
            success: (res) => {
              location.reload()
              // indexs()
            }
          });
      })

      $('.disapproved').on('click', function () {
        $.ajax({
            url: '{{ route('purchase_request.disapproved') }}?corpID={{ request()->corpID }}&requester_id='+ $('input[name="requester_id"]').val() + '&reasons=' + $('.reasons').val(),
            type: 'GET',
            success: (res) => {
              if (res['success'] == true) {
                window.location = indexLink 
              }
              // indexs()
            }
          });
      })

      $('.delete_request_verify').on('click', function () {
        let purchaseID = $('input[name="requester_id"]').val()
        $.ajax({
            url: '{{ route('purchase_request.destroyPurchaseRequest') }}?corpID={{ request()->corpID }}&purchaseID='+ purchaseID,
            type: 'GET',
            success: (res) => {
              if (res['success'] == true) {
                window.location = indexLink 
              }
            }
          });
      })

      $('.delete_part').on('click', function () {
        partID = $(this).parents('tr').find('td:eq(0) input').val()
        $.ajax({
            url: '{{ route('purchase_request.destroyPart') }}?corpID={{ request()->corpID }}&partID='+  partID,
            type: 'GET',
            success: (res) => {
              location.reload()
            }
          });
      })

      $('.access_mark').on('click', function () {
        $('.pr_id').text($('input[name="requester_id"]').val())
        $('.approve_id').text($('input[name="requester_id"]').val())
      })

      $('.edit').on('click', function () {
        $('.btnAddRow').prop('disabled', false)
        $('.btnRemoveRow').prop('disabled', false)
        $('.quantity').prop('readonly', false)
        $('select.brand').prop('disabled', false)
        $('.before_edt').remove()
        $('.after_edit').css('visibility', '')
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
            showAlertMessage('There should be no duplicate items in a PR.', 'Note for create request:')
            // self.parents('tr').remove()
            self.parents('tr').find('select option[value=""]').attr("selected",false)
            self.parents('tr').find('select option[value=""]').attr("selected",true)
            self.parents('tr').find('td:eq(0)').attr("rowspan",'')
            self.parents('tr').find('td:eq(3)').attr("rowspan",'')
            $('tr[data-parent="'+ self.parents('tr').attr('data-id') +'"]').remove()
            $value = false
            return false
          }   
        });
      
        return $value
      }

      checkPRT = (self) => {
        // console.log($('.table-purchases tbody tr.purchaseRow').length)
      
        $('.table-purchases tbody tr.purchaseRow.active').each(function(){
          if($(this).find('select').val() == self.val()) {
            showAlertMessage('There should be no duplicate items in a PR.', 'Note for create request:')
            self.parents('tr').find('select option[value=""]').attr("selected",false)
            self.parents('tr').find('select option[value=""]').attr("selected",true)
          }   
        });
      }
  

      checkSelect = () => {
        let $value = true
        $('.table-purchases tbody tr.purchaseRow').each(function(){
          if($(this).find('select').val() == '')
            {
              showAlertMessage('Not checked', 'Item Entry Error...')
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

      $('body').on('change', 'select.brand', function(event) {
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

      $('body').on('change', 'select.parts', function () {
        let self = $(this)
        $('tr.purchaseRow').removeClass('active')
        $('tr.purchaseRow').addClass('active')
        self.parents('tr.purchaseRow').removeClass('active')
        checkPRT(self)
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