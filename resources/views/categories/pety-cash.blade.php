@extends('layouts.custom')
@section('content')
<div class="panel panel-default">
  <div class="panel-heading">
    <div class="row">
      <div class="col-sm-6">
        <strong>Petty Cash Categories</strong>
      </div>
      <div class="col-sm-6 text-right">
        @if(\Auth::user()->checkAccessById(32, "A"))
        <a href="#" data-toggle="modal" data-target="#modal-new-category" style="display: none;">
          Add Category
        </a>
        <a href="#" data-toggle="modal" data-target="#modal-new-subcategory" style="display: none;">
          Add Subcategory
        </a>
        @endif
      </div>
    </div>
    
  </div>
  <div class="panel-body">
  <div>
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active">
        <a href="#con" aria-controls="con" role="tab" data-toggle="tab">Configuration</a>
      </li>
      <li role="presentation">
        <a href="#cat" aria-controls="cat" role="tab" data-toggle="tab">Categories</a>
      </li>
      <li role="presentation">
        <a href="#sub" aria-controls="sub" role="tab" data-toggle="tab">Subcategories</a>
      </li>
    </ul>

    <div class="tab-content" style="margin-top: 30px;">
      <div role="tabpanel" class="tab-pane active" id="con">
        @include('categories.config-tab')
      </div>
      <div role="tabpanel" class="tab-pane" id="cat">
        @include('categories.category-tab')
      </div>
      <div role="tabpanel" class="tab-pane" id="sub">
        @include('categories.subcategory-tab')
      </div>
    </div>

    </div>
  </div>
</div>

<div class="modal fade" id="modal-new-subcategory">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('pcsubcategories.store') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="subcat[active]" value="0">
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Subcategory</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Category:</label>
                <select name="subcat[cat_id]" class="form-control">
                  @foreach($categories as $cat)
                  <option value="{{ $cat->cat_id }}">{{ $cat->description }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Subcategory:</label>
                <input type="text" class="form-control" name="subcat[description]">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label style="width: 100%">&nbsp;</label>
                <label for="">
                  <input type="checkbox" name="subcat[active]" value="1"> Active
                </label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label>Satellite Branchs:</label>
                <div>
                  @foreach($branchs as $branch)
                  <input type="hidden" name="branches[{{ $loop->index }}][sat_branch]" value="{{ $branch->sat_branch }}">
                  <label style="margin-right: 10px; margin-bottom: 5px;">
                    <input type="checkbox" name="branches[{{ $loop->index }}][checked]" value="1"> {{ $branch->short_name }}
                  </label>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="pull-left">
              <button type="button" class="btn btn-default" data-dismiss="modal"> Back</button>
            </div>
            <div class="pull-right">
              <button type="submit" class="btn btn-primary btn-create">Create</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-edit-subcategory">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('pcsubcategories.store') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="subcat[active]" value="0">
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Edit Subcategory</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Category:</label>
                <select name="subcat[cat_id]" class="form-control">
                  @foreach($categories as $cat)
                  <option value="{{ $cat->cat_id }}">{{ $cat->description }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Subcategory:</label>
                <input type="text" class="form-control" name="subcat[description]">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label style="width: 100%">&nbsp;</label>
                <label for="">
                  <input type="checkbox" name="subcat[active]" value="1"> Active
                </label>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label>Satellite Branchs:</label>
                <div>
                  @foreach($branchs as $branch)
                  <input type="hidden" name="branches[{{ $loop->index }}][sat_branch]" value="{{ $branch->sat_branch }}">
                  <label style="margin-right: 10px; margin-bottom: 5px;">
                    <input data-id="{{ $branch->sat_branch }}" type="checkbox" name="branches[{{ $loop->index }}][checked]" value="1"> {{ $branch->short_name }}
                  </label>
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="pull-left">
              <button type="button" class="btn btn-default" data-dismiss="modal"> Back</button>
            </div>
            <div class="pull-right">
              <button type="submit" class="btn btn-primary btn-save">Save</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('pageJS')
<script type="text/javascript">
  (function() {
    $('#table-category').DataTable({
      order: [[1, 'asc']],
      columnDefs: [
        {
          targets: 2,
          orderable: false
        }
      ]
    });

    @if($tab == 'cat')
      $('.nav a[href="#cat"]').click();
      $('a[data-target="#modal-new-category"]').css('display', 'inline-block');
    @elseif($tab == 'sub')
      $('.nav a[href="#sub"]').click();
      $('.table-subcategory .empty').css('display', 'none');
      $('a[data-target="#modal-new-subcategory"]').css('display', 'inline-block');
      $('.table-subcategory tbody tr[data-id="{{ $categoryId }}"]').css('display', 'table-row');
    @endif
    
    @if(\Auth::user()->checkAccessById(32, "E"))
    $('#table-config input[type="checkbox"]').click(function() {
      $('.form-update-branch input[name="sat_branch"]').val($(this).attr('data-branch'));
      $('.form-update-branch input[name="cat_id"]').val($(this).attr('data-cat'));
      $('.form-update-branch input[name="subcat_id"]').val($(this).attr('data-subcat'));
      $('.form-update-branch').submit();
    });
    @endif

    $('.nav a').click(function(event) {
      if($(this).attr('href') == '#cat') {
        $("#table-category").parents('.table-responsive').css('display', 'block');
        $('.view-subcategory').css('display', 'none');

        $('a[data-target="#modal-new-category"]').css('display', 'inline-block');
        $('a[data-target="#modal-new-subcategory"]').css('display', 'none');
      }else if($(this).attr('href') == '#sub') {
        $('a[data-target="#modal-new-subcategory"]').css('display', 'inline-block');
        $('a[data-target="#modal-new-category"]').css('display', 'none');
      }else {
        $('a[data-target="#modal-new-category"]').css('display', 'none');
        $('a[data-target="#modal-new-subcategory"]').css('display', 'none');
      }
    });

    $('.btn-view').click(function() {
      $("#table-category").parents('.table-responsive').css('display', 'none');
      $('.view-subcategory').css('display', 'block');
      $('.view-subcategory tbody tr').css('display', 'none');
      $('.view-subcategory tbody tr[data-id="' + $(this).attr('data-id') +  '"]').css('display', 'table-row');

      $('a[data-target="#modal-new-subcategory"]').css('display', 'inline-block');
      $('a[data-target="#modal-new-category"]').css('display', 'none');
    });

    $('.view-subcategory .btn-back').click(function() {
      $("#table-category").parents('.table-responsive').css('display', 'block');
      $('.view-subcategory').css('display', 'none');
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

    $('#modal-new-subcategory .btn-create').click(function(event) {
      $('#modal-new-subcategory .error').remove();

      if($('#modal-new-subcategory input[name="subcat[description]"').val().trim() == '') {
        event.preventDefault();
        $('#modal-new-subcategory input[name="subcat[description]"').parents('.form-group').append('<span class="error">Subcategory cannot be empty</span>');
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
    

    $('.table-subcategory').on("click", ".btn-edit", function(){
      $('#modal-edit-subcategory select[name="subcat[cat_id]"]').val($(this).parents('tr').attr('data-id'));
      $('#modal-edit-subcategory input[name="subcat[description]"]').val($(this).parents('tr').find('.col-name').text());
      $('#modal-edit-subcategory form').attr('action', $(this).attr('data-url'));
      if($(this).attr('data-active') == 1) {
        $('#modal-edit-subcategory input[name="subcat[active]"]').prop('checked', 1);
      }
      $('#modal-edit-subcategory input[type="checkbox"][data-id]').prop('checked', false);
      if($(this).attr('data-branches').replace(/[^0-9\,]/g, '') != "") {
        var branches = $(this).attr('data-branches').replace(/[^0-9\,]/g, '').split(',');
        for(var i = 0; i < branches.length; i++){
          $('#modal-edit-subcategory input[type="checkbox"][data-id="' + branches[i] + '"]').prop('checked', true);
        }
      }

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
        text:  "<div class='delete-text'>You are about to delete subcategory <strong>\"" + $(this).parents('tr').find('.col-name').text() + "\"</strong><br/> Are you sure?</div>",
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
