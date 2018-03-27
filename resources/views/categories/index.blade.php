@extends('layouts.app')
@section('content')
<div class="container-fluid" id="retailItemPCAppWrapper">
  <div class="row">
    <div id="togle-sidebar-sec" class="active">
      <div id="sidebar-togle-sidebar-sec">
          <ul id="sidebar_menu" class="sidebar-nav">
              <li class="sidebar-brand"><a id="menu-toggle" href="#">Menu <span id="main_icon" class="glyphicon glyphicon-align-justify"></span></a></li>
          </ul>
          <div class="sidebar-nav" id="sidebar">
              <div id="treeview_json"></div>
          </div>
      </div>

      <!-- Page content -->
      <div id="page-content-togle-sidebar-sec">
          @if(Session::has('success'))
              <div class="alert alert-success col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('success') !!}</em></div>
          @elseif(Session::has('error'))
              <div class="alert alert-danger col-md-8 col-md-offset-2 alertfade"><span class="glyphicon glyphicon-remove"></span><em> {!! session('error') !!}</em></div>
          @endif

        <div class="col-md-12 col-xs-12" style="margin-top: 20px;">
          <div class="panel panel-default">
            <div class="panel-heading">
              <strong>Document Categories</strong>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-6">
                  <div class="panel panel-default box-category">
                    <div class="panel-heading">
                      <strong>Categories</strong>
                    </div>
                    <div class="panel-body">
                      <table class="table table-bordered table-category">
                        <thead>
                          <tr>
                            <th>Category</th>
                            <th>Series</th>
                          </tr>
                        </thead>
                        <tbody>
                          @foreach($categories as $category)
                          <tr data-delete="{{ route('categories.destroy', $category) }}" class="{{ $loop->index == 0 ? 'selected' : '' }}" data-id='{{ $category->doc_no }}'>
                            <td>{{ $category->description }}</td>
                            <td>{{ $category->series }}</td>
                          </tr>
                          @endforeach
                          @if(!$categories->count())
                          <tr class="empty">
                            <td colspan="2">Not found any categories</td>
                          </tr>
                          @endif
                        </tbody>
                      </table>
                    </div>
                    <div class="panel-footer">
                      <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal-new-category">
                        <i class="glyphicon glyphicon-plus"></i> New
                      </button>
                      <button class="btn btn-primary btn-xs btn-edit">
                        <i class="glyphicon glyphicon-pencil"></i> Edit
                      </button>
                      <form action="" method="POST" style="display: inline-block;">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="corpID" value="{{ $corpID }}">
                        <button class="btn btn-danger btn-xs btn-delete" type="button">
                          <i class="glyphicon glyphicon-trash"></i> Delete
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="panel panel-default box-subcategory">
                    <div class="panel-heading">
                      <strong>Category: <span class="category-name">{{ $categories->first() ? $categories->first()->description : '' }}</span></strong>
                    </div>
                    <div class="panel-body">
                      <table class="table table-bordered table-subcategory">
                        <thead>
                          <tr>
                            <th>Document</th>
                            <th>Expires</th>
                            <th>Multi-Doc</th>
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                    <div class="panel-footer">
                      <button class="btn btn-success btn-xs" data-toggle="modal" data-target="#modal-new-subcategory">
                        <i class="glyphicon glyphicon-plus"></i> New
                      </button>
                      <button class="btn btn-primary btn-xs btn-edit">
                        <i class="glyphicon glyphicon-pencil"></i> Edit
                      </button>
                      <form action="" method="POST" style="display: inline-block;">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="corpID" value="{{ $corpID }}">
                        <button class="btn btn-danger btn-xs btn-delete" type="button">
                          <i class="glyphicon glyphicon-trash"></i> Delete
                        </button>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modal-edit-category">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Edit Category</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Category:</label>
            <input type="text" class="form-control" name="description">
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

<div class="modal fade" id="modal-new-category">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('categories.store') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Category</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Category:</label>
            <input type="text" class="form-control" name="description">
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

<div class="modal fade" id="modal-new-subcategory">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('subcategories.store') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <input type="hidden" name="expires" value="0">
        <input type="hidden" name="mutli_doc" value="0">
        <input type="hidden" name="category_id" value="">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Subcategory</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Subcategory:</label>
            <input type="text" class="form-control" name="description">
          </div>
          <div >
            <label style="margin-right: 30px;">
              <input type="checkbox" name="expires" value="1"> with Expiry
            </label>
            <label>
              <input type="checkbox" name="mutli_doc" value="1"> Multi-Document
            </label>
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
      <form action="" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="PUT">
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <input type="hidden" name="expires" value="0">
        <input type="hidden" name="mutli_doc" value="0">
        <input type="hidden" name="category_id" value="">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Subcategory</h4>
        </div>
        <div class="modal-body">
          <div class="form-group">
            <label>Subcategory:</label>
            <input type="text" class="form-control" name="description">
          </div>
          <div >
            <label style="margin-right: 30px;">
              <input type="checkbox" name="expires" value="1"> with Expiry
            </label>
            <label>
              <input type="checkbox" name="mutli_doc" value="1"> Multi-Document
            </label>
          </div>
        </div>
        <div class="modal-footer">
          <div class="row">
            <div class="pull-left">
              <button type="button" class="btn btn-default" data-dismiss="modal"> Back</button>
            </div>
            <div class="pull-right">
              <button type="submit" class="btn btn-primary btn-create">Update</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@section('footer-scripts')
<script type="text/javascript">
  (function() {
    $('#modal-new-category .btn-create').click(function(event) {
      $('#modal-new-category .error').remove();
      if($('#modal-new-category input[name="description"]').val().trim() == '') {
        event.preventDefault();
        $('#modal-new-category .form-group').append('<span class="error">Please input category</span>');
      }
    });

    $('#modal-new-subcategory .btn-create').click(function(event) {
      $('#modal-new-subcategory input[name="category_id"]').val($('.table-category tbody tr.selected').attr('data-id'));
      $('#modal-new-subcategory .error').remove();
      if($('#modal-new-subcategory input[name="description"]').val().trim() == '') {
        event.preventDefault();
        $('#modal-new-subcategory .form-group').append('<span class="error">Please input subcategory</span>');
      }
    });

    $('#modal-edit-category .btn-save').click(function(event) {
      $('#modal-edit-category .error').remove();
      if($('#modal-edit-category input[name="description"]').val().trim() == '') {
        event.preventDefault();
        $('#modal-edit-category .form-group').append('<span class="error">Please input category</span>');
      }
    });
    

    $('.table-category tbody tr:not(.empty)').click(function() {
      $('.table-category tbody tr').removeClass('selected');
      $(this).addClass('selected');
      $('.box-subcategory .category-name').text($('.table-category tbody tr.selected td:eq(0)').text());
      getViewForSubcategories();
    });

    $('.table-subcategory tbody').on('click', 'tr:not(.empty)', function() {
      $('.table-subcategory tbody tr').removeClass('selected');
      $(this).addClass('selected');
    });

    $('.box-category').on("click", ".btn-edit", function(){
      if($('.table-category tbody tr.selected').length == 0) {
        return;
      }

      $('#modal-edit-category input[name="description"]').val($('.table-category tbody tr.selected td:eq(0)').text());
      $('#modal-edit-category form').attr('action', $('.table-category tbody tr.selected').attr('data-delete'));
      $('#modal-edit-category').modal('show');
    });
    

    $('.box-subcategory').on("click", ".btn-edit", function(){
      if($('.table-subcategory tbody tr.selected').length == 0) {
        return;
      }

      $('#modal-edit-subcategory input[name="description"]').val($('.table-subcategory tbody tr.selected td:eq(0)').text());
      $('#modal-edit-subcategory input[type="checkbox"][name="expires"]').prop('checked', $('.table-subcategory tbody tr.selected td:eq(1) input').is(':checked'));
      $('#modal-edit-subcategory input[type="checkbox"][name="mutli_doc"]').prop('checked', $('.table-subcategory tbody tr.selected td:eq(2) input').is(':checked'));
      $('#modal-edit-subcategory form').attr('action', $('.table-subcategory tbody tr.selected').attr('data-delete'));
      $('#modal-edit-subcategory').modal('show');
    });

    $('.box-category').on("click", ".btn-delete", function(){
      var self = $(this);
      if($('.table-category tbody tr.selected').length == 0) {
        return;
      }

      swal({
        title: "<div class='delete-title'>Confirm Delete</div>",
        text:  "<div class='delete-text'>You are about to delete category <strong>\"" + $('.table-category tbody tr.selected td:eq(0)').text() + "\"</strong><br/> Are you sure?</div>",
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
          self.parents('form').attr('action',  $('.table-category tbody tr.selected').attr('data-delete'));
          self.parents('form').submit();
        }
      });
    });

    $('.box-subcategory').on("click", ".btn-delete", function(){
      var self = $(this);
      if($('.table-subcategory tbody tr.selected').length == 0) {
        return;
      }

      swal({
        title: "<div class='delete-title'>Confirm Delete</div>",
        text:  "<div class='delete-text'>You are about to delete subcategory <strong>\"" + $('.table-subcategory tbody tr.selected td:eq(0)').text() + "\"</strong><br/> Are you sure?</div>",
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
          self.parents('form').attr('action',  $('.table-subcategory tbody tr.selected').attr('data-delete'));
          self.parents('form').submit();
        }
      });
    });

    function getViewForSubcategories() {
      $.ajax({
        type: 'GET',
        url: '{{ route("subcategories.index") }}',
        data: {id: $('.table-category tbody tr.selected').attr('data-id'), corpID: '{{ $corpID }}'},
        success: function(res) {
          $('.table-subcategory tbody').html(res);
        }
      });
    }
    getViewForSubcategories();
  })();
</script>
@endsection