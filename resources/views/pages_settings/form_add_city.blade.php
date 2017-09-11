@extends('layouts.app')

@section('content')
<h3 class="text-center">List of Locations</h3>
<div class="container-fluid">
    <div class="row">
		<div class="col-md-2">
		
		</div>
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">{{isset($detail_edit_city->City_ID)? "Edit " : "Add " }}City</div>
                <div class="panel-body">
                    <form class="form-horizontal form" role="form" method="POST" action="" id ="cityform">
                        {{ csrf_field() }}
                        <div class="form-group{{ $errors->has('City') ? ' has-error' : '' }}">
                            <label for="province_name" class="col-md-4 control-label">Province: </label>
                            <div class="col-md-6"> 
                         
                            <select class="form-control required" id="prov_id" name="Prov_ID">
                                    <option value="">Choose Province</option>
										
									
									
                                        @foreach ($province as $prov) 
                                            <option {{ (isset($detail_edit_city->Prov_ID) && 
											($detail_edit_city->Prov_ID == $prov ->Prov_ID)) ? "selected" : "" }} 
											value="{{ $prov ->Prov_ID }}">{{$prov->Province }}</option>
                                          
                                        @endforeach
                                </select>
                        </div>

                        <div class="form-group{{ $errors->has('city_name') ? ' has-error' : '' }}">
                            <label for="city" class="col-md-4 control-label">City Name:</label>
                            <div class="col-md-6">
                                <input id="city_name" type="text" class="form-control required" name="city_name"  value="{{isset($detail_edit_city->City) ? $detail_edit_city->City : "" }}" autofocus>
                                
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="text-center">
                                <button type="submit" class="btn btn-primary">
                                    {{isset($detail_edit_city->City_ID) ? "Save " : "Create " }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(function(){
    $("#cityform").validate();   
});

$(document).ready(function() {
    $('#list_featur').DataTable();
    $(document).on("click", ".sweet-4", function(){
        var delete_url = $(this).attr("rel");
        swal({
            title: "Your are about to delete a data?",
            text: "This will be deleted permanently...",
            type: "warning",
            showCancelButton: true,
            confirmButtonClass: 'btn-danger',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: "No",
            closeOnConfirm: false,
            closeOnCancel: true
        },
        function(isConfirm){
          if (isConfirm){
            window.location.replace(delete_url);
          } else {
            return false;
          }
        });
    });
    $('[data-toggle="tooltip"]').tooltip();
});
</script>

@endsection

