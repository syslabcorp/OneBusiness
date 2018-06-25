<script type="text/javascript">
  (function() {
    $('.btn-edit').click(function(event) {
      if(!$('.tab-pane.benefit-tab').hasClass('active')) {
        return;
      }

      let parentElement = $('.benefit-tab.active')
      
      $('.tab-pane.active .table-wages .form-control').prop('readonly', false)
      $(this).css('display', 'none')
      $('.btn-save').css('display', 'inline-block')
      $('.btn-cancel').css('display', 'inline-block')
      $('.nav.nav-tabs li:not(.active)').css('display', 'none')
      parentElement.find('input[type="radio"], input[type="checkbox"]').attr('onclick', '')
      parentElement.find('.listDeductions').css('display', 'none')
      parentElement.find('input[name="description"]').css('display', 'block')
      parentElement.find('.form-control').prop('disabled', false)
      parentElement.find('.btn-reset, .btn-add, .btn-edit-row, .btn-remove-row').prop('disabled', false)
      parentElement.find('input[name="type"]').change()
      parentElement.find('input[name="type"]:checked').change()
    })

    $('.benefit-tab input[name="type"]').change(function(event) {
      let parentElement = $(this).parents('.benefit-tab')
      let checkedVal = parentElement.find('input[name="type"]:checked').val()

      parentElement.find('input[name="type"]').closest('.rown').find('.form-control').prop('disabled', true)
      parentElement.find('input[name="type"]:checked').closest('.rown').find('.form-control').prop('disabled', false)

      if(checkedVal == 3 || checkedVal == 4 || checkedVal == 2) {
        parentElement.find('.has-group-line .form-control').prop('disabled', false)
      }else {
        parentElement.find('.has-group-line .form-control').prop('disabled', true)
      }
      if(checkedVal == 4) {
        if($('.table-wages').is(':hidden')) {
          parentElement.find('.table-wages').slideDown(400);
        }
      }else {
        parentElement.find('.table-wages').slideUp(0);
      }
    })
  })()
</script>