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
});