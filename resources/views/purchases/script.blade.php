@section('pageJS')
  <script type="text/javascript">
    (() => {
      let indexLink = "{{ route('purchase_request.index', ['corpID' => request()->corpID]) }}"

      openTablePurchase = (event) => {
        $(event.target).parent('p').slideUp()
        $('.table-purchases').slideDown()
      }

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
        if ($('input[name="eqp_prt"]:checked').val() == 'Part') {
          $.ajax({
            url: '{{ route('purchase_request.getBrands') }}?corpID={{ request()->corpID }}&radio='+ $('input[name="eqp_prt"]:checked').val() ,
            type: 'GET',
            success: (res) => {
              $('.table-purchases tbody').append(res)
              indexs()
            }
          });
          
        } else if ($('input[name="eqp_prt"]:checked').val() == 'Equipment') {
          $.ajax({
            url: '{{ route('purchase_request.getBrands') }}?corpID={{ request()->corpID }}&radio='+ $('input[name="eqp_prt"]:checked').val() ,
            type: 'GET',
            success: (res) => {
              $('.table-purchases tbody').append(res)
              indexs()
            }
          }); 
        }
      })

      $('body').on('change', '.quantity', (event) => {
        let $parent = $(event.target).parents('tr')
        if ($parent.find('td:eq(2) input').val() < 1){
          showAlertMessage('Nothing to save...', 'Alert:')
          $parent.find('td:eq(2) input').val(1)
        }
        
        if ($parent.find('td:eq(1) input').val() < 0){
          showAlertMessage('Nothing to save...', 'Alert:')
          $parent.find('td:eq(1) input').val(0)
        }

        indexs()
      })

      $('.cost-mask').mask("0000000.00", {placeholder: "0000000.00"})

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
            if (checkValue_0() == true) {
              showAlertMessage('Nothing to save...', 'Alert:')
            } else {
              $('.form').submit()
            }
          }
        } else {
          showAlertMessage('Nothing to save...', 'Alert:')
        }
      })

      checkValue_0 = () => {
        let $value = true
        if ($('input[type="radio"]:checked').val() == 'Equipment') {
          $('.table-purchases tbody tr.rowTR').each(function(){
            if($(this).find('td:eq(1) input').val() > 0)
              {
                $value = false
              }   
            });
          return $value
        } else if ($('input[type="radio"]:checked').val() == 'Part') {
          $('.table-purchases tbody tr.purchaseRow').each(function(){
            if($(this).find('td:eq(2) input').val() > 0)
              {
                $value = false
              }   
            });
          return $value
        }
      }

      $('.btn-update').on('click', function() {
        if ($('input[type="radio"]:checked').length > 0 && checkSelect() ) {
          if ($('body tr').hasClass('purchaseRow') == false) {
            showAlertMessage('Nothing to save...', 'Alert:')
          } else {
            if (checkValue_0() == true) {
              showAlertMessage('Nothing to save...', 'Alert:')
            } else {
              $(this).val('update_pr')
            
              $(this).prop("type", "submit")// submit prop
            }
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

      $('.btn-verify').on('click', function() {
        $('.form').submit()
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
            }
          });
      })

      $('.disapproved').on('click', function () {
        let id = $('input[name="requester_id"]').val()
        if($('textarea[name="remarks"]').val()){
          $('.form').submit()
        } else {
          showAlertMessage('Remarks is required...', 'Alert:')
        }
      })

      $('.undoQTY').on('click', function () {
        if ($(this).parents('tr').find('td:eq(0) input').val()) {
          partID = $(this).parents('tr').find('td:eq(0) input').val() 
        } else {
          partID = $(this).parents('tr').find('td:eq(1) input').val()
        }

        $.ajax({
          url: '{{ route('purchase_request.undoQTY') }}?corpID={{ request()->corpID }}&partID='+ partID,
          type: 'GET',
          success: (res) => {
            location.reload()
          }
        });
      })

      $('.undoDelete').on('click', function () {
        if ($(this).parents('tr').find('td:eq(0) input').val()) {
          partID = $(this).parents('tr').find('td:eq(0) input').val() 
        } else {
          partID = $(this).parents('tr').find('td:eq(1) input').val()
        }

        $.ajax({
          url: '{{ route('purchase_request.undoDelete') }}?corpID={{ request()->corpID }}&partID='+ partID,
          type: 'GET',
          success: (res) => {
            location.reload()
          }
        });
      })

      $('input.qty').on('change', function (event) {
        if ($(this).parents('tr').find('td:eq(0) input').val()) {
          partID = $(this).parents('tr').find('td:eq(0) input').val() 
        } else {
          partID = $(this).parents('tr').find('td:eq(1) input').val()
        }
    
        $.ajax({
          url: '{{ route('purchase_request.changeQTY') }}?corpID={{ request()->corpID }}&partID='+ partID +'&qty='+ $(this).val() + '&reason=' + $('.textReason').val(),
          type: 'GET',
          success: (res) => {
            location.reload()
          }
        });    
      })

      $(document).ready(function() {
        if ($('button').hasClass('edit')) {
          $.ajax({
            url: '{{ route('purchase_request.accessPage') }}?corpID={{ request()->corpID }}&id='+ $('input[name="requester_id"]').val(),
            type: 'GET',
            success: (res) => {
            }
          });
          setInterval(function() {
            $.ajax({
              url: '{{ route('purchase_request.accessPage') }}?corpID={{ request()->corpID }}&id='+ $('input[name="requester_id"]').val(),
              type: 'GET',
              success: (res) => {
              }
            });
          }, 10000);
        }

        if ($('button[name="verification"]').val() == 'for_verify' || $('button').hasClass('access_mark')) {
          $.ajax({
            url: '{{ route('purchase_request.accessPage') }}?corpID={{ request()->corpID }}&id='+ $('input[name="requester_id"]').val(),
            type: 'GET',
            success: (res) => {
            }
          });
          setInterval(function() {
            $.ajax({
              url: '{{ route('purchase_request.accessPage') }}?corpID={{ request()->corpID }}&id='+ $('input[name="requester_id"]').val(),
              type: 'GET',
              success: (res) => {
              }
            });
          }, 10000);
        } 
      })

      setDefaultRadiobutton = () => {
        if($('button').hasClass('create')) {
          $('input[value="Equipment"]').prop('checked', true)
          $.ajax({
            url: '{{ route('purchase_request.getBrands') }}?corpID={{ request()->corpID }}&radio=Equipment' ,
            type: 'GET',
            success: (res) => {
              $('.table-purchases tbody').prepend(res)
              indexs()
            }
          });
        }
      }

      setDefaultRadiobutton()

      // $('.edit_verify').on('click', function () {
      //   showAlertMessage('Cannot edit this PR anymore.', 'Request Verified:')
      // })

      $('.delete_request_verify').on('click', function () {
        $(this).val('delete')
        $('.form').submit()        
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
        $('select[name="branch"]').prop('disabled', false)
        $('input[name="description"]').prop('disabled', false)
        $('.before_edt').remove()
        $('.after_edit').css('visibility', '')
      })

      $('body').on('change', 'input:radio', function(event) {
        if ($('input[type="radio"]:checked').val() == 'Part') {
          swal({
            title: "<div class='delete-title'>Warning</div>",
            text:  "<div class='delete-text'>Changing request type will not save your progress.Continue?</strong></div>",
            html:  true,
            customClass: 'swal-wide',
            confirmButtonClass: 'btn-primary',
            confirmButtonText: 'Yes',
            showCancelButton: true,
            closeOnConfirm: true,
            allowEscapeKey: true,
          }, (data) => {
            if(data) {
              $('input[value="Part"]').prop('checked', true)
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
            } 
          });
          $('input[value="Equipment"]').prop('checked', true)
        } else if ($('input[type="radio"]:checked').val() == 'Equipment') {
          swal({
            title: "<div class='delete-title'>Warning</div>",
            text:  "<div class='delete-text'>Changing request type will not save your progress.Continue?</strong></div>",
            html:  true,
            customClass: 'swal-wide',
            confirmButtonClass: 'btn-primary',
            confirmButtonText: 'Yes',
            showCancelButton: true,
            closeOnConfirm: true,
            allowEscapeKey: true,
          }, (data) => {
            if(data) {
              $('input[value="Equipment"]').prop('checked', true)
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
            } 
          });
          $('input[value="Part"]').prop('checked', true)
        }
      })

      checkEQP = (self) => {
        let $value = true
        $('.table-purchases tbody tr.purchaseRow').each(function(){
        if($(this).attr('data-id') == self.val())
          {
            showAlertMessage('There should be no duplicate items in a PR.', 'Note for create request:')
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
        $('.table-purchases tbody tr.purchaseRow.active').each(function(){
          if($(this).find('select').val() == self.val()) {
            showAlertMessage('There should be no duplicate items in a PR.', 'Note for create request:')
            self.parents('tr').find('select option[value=""]').attr("selected",false)
            self.parents('tr').find('select option[value=""]').attr("selected",true)
          }   
        });
      }
      
      $('.for-verification').on('click', function() {
        $('.form').submit()
      })

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
        
        if ($('input[type="radio"]:checked').val() == 'Equipment') {
          $('.table-purchases tbody tr.purchaseRow').each(function(index, element){
            $(this).find('label.index').text(a)
            $(this).find('select').attr('name', 'purchases['+ a++ +'][item_id]')
          });
          
          $('.table-purchases tbody tr.rowTR').each(function(index, element){
            sum += parseFloat($(this).find('input[type="number"]').val())
          });
        }

        if ($('input[type="radio"]:checked').val() == 'Part') {
          $('.table-purchases tbody tr.purchaseRow').each(function(index, element){
            $(this).find('label.index').text(a)
            $(this).find('select').attr('name', 'purchases['+ a +'][item_id]')
            $(this).find('input[type="number"]').attr('name', 'purchases['+ a++ +'][qty]')
            sum += parseFloat($(this).find('input[type="number"]').val())   
          });
        }
  
        $('.sumtotal').val(sum.toFixed(2))
      }
      indexs()

      $('body').on('change', 'select.brand', function(event) {
        let self = $(this)
        let tr = $('.table-purchases tbody tr.purchaseRow') 
        let value = checkEQP(self)

        if (value == true) {
          $('tr.purchaseRow').removeClass('active')
          self.parents('tr.purchaseRow').addClass('active')
          $.ajax({
            url: '{{ route('purchase_request.getParts') }}?corpID={{ request()->corpID }}&EQP_PRT='+ $('input[name="eqp_prt"]:checked').val() +'&equipmentID='+ $(this).val() ,
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
    })()
  </script>
@endsection