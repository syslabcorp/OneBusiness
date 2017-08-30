<div class="col-md-12 combine_branch">
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
                                    <td class="text-center"><input class="select" type="checkbox" name="city_id[]" value="{{$det->City_ID}}"
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
        if(this.checked){
            $(".select").each(function(){
                this.checked=true;
            })              
        }else{
            $(".select").each(function(){
                this.checked=false;
            })              
        }
    });
});
</script>