<div class="col-md-12 combine_branch">
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
                                    <td class="text-center"><input class="select" type="checkbox" name="provience_id[]" value="{{$det->Prov_ID}}"
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