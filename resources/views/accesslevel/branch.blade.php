<div class="col-md-12 combine_branch">
	<input type="hidden" />
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
                                <th class="text-center">Corporation Name</th>
                                <th class="text-center">Branch Name</th>
                                <th><input class="selectall area_user" type="checkbox" name="selectall" id="select_all">Select</th>
                            </tr>
                        </thead>
                        <tbody> 
                            <?php $old_prov_id = 0; 
                                  $old_city_id = 0;  
                                  $old_corp_id = 0;
                            ?>
                            @foreach($branches as $key=>$det)
                                <?php $count_city = count($city_b_array[$det->City_ID]); 
                                      $count_prov = count($prov_b_array[$det->Prov_ID]); 
                                      $count_corp = count($corp_b_array[$det->City_ID][$det->corp_id]); 
                                ?>
                                <tr>
                                    <?php if($det->Prov_ID != $old_prov_id) { ?>
                                    <td rowspan="{{$count_prov}}" class="text-center">{{$det->Province}}</td>
                                    <?php  } ?>
                                    <?php if($det->City_ID != $old_city_id) { ?>
                                    <td rowspan="{{$count_city}}">{{$det->city}}</td>
                                    <?php  } ?>
                                    <?php if($det->corp_id != $old_corp_id || ($det->City_ID != $old_city_id)) { ?>
                                    <td rowspan="{{$count_corp}}">{{ $det->corp_name }}</td>
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
                                $old_city_id = $det->City_ID; 
                                $old_corp_id = $det->corp_id; ?>
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
    var isAdmin = $('#temp_name option:selected').attr('is-admin');
    if($('#slctd_grp_ids').val() != ''){
        var g_id = $('#slctd_grp_ids').val();
        arr_grp_id = g_id.split(',');
        if(isAdmin == 1){
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
        }else{
            GetSelectedvalues();
            $('#append_areatype').html('');
        }
    }else{
        arr_grp_id = [""];
        if(isAdmin == 1){
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
        }else{
            GetSelectedvalues();
            $('#append_areatype').html('');
        }
    }
});
function GetSelectedvalues() {
    $('.grp_append').html('');
    $('.label_remittance').css("display", "none");
    var _token = $("meta[name='csrf-token']").attr("content");
    var ids = []
    var isAdmin = $('#temp_name option:selected').attr('is-admin');
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
                $('#appendall_group').html('');
                $.each(response, function(k,v){
                    grp = v.group_ID.toString();
                    var branchsort = new Array();
                    arrayBranch =  v.branch.toString().split(",");
                    branchsort  = arrayBranch.sort();
                    if(isAdmin == 1){
                        var appendGroup = '<div class="row"> <div class="col-md-12 branch_assign"><input id="group_name" type="checkbox" name="group[]" value="'+v.group_ID+'" class="area_user grp_select" disabled checked><label>'+v.desc+'</label></div><div class="col-md-12">';
                        $.each(branchsort, function(k,br){
                            appendGroup += '<div class="col-md-2 grp-brnch">'+br+'</div>';
                        });
                        appendGroup += '</div></div>';
                        $('.grp_append').append(appendGroup);
                        $('#appendall_group').append('<input type ="hidden" type="checkbox" name="group[]" value="'+v.group_ID+'">');
                    }else{
                        $('#appendall_group').html('');
                        if ($.inArray(grp,arr_grp_id) !== -1) {
                            var appendGroup = '<div class="row"> <div class="col-md-12 branch_assign"><input id="group_name" type="checkbox" name="group[]" value="'+v.group_ID+'"class="area_user grp_select" checked><label>'+v.desc+'</label></div><div class="col-md-12">';
                            $.each(branchsort, function(k,br){

                                appendGroup += '<div class="col-md-2 grp-brnch">'+br+'</div>';
                            });
                            appendGroup += '</div></div>';
                            $('.grp_append').append(appendGroup); 
                        }else{
                            var appendGroup = '<div class="row"> <div class="col-md-12 branch_assign"><input id="group_name" type="checkbox" name="group[]" value="'+v.group_ID+'" class="area_user grp_select"><label>'+v.desc+'</label></div><div class="col-md-12">';
                            $.each(branchsort, function(k,br){
                                appendGroup += '<div class="col-md-2 grp-brnch">'+br+'</div>';
                            });
                            appendGroup += '</div></div>';
                            $('.grp_append').append(appendGroup);
                        }
                    }
                });
            }
        }
    });
}
</script>