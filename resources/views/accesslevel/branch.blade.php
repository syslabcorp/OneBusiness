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
                                    <td class="text-center"><input class="select" type="checkbox" name="branch_id[]" value="{{$det->Branch }}"
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