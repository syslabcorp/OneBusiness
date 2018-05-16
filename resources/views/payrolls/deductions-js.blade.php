<script type="text/javascript">
  (function() {
    $('.deductions-tab .btn-edit').click(function(event) {
      let parentElement = $(this).parents('.deductions-tab')

      $(this).css('display', 'none')
      parentElement.find('.btn-save').css('display', 'inline-block')
      parentElement.find('input[type="radio"], input[type="checkbox"]').attr('onclick', '')
      parentElement.find('.listDeductions').css('display', 'none')
      parentElement.find('input[name="description"]').css('display', 'block')
      parentElement.find('.form-control').prop('disabled', false)
      parentElement.find('.btn-reset, .btn-add, .btn-edit-row, .btn-remove-row').prop('disabled', false)
      parentElement.find('input[name="type"]').change()
      parentElement.find('input[name="type"]:checked').change()
    })

    $('.deductions-tab input[name="type"]').change(function(event) {
      let parentElement = $(this).parents('.deductions-tab')
      let checkedVal = parentElement.find('input[name="type"]:checked').val()

      parentElement.find('input[name="type"]').closest('.rown').find('.form-control').prop('disabled', true)
      parentElement.find('input[name="type"]:checked').closest('.rown').find('.form-control').prop('disabled', false)

      if(checkedVal == 2) {
        parentElement.find('input[name="type"][value="4"]').closest('.rown').find('.form-control').prop('disabled', false)
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