@extends('layouts.custom')
@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="row">
      <div class="col-sm-6">
        <strong>Pety Cash</strong>
      </div>
      <div class="col-sm-6 text-right">
        <a href="#" data-toggle="modal" data-target="#modal-new-category">
          Add Category
        </a>
        <a href="#" data-toggle="modal" data-target="#modal-new-subcategory">
          Add Subcategory
        </a>
      </div>
    </div>
    
  </div>
  <div class="panel-body">
  <div>
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active">
        <a href="#home" aria-controls="home" role="tab" data-toggle="tab">Configuration</a>
      </li>
      <li role="presentation">
        <a href="#profile" aria-controls="profile" role="tab" data-toggle="tab">Categories</a>
      </li>
      <li role="presentation">
        <a href="#messages" aria-controls="messages" role="tab" data-toggle="tab">Subcategories</a>
      </li>
    </ul>

    <div class="tab-content" style="margin-top: 30px;">
      <div role="tabpanel" class="tab-pane active" id="home">
        @include('categories.config-tab')
      </div>
      <div role="tabpanel" class="tab-pane" id="profile">
        @include('categories.category-tab')
      </div>
      <div role="tabpanel" class="tab-pane" id="messages">
        @include('categories.subcategory-tab')
      </div>
    </div>

    </div>
  </div>
</div>
@endsection

@section('pageJS')
<script type="text/javascript">
  (function() {
    // $('#table-config').DataTable({
    //   ordering: false,
    //   paging: false,
    // });

    $('#table-category').DataTable({
      order: [[1, 'asc']],
      columnDefs: [
        {
          targets: 2,
          orderable: false
        }
      ]
    });

    $('.btn-view').click(function() {
      $('.table-subcategory .empty').css('display', 'none');
      $('.table-subcategory tbody tr').css('display', 'none');
      $('.table-subcategory tbody tr[data-id="' + $(this).attr('data-id') +  '"]').css('display', 'table-row');
      $('.nav a[href="#messages"]').click();
    });

    $('#modal-new-category .btn-create').click(function(event) {
      $('#modal-new-category .error').remove();
      if($('#modal-new-category input[name="cat[description]"').val().trim() == '') {
        event.preventDefault();
        $('#modal-new-category input[name="cat[description]"').parents('.form-group').append('<span class="error">Category cannot be empty</span>');
      }

      if($('#modal-new-category input[name="subcat[description]"').val().trim() == '') {
        event.preventDefault();
        $('#modal-new-category input[name="subcat[description]"').parents('.form-group').append('<span class="error">Subcategory cannot be empty</span>');
      }
    });

    $('#modal-edit-category .btn-save').click(function(event) {
      $('#modal-edit-category .error').remove();
      if($('#modal-edit-category input[name="cat[description]"]').val().trim() == '') {
        event.preventDefault();
        $('#modal-edit-category input[name="cat[description]"]').parents('.form-group').append('<span class="error">Please input category</span>');
      }
    });
    

    $('#table-category').on("click", ".btn-edit", function(){

      $('#modal-edit-category input[name="cat[description]"]').val($(this).parents('tr').find('td:eq(1)').text());
      $('#modal-edit-category form').attr('action', $(this).attr('data-url'));
      if($(this).attr('data-active') == 1) {
        $('#modal-edit-category input[name="cat[active]"]').prop('checked', 1);
      }
      $('#modal-edit-category').modal('show');
    });
    

    $('.box-subcategory').on("click", ".btn-edit", function(){
      if($('.table-subcategory tbody tr.selected').length == 0) {
        return;
      }

      $('#modal-edit-subcategory input[name="description"]').val($('.table-subcategory tbody tr.selected td:eq(0)').text());
      $('#modal-edit-subcategory input[type="checkbox"][name="expires"]').prop('checked', $('.table-subcategory tbody tr.selected td:eq(1) input').is(':checked'));
      $('#modal-edit-subcategory input[type="checkbox"][name="multi_doc"]').prop('checked', $('.table-subcategory tbody tr.selected td:eq(2) input').is(':checked'));
      $('#modal-edit-subcategory form').attr('action', $('.table-subcategory tbody tr.selected').attr('data-delete'));
      $('#modal-edit-subcategory').modal('show');
    });

    $('#table-category').on("click", ".btn-delete", function(){
      var self = $(this);

      swal({
        title: "<div class='delete-title'>Confirm Delete</div>",
        text:  "<div class='delete-text'>You are about to delete category <strong>\"" + $(this).parents('tr').find('td:eq(1)').text() + "\"</strong><br/> Are you sure?</div>",
        html:  true,
        customClass: 'swal-wide',
        showCancelButton: true,
        confirmButtonClass: 'btn-danger',
        confirmButtonText: 'Delete',
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
        closeOnCancel: true
      },
      function(isConfirm){
        if(isConfirm) {
          self.parents('form').submit();
        }
      });
    });

    $('.table-subcategory').on("click", ".btn-delete", function(){
      var self = $(this);
      swal({
        title: "<div class='delete-title'>Confirm Delete</div>",
        text:  "<div class='delete-text'>You are about to delete subcategory <strong>\"" + $(this).parents('tr').find('td:eq(2)').text() + "\"</strong><br/> Are you sure?</div>",
        html:  true,
        customClass: 'swal-wide',
        showCancelButton: true,
        confirmButtonClass: 'btn-danger',
        confirmButtonText: 'Delete',
        cancelButtonText: "Cancel",
        closeOnConfirm: false,
        closeOnCancel: true
      },
      function(isConfirm){
        if(isConfirm) {
          self.parents('form').submit();
        }
      });
    });
  })();
</script>
@endsection
