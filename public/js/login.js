$(function(){
  $("input[type='text']").keydown(function(event){
    if(event.keyCode == 13) {
      event.preventDefault();
      return false;
    }
  });

  $("#form-username input[name='email']").change(function(){
    var username = $(this).val();
    //check_btn(username);
  });
});

function check_btn(username){
  if(username == ""){
    $(".submit-button").html('');
    return false;
  }
  var action = 'check_btn';
  var _token = $("input[name='_token']").val();
  $.ajax({
    url: ajax_url+'/ajax_action',
    data: {username, _token, action},
    type: "POST",
    dataType: "json",
    success: function(res){
      if(res.id){
        $(".submit-button").html(res.btn);
      }else{
        $(".submit-button").html('<button type="submit" class="btn btn-primary">Submit</button><div class="pull-right forgot-password"><a href="'+ajax_url+'/forgot_pass">Forgot Your Password</a></div>');
      }
    }
  });
}
function user_register(user_id, user_name) {
  $('body').ajaxMask();
  regStats = 0;
  regCt = -1;
  try
  {
    timer_register.stop();
  }
  catch(err)	
  {
    console.log('Registration timer has been init');
  }
  
  var limit = 4;
  var ct = 1;
  var timeout = 5000;
  
  timer_register = $.timer(timeout, function() {
    console.log("'"+user_name+"' registration checking...");
    user_checkregister(user_id,$(".user-finger").attr("finger-count"));
    if (ct>=limit || regStats==1) 
    {
      timer_register.stop();
      console.log("'"+user_name+"' registration checking end");
      if (ct>=limit && regStats==0)
      {
        $("#myModal").modal("show");
        //alert("'"+user_name+"' registration fail!");
        $('body').ajaxMask({ stop: true });
      }						
      if (regStats==1)
      {
        $(".user-finger").attr("finger-count", regCt);
        //alert("'"+user_name+"' registration success!");
        $('body').ajaxMask({ stop: true });
        $(".removeonsuccess").remove();
        window.location.replace(ajax_url+'/get_logout');
        //check_btn(user_name);
        //load('user.php?action=index');
      }
    }
    ct++;
  });
}

function user_checkregister(user_id, current) {
  var action = 'checkreg';
  var _token = $("input[name='_token']").val();
  $.ajax({
    //url :	ajax_url+"/ajax_action",
    url :	ajax_url+"/home_ajax_action",
    data: {_token, action, user_id, current},
    type :	"POST",
    success :	function(data) {
      try
      {
        var res = jQuery.parseJSON(data);	
        if (res.result)
        {
          regStats = 1;
          $.each(res, function(key, value){
            if (key=='current')
            {														
              regCt = value;
            }
          });
        }
      }
      catch(err)
      {
        alert(err.message);
      }
    }
  });
}

function check_for_btn(username, type){
  if(type == "bio_auth"){
    var action = 'btnontype';
    var _token = $("input[name='_token']").val();
    $.ajax({
      url: ajax_url+'/ajax_action',
      data: {username, _token, action},
      type: "POST",
      dataType: "json",
      success: function(res){
        $(".submit-button").html(res.btn);
      }
    });
  }else{
    $(".submit-button").html('<button type="submit" class="btn btn-primary otp-submit">Submit</button>');
  }
}

function user_register_type(user_id, user_name) {
  $('body').ajaxMask();
  regStats = 0;
  regCt = -1;
  try
  {
    timer_register.stop();
  }
  catch(err)	
  {
    console.log('Registration timer has been init');
  }
  
  var limit = 4;
  var ct = 1;
  var timeout = 5000;
  
  timer_register = $.timer(timeout, function() {
    console.log("'"+user_name+"' registration checking...");
    user_checkregister(user_id,$(".user-finger").attr("finger-count"));
    if (ct>=limit || regStats==1) 
    {
      timer_register.stop();
      console.log("'"+user_name+"' registration checking end");
      if (ct>=limit && regStats==0)
      {
        $("#myModal").modal("show");
        //alert("'"+user_name+"' registration fail!");
        $('body').ajaxMask({ stop: true });
      }						
      if (regStats==1)
      {
        $(".user-finger").attr("finger-count", regCt);
        alert("'"+user_name+"' registration success!");
        $('body').ajaxMask({ stop: true });
        check_for_btn(user_name);
        //load('user.php?action=index');
      }
    }
    ct++;
  });
}

function reset_finger(user_id){
  var _token = $("meta[name='csrf-token']").attr("content");
  var action = "remove_finger";
  $.ajax({
    url: ajax_url+'/home_ajax_action',
    data: {user_id, _token, action},
    type: "POST",
    dataType: "json",
    success: function(res){
      $(".row-"+user_id+" td.switch-reset-register").html(res.btn);
    }
  });
}

function user_register_admin(user_id, user_name) {
  $('body').ajaxMask();
  regStats = 0;
  regCt = -1;
  try
  {
    timer_register.stop();
  }
  catch(err)	
  {
    console.log('Registration timer has been init');
  }
  
  var limit = 4;
  var ct = 1;
  var timeout = 5000;
  timer_register = $.timer(timeout, function() {
    console.log("'"+user_name+"' registration checking...");
    user_checkregister(user_id,$(".row-"+user_id+" td a.user-finger").attr("finger-count"));
    if (ct>=limit || regStats==1) 
    {
      timer_register.stop();
      console.log("'"+user_name+"' registration checking end");
      if (ct>=limit && regStats==0)
      {
        $("#myModal").modal("show");
        $('body').ajaxMask({ stop: true });
      }						
      if (regStats==1)
      {
        $('body').ajaxMask({ stop: true });
        $("#successReg").modal("show");
        $(".switch-reset-register").html('<a href="javascript:;" class="btn btn-xs btn-danger" onclick="reset_finger('+user_id+')">Reset</a>');
      }
    }
    ct++;
  });
}

$('#assign-rate-template input[value!="all"]').click(function(event) {
  $('#assign-rate-template input[value="all"]').prop("checked", false);
});

$(function(){
  $('#brach_remittance_create').on('change', '#city', function(){
    $('#brach_remittance_create').submit();
  });

  $('.form-status').on('change', 'select', function(event) {
    $('#brach_remittance_create input[name="groupStatus"]').val($(this).val());
    $('#brach_remittance_create select[name="groupId"]').val(null);
    $('#brach_remittance_create select[name="cityId"]').val(null);
    $('#brach_remittance_create').submit();
  });
});

$(function()
{
  $('#brach_remittance_create').on('change', '#remit_group', function(){
    $('#brach_remittance_create').submit();
  });
  
  $('#view_date_range').change(function(){
    if( this.checked )
    {
      $("#start_date").prop('disabled', false);
      $("#end_date").prop('disabled', false);
      $("#button_ranger_date").prop('disabled', false);
    }
    else
    {
      $('#start_date').val("");
      $('#end_date').val("");
      $(this).parents('form').submit();
    }
  });

  // $("#start_date").change(function()
  // {
  // 	$("#date_range").submit();
  // });

  // $("#end_date").change(function()
  // {
  // 	$("#date_range").submit();
  // });

  $('#adj_short').change(function()
  {
    if( this.checked ) {
      $("#shortage").prop('disabled', false);
    }else {
      $("#shortage").prop('disabled', true);
      $('#shortage').parent('.col-xs-9').find('.render-error-modal').remove();
    }
  });

  $('#save_button').on('click', function(event)
  {
    event.preventDefault();
    $continue = true;
    $('.render-error-modal').remove();
    if(!$.isNumeric($('#shortage').val()) && $('#adj_short')[0].checked) {
      $('#shortage').closest('.col-xs-9').append('<i class="render-error-modal" style="color:#cc0000;">The Input must be a number.</i>')
      $continue = false;
    }

    if( !$.isNumeric( $('#total_remittance').val() )  )
    {
      $('#total_remittance').closest('.col-xs-9').append('<i class="render-error-modal" style="color:#cc0000;">The Input must be a number.</i>')
      $continue = false;
    }
    
    if($continue)
    {
      $('#modal_form').submit();
    }
    
  });

  $(".show_modal").on('click', function(event)
  {
    event.preventDefault();
    var _token = $("meta[name='csrf-token']").attr("content");
    var self = $(this);
    $id = $(this).attr("data-shift-id");
    var url = "/branch_remittances/render_modal";
    if(window.location.pathname.match(/OneBusiness/)) {
      url = '/OneBusiness' + url; 
    }
    $.ajax({
      url: url,
      type: "POST",
      data: { 'id': $id, corpID: self.attr('data-corp'), _token },
      success: function(res){
        $('#cashier').html(res.cashier);
        $('#shift_id').html(res.shift_id);
        $('#total_sales').html(res.total_sales);
        $('#total_shortage').html(res.total_shortage);
        $('#total_remittance').val(res.total_remittance.toFixed(2));
        if(res.couterchecked == 1) {
          $('#counterchecker').prop( "checked", true );
        }else {
          $('#counterchecker').prop( "checked", false );
        }

        if(res.wrong_input == 1)
        {
          $('#wrong_input').prop( "checked", true );
        }
        else
        {
          $('#wrong_input').prop( "checked", false );
        }

        $('#shortage').val(res.shortage);

        if(res.adj_short == 1 )
        {
          $('#adj_short').prop( "checked", true );
        }
        else
        {
          $('#adj_short').prop( "checked", false );
          $("#shortage").prop('disabled', true);
        }
        $('#remarks').val(res.remarks);
        $('#hidden_shift_id').val(res.shift_id);
      }

    });
  });


  $('.table-remittances tbody td').click(function(event) {
    if(!event.ctrlKey && !event.shiftKey) {
      return;
    }

    var isSelected = $(this).hasClass('selected');
    var startIndex = $(this).parent('tr').index();
    var endIndex = startIndex;
    var branchId = $(this).parent('tr').attr('data-branch');
    var dateGroup = $(this).parent('tr').attr('data-date');
    $tbodyEl = $(this).parents('tbody');
  
    if(event.ctrlKey) {
      if($(this).hasClass('col-branch')) {
        endIndex = startIndex + $(this).attr('rowspan')*1;
      }else if($(this).hasClass('col-date')) {
        endIndex = startIndex + $(this).attr('rowspan')*1;
      }else {
        endIndex = startIndex + 1;
      }
    }else if(event.shiftKey) {
      if($('.table-remittances tbody td.selected').length == 0) {
        endIndex = startIndex + 1;
      }else {
        startIndex = $('.table-remittances tbody td.selected:eq(4)').parent('tr').index();
        if(endIndex > startIndex) {
          endIndex += 1;
        }else {
          startIndex += 1;
        }
      }
      if(startIndex > endIndex) {
        var temp = endIndex;
        endIndex = startIndex;
        startIndex = temp;
      }
      $tbodyEl.find('tr td').removeClass('selected');
    }

    for(var col = startIndex; col < endIndex; col++) {
      if(isSelected) {
        $tbodyEl.find('tr:eq(' + col + ') td').removeClass('selected');
      }else {
        $tbodyEl.find('tr:eq(' + col + ') td').addClass('selected');
      }
    }

    if($tbodyEl.find('tr[data-branch="' + branchId + '"] .selected').length > 1) {
      $tbodyEl.find('tr[data-branch="' + branchId + '"] .col-branch').addClass('selected');
    }else {
      $tbodyEl.find('tr[data-branch="' + branchId + '"] .col-branch').removeClass('selected');
    }

    if($tbodyEl.find('tr[data-branch="' + branchId + '"][data-date="' + dateGroup + '"] .selected').length > 1) {
      $tbodyEl.find('tr[data-branch="' + branchId + '"][data-date="' + dateGroup + '"] .col-date').addClass('selected');
    }else {
      $tbodyEl.find('tr[data-branch="' + branchId + '"][data-date="' + dateGroup + '"] .col-date').removeClass('selected');
    }

    $('.remitance-total td:eq(1) .total').text(getTotalColumn('col-retail'));
    $('.remitance-total td:eq(2) .total').text(getTotalColumn('col-service'));
    $('.remitance-total td:eq(3) .total').text(getTotalColumn('col-rental'));
    $('.remitance-total td:eq(4) .total').text(getTotalColumn('col-sale'));
    $('.remitance-total td:eq(5) .total').text(getTotalColumn('col-remit'));

    if($('.table-remittances tbody td.selected').length > 0) {
      $('.btn-check-ok').prop('disabled', false);
      $('.btn-save-ok').prop('disabled', false);
    }else {
      $('.btn-check-ok').prop('disabled', true);
      $('.btn-save-ok').prop('disabled', true);
    }
  });

  $('.btn-check-ok').click(function(event) {
    $('.table-remittances tbody tr').each(function(el) {
      $(this).find('.col-clr.selected input').prop('checked', true);
    });
  });

  $('.btn-save-ok').click(function(event) {
    var shiftIds = [];
    $('.table-remittances tbody tr').each(function(el) {
      if($(this).find('td:eq(4).selected').length > 0) {
        shiftIds.push($(this).attr('data-id'));
      }
    });

    var _token = $("input[name='_token']").val();

    $.ajax({
      url: window.location.pathname + '/remittances' + window.location.search,
      type: "POST",
      data: { shiftIds: shiftIds, _method: "PUT", _token },
      success: function(res){
        window.location.reload();
      },
      error: function(res) {
        window.location.reload();
      }
    });
  });

  function getTotalColumn(colClass) {
    var result = 0;
    $('.table-remittances tbody td.selected.' + colClass).each(function(el, index) {
      if(parseFloat($(this).text())) {
        result += parseFloat($(this).text().replace(/\,/, ""));
      }
    });

    return result.toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
});
