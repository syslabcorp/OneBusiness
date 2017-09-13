<div class="col-md-12 combine_branch">
    <div class="panel panel-default">
        <div class="panel-heading">Branches</div>
        <div class="form-group{{ $errors->has('cities_name') ? ' has-error' : '' }}">
            <div class="panel-body">
                <div class="col-md-7">
                    <table id="list_cities" class="table table-striped table-bordered" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th class="text-center">Province</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Branch Name</th>
                                <th><input class="selectall area_user" type="checkbox" name="selectall" id="select_all">Select</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <?php $old_prov_id = 0; 
                                  $old_city_id = 0;  
                            ?>
                            @foreach($branches as $key=>$det)
                                <?php $count_city = count($city_b_array[$det->City_ID]); 
                                      $count_prov = count($prov_b_array[$det->Prov_ID]); 
                                ?>
                                <tr>
                                    <?php if($det->Prov_ID != $old_prov_id) { ?>
                                    <td rowspan="{{$count_prov}}" class="text-center">{{$det->Province}}</td>
                                    <?php  } ?>
                                    <?php if($det->City_ID != $old_city_id) { ?>
                                    <td rowspan="{{$count_city}}">{{$det->city}}</td>
                                    <?php  } ?>
                                    <td>{{ $det->ShortName }}</td>
                                    <td class="text-center"><input class="select branch_id" onclick="GetSelectedvalues()" type="checkbox" name="branch_id[]" value="{{$det->Branch }}"
                                    <?php 
                                        if(isset($Branch_ids)){ echo in_array($det->Branch, $Branch_ids) ? "checked" : '' ;
                                        }
                                    ?>
                                    ></td>
                                </tr>
                                <?php $old_prov_id = $det->Prov_ID;
                                $old_city_id = $det->City_ID; ?>
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
    $("input.branch_id:checked").each(function ()
    {
        ids.push(parseInt($(this).val()));
    });
    $.ajax({
        url: ajax_url+'/'+ 'get_branch_ids',
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