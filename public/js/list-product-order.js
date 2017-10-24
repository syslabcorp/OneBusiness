function get_list_data(){
	var city_id = $('#city option:selected').val();
	var active = $('#active option:selected').val();
    var _token = $("meta[name='csrf-token']").attr("content");
    $.ajax({
        url: ajax_url+'/list_purchase_order',
        type: "POST",
        data: {_token,city_id,active},
        success: function(response){
          $('.list-purchase-orders').html(response);
        }
    });
}

$(document).ready(function(){
	$("#city option:first").prop("selected", "selected");
	get_list_data();
});
$(".listcity").change(function(){ 
	get_list_data();
});
$(".activelist").change(function(){ 
	get_list_data();
});