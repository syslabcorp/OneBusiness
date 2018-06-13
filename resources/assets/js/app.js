window.$ = window.jQuery = require('jquery');
toastr = require('toastr');

$(document).ready(function()
{
  $('#rate-template-name').change(function(event) {
    window.location = $(this).find('option:selected').attr('data-href');
  });

  $('.color-picker').ColorPicker({
    onChange: function(hsb, hex, rgb) {
      $('.color-picker').css("background", "#" + hex);
      $('input[name="Color"]').val("#" + hex);
    }
  });

  $('#assign-rate-template input[value="all"]').change(function(event) {
    if(event.target.checked) {
      $('#assign-rate-template input[name="days[]"]').each(function(index, element) {
        if($(element).attr('value') != 'all') {
          $(element).prop("checked", true);
        }
      });
    } else {
      $('#assign-rate-template input[name="days[]"]').each(function(index, element) {
        if($(element).attr('value') != 'all') {
          $(element).prop("checked", false);
        }
      });
    }
  });

    $('#select-province').on('change', function(event)
    {
        var parent = $(this);
        $('#select-city option').each(function(index, element)
        {
            if(parent.val() != '' && $(element).attr('data-province') != parent.val())
            {
                $(element).css('display', 'none');
            }else
            {
                $(element).css('display', 'block');
            }
        });
    });

    $('#select-city option').each(function(index, element)
    {
        if ($('#select-province').val() == "" || $('#select-province').val() != '' && $(element).attr('data-province') != $('#select-province').val()) {
            $(element).css('display', 'none');
        }else
        {
            $(element).css('display', 'block');
        }
    });

    $('#branch-select, .branch-select').on('change', function(event)
    {
        $('#station-select, .station-select').val('');
        var parent = $(this);
        $('#station-select option, .station-select option').each(function(index, element)
        {
            if(parent.val() != '' && $(element).attr('data-branch') != parent.val())
            {
                $(element).css('display', 'none');
            }else
            {
                $(element).css('display', 'block');
            }
        });
    });

    $('#station-select option, .station-select option').each(function(index, element)
    {
        $(element).css('display', 'none');
    });

    $('.alert:not(.no-close)').delay(3000).slideUp(400);
    if ($('a[href="' + window.location.hash + '"]').length) {
        $('a[href="' + window.location.hash + '"]')[0].click();
    }else {
    if($('a[href="#branch-details"]').length) {
      $('a[href="#branch-details"]')[0].click();
        }
        if($('a[href="#template"]').length) {
      $('a[href="#template"]')[0].click();
    }
  }

    $('.list-macs tbody tr').click(function(event)
    {
        $('.list-macs tbody tr').removeClass('active');
        $(this).addClass('active');
    });

    $('.list-macs td.ip-address').click(function(event)
    {
        $(this).toggleClass('active');
    })

    $('#assign-ip').click(function(event)
    {
        if($('.list-macs td.ip-address.active').length != 0)
        {
            $('#assign-modal').modal('show');
        }else
        {
            toastr.error("You must select at least one IP Address field");
        }
    });

    $('#assign-modal .btn').click(function(event)
    {
        var ipAddress = $('#assign-modal input.form-control').val();
        if(ipAddress.match("[^0-9\.]") || ipAddress.split(".").length != 4)
        {
            toastr.error("IP Address format is invalid");
        }else
        {
            var partIps = ipAddress.split(".");
            $('.list-macs td.ip-address.active .form-control').each(function(index, element)
            {
                $(element).attr("value", partIps.join(".")).change();
                $(element).parent('td').removeClass('active');
                partIps[3]++;
                for(var i = 0; i < 4; i++)
                {
                    if(partIps[i] > 255)
                    {
                        if(i != 0)
                        {
                            partIps[i] = 1;
                            partIps[i - 1]++;
                        }else
                        {
                            partIps[0] = 1;
                        }
                        
                    }
                }
            });
            $('#assign-modal').modal('hide');
        }
    });

    $('#assign-ip-range').click(function(event)
    {
        $('#assign-range-modal').modal('show');
    });

    $('#assign-range-modal .btn').click(function(event){
        var ipAddress = $('#assign-range-modal input[name="IP_Addr"]').val();
        var inputRange = $('#assign-range-modal input[name="range"]').val();
        inputRange = inputRange.trim();
        if( inputRange == "" || ipAddress == "") {
                toastr.error("Field Range and Start Ip Address can't be blank");
                return;
            }
        
        if(ipAddress.match("[^0-9\.]") || ipAddress.split(".").length != 4)
        {
            return toastr.error("IP Address format is invalid");
        }

        inputRange = inputRange.replace(/\s/g, "");

        if(inputRange.match("[^0-9\,\-]"))
        {
            return toastr.error("Input Range format is invalid");
        }

        inputRange = inputRange.split(",");
        var fields = [];

        for(var i = 0; i < inputRange.length; i++)
        {
            if(inputRange[i].match(/\-/))
            {
                var numbers = inputRange[i].split("-");
                for(var number = numbers[0]; number <= numbers[1]; number++)
                {
                    if(fields.indexOf(number*1) == -1)
                    {
                        fields.push(number*1);
                    }
                }
            }else
            {
                if(fields.indexOf(inputRange[i]*1) == -1)
                {
                    fields.push(inputRange[i]*1);
                }
            }
        }

        var partIps = ipAddress.split(".");
        for(var index = 0; index < fields.length; index++)
        {
            var element = $('.list-macs td.ip-address:eq(' + (fields[index] - 1) +  ') .form-control');
            if(element.length != 0)
            {
                element.attr("value", partIps.join(".")).change();
                element.parent('td').removeClass('active');
                partIps[3]++;
                for(var i = 0; i < 4; i++)
                {
                    if(partIps[i] > 255)
                    {
                        if(i != 0)
                        {
                            partIps[i] = 1;
                            partIps[i - 1]++;
                        }else
                        {
                            partIps[0] = 1;
                        }
                    }
                }
            }
        }

        $('#assign-range-modal').modal('hide');
    });

    $('#transfer-mac').click(function(event)
    {
        if($('.list-macs tbody tr.active').length != 0)
        {
            $('#transfer-modal input[name="mac_id"]').val($('.list-macs tbody tr.active').attr('data-id'));
            $('#transfer-modal').modal('show');
        }else
        {
            toastr.error("You must select station");
        }
    });

    $('.list-macs input[type="checkbox"], .list-macs input[type="text"]').change(function(event)
    {
        $(this).parents('tr').find('input[type="hidden"]').val(1);
    });

    $('#swap-station').click(function(event)
    {
        if($('.list-macs tbody tr.active').length != 0)
        {
            $('#swap-station-modal input[name="mac_id"]').val($('.list-macs tbody tr.active').attr('data-id'));
            $('#branch-select').val('');
            $('#station-select, .station-select').val('');
            $('#station-select option, .station-select option').each(function(index, element)
            {
                $(element).css('display', 'none');
            });
            $('#swap-station-modal').modal('show');
        }else
        {
            toastr.error("You must select station");
        }
    });

    $('#filter-branchs').change(function(event) {
        $(this).parents('form').submit();
    });

    $('.rate-page .cancel-selection').click(function(event) {
      $('.rate-page .table .selected').removeClass('selected');
      $('.box-assign:not(.nohide)').slideUp(400);
      $(this).slideUp(0);
    });

    $('.rate-page .table .form-control').click(function(event) {
      if(event.ctrlKey) {
        $(this).parents('tr').each(function(index, element) {
          $(this).toggleClass('selected');
        });
      }else if(event.shiftKey) {
        if($(this).parents("table").find('tr.selected').length == 0) {
          $(this).parents('tr').addClass('selected');
        }else {
          var startIndex = $(this).parents("table").find('tr.selected').index();
          var endIndex = $(this).parents('tr').index();
          $(this).parents("table").find('tr.selected').removeClass('selected');
          if(startIndex == endIndex) {
            $(this).parents('tr').removeClass('selected');
          }else {
            if(endIndex < startIndex) {
              var temp = startIndex;

              startIndex = endIndex;
              endIndex = temp;
            }
            for(startIndex; startIndex <= endIndex; startIndex++) {
                $(this).parents("table").find('tbody tr:eq(' + startIndex + ')').addClass('selected');
            }
          }
        }
      }else {
        $(this).select();
      }

      if($('.rate-page .table tr.selected').length != 0) {
        $('.rate-page .cancel-selection').slideDown(0);
        $('.rate-page .box-assign:not(.nohide)').slideDown(500);
      }else {
        $('.rate-page .cancel-selection').slideUp(0);
        $('.rate-page .box-assign:not(.nohide)').slideUp(500);
      }
    });

    $('.rate-page .table .form-control').keydown(function(event) {
      var column = $(this).parents("td").index();
      var row = $(this).parents("tr").index();
      switch(event.which) {
        case 37:
          column -= 1;
          break;
        case 38:
          row -= 1;
          break;
        case 39:
          column += 1;
          break;
        case 40:
          row += 1;
          break;
        default:
          return;
          break;
      }
      $(this).parents('.table').find("tbody tr:eq(" + row + ") td:eq(" + column + ") .form-control").click();
    });

    $('.rate-page .box-assign .btn').click(function(event) {
      $('.rate-page .btn-save').removeClass("not-apply");
      var invalid = false;
      $('.rate-page .box-assign td .form-control').each(function(index, element) {
        if($(this).val().match(/[^0-9\.]/)) {
          invalid = true;
        }
      });
      if(invalid) {
        toastr.error("Invalid input. Please enter a number.");
        return;
      }
      $('.rate-page .table tr.selected').each(function(index) {
        $(this).find('.form-control').each(function(key, element) {
          if($('.rate-page .box-assign td:eq(' + (key + 1) + ') .form-control').val()) {
            $(this).val($('.rate-page .box-assign td:eq(' + (key + 1) + ') .form-control').val());
          }
        });
      });
    });

    $('.rate-page .box-assign .form-control').on("change", function(event) {
      $('.rate-page .btn-save').addClass("not-apply");
    });

    $('.rate-page .btn-save').click(function(event) {
      if($(this).hasClass('not-apply')) {
        toastr.error("Please apply values first before saving");
        event.preventDefault();
      }
    })


    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": false,
        "positionClass": "toast-bottom-left",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }

    // Rate Template
    $("#change_minimum").click(function(event) {
      if(event.target.checked) {
        $("input[name='MinimumTime']").prop("disabled", false);
      }else {
        $("input[name='MinimumTime']").val("").change();
        $("input[name='MinimumTime']").prop("disabled", true);
      }
    })

    $('body').on('change', '.changePageCompany', (event) => {
        let search = location.search.replace(/&corpID=[0-9]*/g, '')
        search += (search ? '&corpID=' : '?corpID=') + event.target.value

        window.location = location.pathname + search
    })
});



