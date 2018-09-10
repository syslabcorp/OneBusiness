@section('pageJS')
  <script type="text/javascript">
    (() => {
      openTablePart = (event) => {
        $(event.target).parent('p').slideUp()
        $('.table-parts').slideDown()
      }

      $('#equipmentDetail select[name="branch"]').change((event) => {
        updateDepartmentsSelect()
      })

      updateDepartmentsSelect = () => {
        let branchID = $('#equipmentDetail select[name="branch"]').val()
        $('select[name="dept_id"] option').css('display', 'none')
        $('select[name="jo_dept"] option').css('display', 'none')

        $.ajax({
          type: 'GET',
          url: '{{ url('/') }}/api/v1/branches/' + branchID + '/depts?corpID={{ request()->corpID }}',
          success: (res) => {
            for(let i = 0; i < res.depts.length; i++) {
              $('select[name="dept_id"] option[value="' + res.depts[i] + '"]').css('display', 'block')
              $('select[name="jo_dept"] option[value="' + res.depts[i] + '"]').css('display', 'block')
            }
            
            $('select[name="dept_id"] option[value="' + res.depts[0] + '"]').prop('selected', true)
            $('select[name="jo_dept"] option[value="' + res.depts[0] + '"]').prop('selected', true)

          },
          error: (res) => {

          }
        })
      }

      updateDepartmentsSelect()

      $('.editEquipment .form-control').prop('disabled', true)
    })()
  </script>
@endsection