<div class="col-md-12 combine_branch">
	<input type="hidden" />
    <div class="panel panel-default">
        <div class="panel-heading">Provinces</div>
        <div class="form-group{{ $errors->has('provinces_name') ? ' has-error' : '' }}">
            <div class="panel-body">
                <div class="col-md-5">
                    <table id="list_provinces" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead> 
                            <tr>
                                <th>Name</th>
                                <th><input class="selectall area_user" type="checkbox" name="selectall" id="select_all">Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($province as $key=>$det)
                                <tr>
                                    <td>{{ $det->Province }}</td>
                                    <td class="text-center"><input onclick="GetSelectedvalues()" class="select province_id" type="checkbox" name="provience_id[]" value="{{$det->Prov_ID}}"
                                    <?php 
                                        if(isset($province_ids)){ echo in_array($det->Prov_ID, $province_ids) ? "checked" : '' ;
                                        }
                                    ?>
                                    ></td>
                                </tr>  
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>         
    </div>
</div>

<script>
$(document).ready(function() {
    $("#select_all").change(function(){
        $('.grp_append').html('');
        if(this.checked){ 
            $(".select").each(function(){
                this.checked=true;
            });
            GetSelectedvalues();              
        }else{
            $(".select").each(function(){
                this.checked=false;
                $('.grp_append').html('');
                $('.label_remittance').css("display", "none");
            })              
        }
    });
    if($('#slctd_grp_ids').val() != ''){
        var g_id = $('#slctd_grp_ids').val();
        arr_grp_id = g_id.split(',');
        GetSelectedvalues();
    }else{
        arr_grp_id = [""];
        GetSelectedvalues();
    }
});
function GetSelectedvalues() {
    $('.grp_append').html('');
    $('.label_remittance').css("display", "none");
    var _token = $("meta[name='csrf-token']").attr("content");
    var ids = []
    $("input.province_id:checked").each(function ()
    {
        ids.push(parseInt($(this).val()));
    });
    $.ajax({
        url: ajax_url+'/'+ 'get_provinces_ids',
        type: "POST",
        data: {_token,ids },
        dataType: 'JSON',
        success: function(response){    
            if((response).length){
                $('.label_remittance').css("display", "block");
                $.each(response, function(k,v){
                    grp = v.group_ID.toString();
                    if ($.inArray(grp,arr_grp_id) !== -1) {
                        $('.grp_append').append('<div class="col-md-2 branch_assign"><input id="group_name" type="checkbox" name="group[]" value="'+v.group_ID+'" class="area_user grp_select" checked>'+v.desc+'</div>'); 
                    }else{
                        $('.grp_append').append('<div class="col-md-2 branch_assign"><input id="group_name" type="checkbox" name="group[]" value="'+v.group_ID+'" class="area_user grp_select">'+v.desc+'</div>'); 
                    }
                });
            }
        }
    });
}
</script>