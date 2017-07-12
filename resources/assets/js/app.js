window.$ = window.jQuery = require('jquery');
toastr = require('toastr');

$(document).ready(function()
{
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
        if($('#select-province').val() != '' && $(element).attr('data-province') != $('#select-province').val())
        {
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

    $('.alert.auto-close').delay(6000).slideUp();
    if($('a[href="' + window.location.hash + '"]').length)
    {
        $('a[href="' + window.location.hash + '"]')[0].click();
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
});