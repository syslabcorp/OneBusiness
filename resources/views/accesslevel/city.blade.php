<div class="col-md-12 combine_branch">
	<input type="hidden" />
    <div class="panel panel-default">
        <div class="panel-heading">Cities</div>
        <div class="form-group{{ $errors->has('cities_name') ? ' has-error' : '' }}">
            <div class="panel-body">
                <div class="col-md-5">
                    <table id="list_cities" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead> 
                            <tr>
                                <th class="text-center">Province</th>
                                <th class="text-center">City</th>
                                <th><input class="selectall area_user" type="checkbox" name="selectall" id="select_all">Select</th>
                            </tr>
                        </thead>
                        <tbody> 
                        	<?php $old_prov_id = 0; ?>
                            @foreach($cities as $key=>$det)
                            	<?php $count = count($cp_aaray[$det->Prov_ID]); 
                            		  $prov_name = $province[$det->Prov_ID]; ?>
                                <tr>
                                <?php 
                                	if($cp_aaray[$det->Prov_ID] != $old_prov_id) { ?>

                                    <td rowspan="{{$count}}">{{$prov_name}}</td>
                                    <?php $old_prov_id = $cp_aaray[$det->Prov_ID]; } ?>
                                    <td>{{$det->City}}</td>
                                    <td class="text-center"><input class="select city_id" onclick="GetSelectedvalues()" type="checkbox" name="city_id[]" value="{{$det->City_ID}}"
                                    <?php 
                                        if(isset($city_ids)){ echo in_array($det->City_ID, $city_ids) ? "checked" : '' ;
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
    $("input.city_id:checked").each(function ()
    {
        ids.push(parseInt($(this).val()));
    });
    $.ajax({
        url: ajax_url+'/'+ 'get_city_ids',
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