$(function(){
    var isAdmin = $('option:selected', this).attr('is-admin');
    var previous,previousadmin;
    
    $('.template_name').focus(function(e){
       previous = $(this).val(); 
       previousadmin = $('option:selected').attr('is-admin');
    });
    if(isAdmin == 1){ 
        $('.label-superAdmin').html('');
        $('.label-superAdmin').append('<label>This is a Super User Template</label>'); 
    }else{
        $('.label-superAdmin').html('');
    }
    $("#userform").validate();  
    $(".template_name").change(function(){ 
        var userid = $("#userid").val();
        var isAdmin = $('option:selected', this).attr('is-admin');
        var value_areatype = $('.area_type:checked').val();
        var currentadmin = $('option:selected').attr('is-admin');
        var currentval = $(this).val();
        if(previousadmin == 1 && currentadmin == 0){
            get_area_type(value_areatype,userid);
        }
        previous = $(this).val();
        previousadmin = $('option:selected').attr('is-admin');
        if(isAdmin == 1){ 
            $('.label-superAdmin').html('');
            $('.label-superAdmin').append('<label>This is a Super User Template</label>');   
            if(value_areatype == "PR"){
                $(".select").each(function(){
                    this.checked=true;
                    this.disabled=true;
                });
                $('#append_areatype').html('');
                $("input.province_id:checked").each(function (){  
                    $('#append_areatype').append('<input type ="hidden" type="checkbox" name="provience_id[]" value="'+($(this).val())+'">');
                });
                GetSelectedvalues(); 
                $(".selectall").attr("checked", true);
                $(".selectall").attr("disabled", true);
                
            }else if(value_areatype == "BR"){
                $(".select").each(function(){
                    this.checked=true;
                    this.disabled=true;
                });
                $('#append_areatype').html('');
                $("input.branch_id:checked").each(function (){   
                    $('#append_areatype').append('<input type ="hidden" type="checkbox" name="branch_id[]" value="'+($(this).val())+'">');
                });
                GetSelectedvalues(); 
                $(".selectall").attr("checked", true);
                $(".selectall").attr("disabled", true);
                
            }else if(value_areatype == "CT"){
                $(".select").each(function(){
                    this.checked=true;
                    this.disabled=true;
                });
                $('#append_areatype').html('');
                $("input.city_id:checked").each(function (){   
                    $('#append_areatype').append('<input type ="hidden" type="checkbox" name="city_id[]" value="'+($(this).val())+'">');
                });
                GetSelectedvalues();
                $(".selectall").attr("checked", true); 
                $(".selectall").attr("disabled", true);
                
            }
        }else{
             //$('input:checkbox').removeAttr('checked');
             $('input:checkbox').removeAttr('disabled');
             //$('.grp_append').html('');
             $('.label_remittance').css("display", "none");
             $('#append_areatype').html('');
             $('#appendall_group').html('');
             $('.label-superAdmin').html(''); 
        }
    }); 

});
function get_area_type(value,userid){
    var _token = $("meta[name='csrf-token']").attr("content");
    var value1 = value;
    if (value == "CT") {
        var activevalue = "city";
    }else if(value == "BR"){
        var activevalue = "branch";
    }else{
        var activevalue = "provinces";
    }
    $.ajax({
        url: ajax_url+'/'+ activevalue +'/'+ userid ,
        type: "POST",
        data: {_token},
        success: function(response){
          $('#branch_assignment').html(response);
        }
    });
}
$(document).on("click", ".area_type", function(){
    var userid = $("#userid").val();
    var value = $(this).val();
    get_area_type(value,userid);
});

$(function(){
    var value = $('.area_type:checked').val();
    var userid = $("#userid").val();
    if(typeof(value) === 'undefined'){
        value = 'PR';
        $(":radio[value=PR]").attr("checked","true");
        get_area_type(value,userid);
    }else{
        get_area_type(value,userid);
    }
});
