function get_list_data(){
    var city_id = $('#city option:selected').val();
    var corp_id = $('#corp_id').val();

    $(".update-add-url").attr("href", ajax_url+'/purchase_order/'+corp_id+'/'+city_id + '?corpID=' + corp_id);
    var active = $('#active option:selected').val();
    var _token = $("meta[name='csrf-token']").attr("content");
    $.ajax({
        url: ajax_url+'/list_purchase_order',
        type: "POST",
        data: {_token,city_id,active,corp_id},
        success: function(response){
          $('.list-purchase-orders').html(response);
        }
    });
}

$(document).ready(function(){
    //$("#city option:first").prop("selected", "selected");
    get_list_data();
});
$(".listcity").change(function(){ 
    get_list_data();
});
$(".activelist").change(function(){ 
	get_list_data();
});