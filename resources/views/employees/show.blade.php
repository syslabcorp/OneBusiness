@extends('layouts.custom')

@section('header_styles')
  <link href="{{ asset('css/my.css') }}" rel="stylesheet" type="text/css"/>
  <style>
    .print {
      display: none;
    }
    @media print {
      * {
        font-size: 13px !important;
      }
      .print {
        display: block !important;
      }
      form, ul, .panel-heading, .panel-footer, .dataTables_info {
        display: none;
      }
      #togle-sidebar-sec {
        margin-top: 0px;
      }
      .panel, .panel-body, .table-bordered {
        border: none;
        padding: 0px;
        margin: 0px;
      }
      .table {
        display: block !important;
      }
    }
  </style>
@endsection

@section('content')

    <div id="page-content-togle-sidebar-sec">
  <div class="col-md-12">
    <div class="row">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="row">
            <div class="col-xs-9">
              <h4>{{ $user->UserName }}</h4>
            </div>
            <div class="col-xs-3 text-right" style="padding-top: 10px;">
              @if(\Auth::user()->checkAccessByIdForCorp($corpID, 48, 'A' ))
                <a href="#" onclick="getDocumentModal('')" class="addDocument" style="{{ $tab != 'doc' ? 'display: none;' : '' }}">Add Document</a>
              @endif
            </div>
          </div>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-md-12">
              <div class="bs-example">
                <ul class="nav nav-tabs" style="margin-bottom: 15px;">
                  <li class="{{ $tab == 'auto' ? 'active' : '' }}">
                    <a href="#personInfo" data-toggle="tab">Personal Information</a>
                  </li>
                  <li class="{{ $tab == 'doc' ? 'active' : '' }}">
                    <a href="#document" data-toggle="tab">Document</a>
                  </li>
                  <li class="{{ $tab == 'shortages' ? 'active' : '' }}">
                    <a href="#shortages" data-toggle="tab">Shortages</a>
                  </li>
                  <li class="{{ $tab == 'tardiness' ? 'active' : '' }}">
                    <a href="#tardiness" data-toggle="tab">Tardiness</a>
                  </li>
                  <li class="{{ $tab == 'stock' ? 'active' : '' }}">
                    <a href="#position" data-toggle="tab">Position-Branch Movement</a>
                  </li>
                  <li class="{{ $tab == 'wage' ? 'active' : '' }}">
                    <a href="#wage" data-toggle="tab">Wage Movement</a>
                  </li>
                </ul>
                <div  class="tab-content" style="padding: 1em;">
                  @include('employees.personInfo', ['user'=> $user])
                  @include('employees.document', ['user'=> $user])
                  @include('employees.shortages')
                  @include('employees.tardiness')
                  @include('employees.positionBranch', ['user'=> $user])
                  @include('employees.wage', ['user'=> $user])
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="panel-footer">
          <div class="rown">
            <div class="col-xs-6">
              <a href="{{ route('employee.index', ['corpID' => $corpID]) }}" class="btn btn-default">Back</a>
            </div>
            <div class="col-xs-6 text-right">
              <a class="btn btn-primary" id="save_employee" style="display: none;">Save</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @include('employees.new-recommendation-modal')
  @include('employees.image-modal')
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/table-edits.min.js"></script>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/momentjs.min.js"></script>
<script src="http://onebusiness.shacknet.biz/OneBusiness/js/bootstrap-datetimepicker.min.js"></script>
<script>

$(document).ready(function() {
  $('.shortages-datatable').DataTable({
    bPaginate: false,
    searching: false,
    columns: [
      {
        name: 'period',
        title: 'Payroll Period'
      },
      {
        title: 'Branch/Shift Date'
      },
      {
        title: 'Amount'
      },
    ],
    rowsGroup: [
      'period:name'
    ],
    order: [
      [0, 'desc']
    ]
  });

  $('.tardiness-datatable').DataTable({
    bPaginate: false,
    searching: false,
    columns: [
      {
        name: 'period',
        title: 'Payroll Period'
      },
      {
        title: 'Branch/Shift Date'
      },
      {
        title: 'Late (in mins)'
      },
    ],
    rowsGroup: [
      'period:name'
    ],
    order: [
      [0, 'desc']
    ]
  });

  let documentTable = $('#table-document-deliveries').DataTable({
    "dom": '<"row"<"m-t-10"B><"m-t-10 pull-left"l><"m-t-10 pull-right"f>><"col-md-12 branch-filter"><"m-t-10 pull-right"p>',
    initComplete: function() {
      $(".branch-filter").append('<div>Filter: \
        <label style="font-weight: normal;"><input checked name="document-filter" value="all" type="radio" /> Show All </label> \
        <label style="font-weight: normal;padding-left: 30px;"><input name="document-filter" value="document" type="radio" /> Document </label> \
        <select disabled class="form-control document-select" style="width: 150px;"> </select> \
        <label style="font-weight: normal;padding-left: 30px;"><input name="document-filter" value="category" type="radio" /> Category </label> \
        <select disabled class="form-control category-select" style="width: 150px;"> </select> \
      </div>')

      @foreach($categories as $cat)
        $(".category-select").append('<option value="{{ $cat->doc_no }}">{{$cat->description}}</option>')
      @endforeach

      @foreach($subCategories as $subcat)
        $(".document-select").append('<option value="{{ $subcat->subcat_id }}">{{$subcat->description}}</option>')
      @endforeach
    },
    bPaginate: false,
    searching: false,
    ajax: '{{ route('employee.deliveryDocuments', ['id' => $user->UserID,'corpID' => $corpID]) }}',
    columns: [
      {
        targets: 0,
        data: "txn_id"
      },
      {
        targets: 1,
        data: "Series",
      },
      {
        targets: 2,
        data: "Approval"
      },
      {
        targets: 3,
        data: "Branch"
      },
      {
        targets: 4,
        data: "Category"
      },
      {
        targets: 5,
        data: "Document"
      },
      {
        targets: 6,
        data: "Notes"
      },
      {
        targets: 7,
        data: "Expiry"
      },
      {
        targets: 8,
        data: "Image",
        render: (data, type, row, meta) => {
          return '<a href="{!! route('image', ['corpID' => $corpID]) !!}&filename=' + data + '" target="_blank">' + data + '</a>'
        }
      },
      {
        targets: 9,
        data: "DateArchived"
      },
      {
        targets: 10,
        render: (data, type, row, meta) => {
          return '<button {{ !\Auth::user()->checkAccessByIdForCorp($corpID, 48, 'E' ) ? 'disabled' : '' }} onclick="getDocumentModal(' + row.txn_id +')" class="btn btn-primary fa fa-pencil-alt"> </button>'
        }
      }
    ],
    order: [
      [0, 'desc']
    ]
  });

  $('body').on('change', 'input[name="document-filter"], .document-select, .category-select', (event) => {
    let baseURL = "{{ route('employee.deliveryDocuments', ['id' => $user->UserID,'corpID' => $corpID]) }}"
    $('.document-select, .category-select').prop('disabled', true)

    switch($('input[name="document-filter"]:checked').val()) {
      case 'all':
      break;
      case 'document':
        $('.document-select').prop('disabled', false)
        baseURL += "&document=" + $('.table-responsive .document-select').val()
      break;
      case 'category':
        $('.category-select').prop('disabled', false)
        baseURL += "&category=" + $('.table-responsive .category-select').val()
      break;
    }

    documentTable.ajax.url(baseURL).load()
  });


  $('#table-position-deliveries').DataTable({

      initComplete: function() {
      },

      ajax: '{{ route('employee.deliveryPositions', ['id' => $user->UserID,'corpID' => $corpID]) }}',
      columns: [
        {
          targets: 0,
          data: "Branch"
        },
        {
          targets: 1,
          data: 'start_date_order',
        },
        {
          targets: 2,
          data: "SeparationDate"
        },
        {
          targets: 3,
          data: "Position"
        },
        {
          targets: 4,
          data: "Status"
        }
      ],
      createdRow: (row, data, dataIndex) => {
        var $dateCell = $(row).find('td:eq(1)')
        $dateCell.attr('data-order', data.start_date_order)
                 .text(data.StartDate)
      },
      order: [
        [1, 'desc']
      ],
  });

  $('#table-wage-deliveries').DataTable({
    searching: false,
    ajax: '{{ route('employee.deliveryWages', ['id' => $user->UserID,'corpID' => $corpID]) }}',
    columns: [
      {
        targets: 0,
        data: "EffectiveDate",
        class: 'text-center'
      },
      {
        targets: 1,
        data: "BaseRate",
        class: 'text-center'
      },
      {
        targets: 2,
        data: "PayCode",
        class: 'text-center',
        render: (data, type, row, meta) => {
          return "<div class='tooltipp'>" + data + "<div class='tooltiptext panel'> <div class='panel-heading'>\
                <strong>Base rate: "  + row.BaseRate + "</strong></div><div class='panel-body'> \
                <strong>Benefits</strong>: <br>"
                + row.benfs.map(item => '- ' + item).join('<br>') + 
                "<br> <strong>Deductions:</strong> <br>"
                + row.deducts.map(item => '- ' + item).join('<br>') + 
                "<br> <strong>Expense:</strong> <br>"
                + row.exps.map(item => '- ' + item).join('<br>') + 
              "</div></div></div>";
        },
      },
      {
        targets: 3,
        data: "PayBasic",
        class: 'text-center'
      }
    ],
    order: [
      [0, 'desc']
    ]
  });

  $('body').on('mouseenter', '.tooltipp', function(event) {
    $(this).find('.tooltiptext').css({
      'display': 'block',
      'top': $(this).offset().top - $(window).scrollTop() - $(this).find('.tooltiptext').height() - 30,
      'left': $(this).offset().left - $(this).find('.tooltiptext').width() / 2,
    })

  }).on('mouseleave', '.tooltipp', function(event) {
    $(this).find('.tooltiptext').css('display', 'none')
  })

  $("#edit_employee").click(function(){
    $(this).attr("disabled", true);
    $('.disabled').removeAttr('disabled');
    $('#employee_form input').prop('disabled', false)
    $('#save_employee').show();
  })

  $("#save_employee").click(function(){
    $('#employee_form').submit();
  })

});



  deleteStock = (id) => {
    let self = $(event.target)

    swal({
      title: "<div class='delete-title'>Confirm Delete</div>",
      text:  "<div class='delete-text'>Are you sure you want to delete DR#" + id + "?</strong></div>",
      html:  true,
      customClass: 'swal-wide',
      showCancelButton: true,
      confirmButtonClass: 'btn-success',
      closeOnConfirm: false,
      closeOnCancel: true
    },(confirm) => {
      $.ajax({
        url : 'stocktransfer/' + id + '?corpID={{ $corpID }}' ,
        type : 'DELETE',
        success: (res) => {
          showAlertMessage('DR#' + id + ' has been deleted!', 'Success')
          self.parents('tr').remove()
        }
      })
    })
  }



function onEditRow(param){
    if($('#editable'+param).hasClass('glyphicon-pencil')){

         $(".rcvdCheckbox"+param).attr("disabled", false);
         $(".uploadCheckbox"+param).attr("disabled", false);
    }
    else{

         $(".rcvdCheckbox"+param).attr("disabled", true);
         $(".uploadCheckbox"+param).attr("disabled", true);
    }
}

</script>

<script>
    var tmasterId;
    var urlmarkToserved;

    filterStatusStock = () => {
      let path = location.search.replace(/&stockStatus=[0-9]+/g, '').replace(/&status=[0-9]+/g, '')
      path = path.replace(/&tab=[a-z]+/g, '') + "&tab=stock"
      path +=  "&stockStatus=" + $('#stockStatus select').val()
      window.location = location.pathname + path
    }

    function filterStatus(event) {
      let path = location.search.replace(/&statusStock=[0-9]+/g, '').replace(/&status=[0-9]+/g, '')
      path = path.replace(/&tab=[a-z]+/g, '') + "&tab=auto"
      path +=  "&status=" + $('#selectId select').val()
      window.location = location.pathname + path
    }

  showAlertMessage = (message, title = "Alert", isReload = false) => {
    swal({
      title: "<div class='delete-title'>" + title + "</div>",
      text:  "<div class='delete-text'>" + message + "</strong></div>",
      html:  true,
      customClass: 'swal-wide',
      showCancelButton: false,
      closeOnConfirm: true,
      allowEscapeKey: !isReload
    }, (data) => {
      if(isReload) {
        window.location.reload()
      }
    });
  }

  markToserved = (event, id) => {
    let self = $(event.target)

    swal({
      title: "<div class='delete-title'>Mark to served</div>",
      text:  "<div class='delete-text'>Serve PO: Are you sure you want to mark " + id + " as served?</strong></div>",
      html:  true,
      customClass: 'swal-wide',
      showCancelButton: true,
      confirmButtonClass: 'btn-success',
      closeOnConfirm: false,
      closeOnCancel: true
    },(confirm) => {
      $.ajax({
        url : 'stocktransfer/' + id + '/served?corpID={{ $corpID }}' ,
        type : 'POST',
        success: (res) => {
          showAlertMessage('P.O.# ' + id + ' has been served', 'Success')
          self.parents('tr').remove()
        }
      })
    })
  }

  getDocumentModal = (id) => {
    $.ajax({
      url : '{{ route('employee.documentModal', [$user->UserID, 'corpID' => $corpID]) }}&txn_no=' + id,
      type : 'GET',
      success: (res) => {
        $('#modal-document').remove()
        $('body').append(res)
        $('#modal-document').modal('show')
        updateDocumentModalSubcategory()
      }
    })
  }

  $('body').on('change', '#modal-document select[name="doc_no"]', (event) => {
    $('#modal-document select[name="subcat_id"]').val('')
    updateDocumentModalSubcategory()
  })

  $('body').on('click', '#modal-document .btn-create', (event) => {
    $('#modal-document .error').remove()

    let isRequiredExpires = $('#modal-document select[name="subcat_id"] option:selected').attr('data-expires')
    if (isRequiredExpires == 1 && !$('#modal-document .checkExpires').is(':checked')) {
      $('#modal-document .checkExpires').parents('.col-sm-8').append(
        '<span class="error">Please select an expiration date for this document</span>'
      )
      event.preventDefault()
    }
  })

  updateDocumentModalSubcategory = () => {
    $('#modal-document select[name="subcat_id"] option[value!=""]').css('display', 'none')
    $('#modal-document select[name="subcat_id"] option[doc-no="' + $('#modal-document select[name="doc_no"]').val() + '"]').css('display', 'block')

    if ($('#modal-document select[name="subcat_id"]').val() == '') {
      $('#modal-document select[name="subcat_id"]').val($('#modal-document select[name="subcat_id"] option[style="display: block;"]:first').val());
    }
    
  }

  toggleExpiry = (event) => {
    $('#modal-document input[name="doc_exp"]').prop('disabled', true)
    $('#modal-document input[name="doc_exp"]').prop('required', false)

    if(event.target.checked) {
      $('#modal-document .error').remove()
      $('#modal-document input[name="doc_exp"]').prop('required', true)
      $('#modal-document input[name="doc_exp"]').prop('disabled', false)
    }
  }

  

  $('.nav li a').click((event) => {
    if ($(event.target).attr('href') != '#document') {
      $('.addDocument').css('display', 'none');
    } else {
      $('.addDocument').css('display', 'block');
    }
  })

  $.ajax({
    url: "{!! route('image', ['corpID' => $corpID, 'filename' => $filename]) !!}",
    type: 'GET',
    contentType: "image/jpeg",
    dataType: "text",
    success: (res) => {
      $('.image #loader').remove()
      $('.image').append('<img style="width: 100%; height: 100%;" />')
      $('.image img').attr('src', '{!! route('image', ['corpID' => $corpID, 'filename' => $filename]) !!}')
    },
    error: () => {
      $('.image').append('<h3 class="text-center">No image stored</h3>')
      $('.image #loader').remove()
    }
  })

  $('.btnModalImage').click(function() {
    $('#image-modal .modal-content').html('<div id="loader" style="margin-bottom: 55px;"></div>')
    $('#image-modal').modal('show')
    let fileName = $(this).attr('data-image')

    $.ajax({
      url: "{!! route('image', ['corpID' => $corpID]) !!}&filename=" + fileName,
      type: 'GET',
      contentType: "image/jpeg",
      dataType: "text",
      success: (res) => {
        $('#image-modal #loader').remove()
        $('#image-modal .modal-content').append('<img style="width: 100%; height: 100%;" />')
        $('#image-modal img').attr('src', '{!! route('image', ['corpID' => $corpID]) !!}&filename=' + fileName)
      },
      error: () => {
        $('#image-modal .modal-content').append('<h3 class="text-center">No image stored</h3>')
        $('#image-modal #loader').remove()
      }
    })
  })



</script>

<script>
    $(function() {
      var pickers = {};

      $('table tr').editable({

        dropdowns: {
          sex: ['Male', 'Female']
        },
        edit: function(values) {

          $(".edit span", this)
            .removeClass('glyphicon-pencil')
            .addClass('glyphicon-ok')
            .attr('title', 'Save');
        },
        save: function(values) {
          $(".edit span", this)
            .removeClass('glyphicon-ok')
            .addClass('glyphicon-pencil')
            .attr('title', 'Edit');



          if (this in pickers) {
            pickers[this].destroy();
            delete pickers[this];
          }
        },
        cancel: function(values) {
          $(".edit i", this)
            .removeClass('glyphicon-ok')
            .addClass('glyphicon-pencil')
            .attr('title', 'Edit');

          if (this in pickers) {
            pickers[this].destroy();
            delete pickers[this];
          }
        }
      });
    });
  </script>
@endsection
