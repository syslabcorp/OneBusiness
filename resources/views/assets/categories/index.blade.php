@extends('layouts.custom')

@section('content')
  <div class="box-content">
    <div class="col-md-12">
      <div class="rown">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="rown">
              <div class="col-xs-9">
                <h5><strong>Equipment Category</strong></h5>
              </div>
              <div class="col-xs-3 text-right" style="margin-top: 10px;">
                @if(\Auth::user()->checkAccessById(55, 'A'))
                  <a data-toggle="modal" data-target="#addCategoryModal" href="javascript:void(0)">Add Category</a>
                @endif
              </div>
            </div>
          </div>
          <div class="panel-body">
            <div class="tablescroll">
              <div class="table-responsive">
                <table class="stripe table table-bordered nowrap table-categories" width="100%">
                  <thead>
                    <tr>
                      <th>Category ID</th>
                      <th>Description</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($categories as $cat)
                    <tr data-id="{{$cat->cat_id}}">
                      <td class="text-center">{{ $cat->cat_id }}</td>
                      <td>{{ $cat->description }}</td>
                      <td class="text-center">
                        <button data-toggle="modal" data-target="#editCategoryModal" onclick="editCategory({{ $cat->cat_id }}, '{{ $cat->description }}')" class="btn btn-primary btn-md" {{ \Auth::user()->checkAccessById(55, 'E') ? '' : 'checked' }}>
                          <i class="fas fa-pencil-alt"></i>
                        </button>
                        <button onclick="deleteCategory({{ $cat->cat_id }}, '{{ $cat->description }}')"  class="btn btn-danger btn-md"
                          {{ \Auth::user()->checkAccessById(55, 'D') ? '' : 'checked' }}>
                          <i class="fas fa-trash-alt"></i>
                        </button>
                      </td>
                    </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Modal add category-->
  <div class="modal fade" id="addCategoryModal" role="dialog">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title">New Equipment Category</h5>
        </div>
        <form action="{{ route('asset-categories.store') }}" method="post" role="form">
          <div class="modal-body">
            {{ csrf_field() }}
            <div class="form-group">
              <div class="rown">
                <div class="col-xs-9 col-xs-offset-1">
                  <div class="rown">
                    <div class="col-xs-3" style="padding-top: 9px;">
                      <label for="">Category:</label>
                    </div>
                    <div class="col-xs-9">
                      <input type="text" class="form-control" name="description" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="rown">
              <div class="col-xs-6">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fas fa-reply"></i>&nbsp;&nbsp;Back</button>
              </div>
              <div class="col-xs-6 text-right">
                <button type="submit" class="btn btn-primary">Create</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--/ Modal add category-->

  <!-- Modal edit category-->
  <div class="modal fade" id="editCategoryModal" role="dialog">
    <div class="modal-dialog ">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h5 class="modal-title">Edit Equipment Category</h5>
        </div>
        <form action="{{ route('asset-categories.index') }}" method="post" role="form">
          <div class="modal-body">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="PUT">
            <div class="form-group">
              <div class="rown">
                <div class="col-xs-9 col-xs-offset-1">
                  <div class="rown">
                    <div class="col-xs-3" style="padding-top: 9px;">
                      <label for="">Category:</label>
                    </div>
                    <div class="col-xs-9">
                      <input type="text" class="form-control" name="description" required>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <div class="rown">
              <div class="col-xs-6">
                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><i class="fas fa-reply"></i>&nbsp;&nbsp;Back</button>
              </div>
              <div class="col-xs-6 text-right">
                <button type="submit" class="btn btn-primary">Save</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  </div>
  <!--/ Modal edit category-->
@endsection

@section('pageJS')
  <script type="text/javascript">
    (()=> { 
      let categoriesTable = $('.table-categories').dataTable()
      deleteCategory = (id, desc) => {
        showConfirmMessage(
          'Are you sure you want to delete <strong>' + id + ' - ' + desc + '</strong>'   , 'Confirm Delete', () => {
            $.ajax({
              type: 'DELETE',
              url:  '{{ route('asset-categories.index') }}/' + id,
              success: (res) => {
                $('.table-categories tbody tr[data-id="' + id + '"]' ).remove()
              }
            })
          })
      }

      editCategory = (id, desc) => {
        $('#editCategoryModal input[name="description"]').val(desc)
        $('#editCategoryModal form').attr("action",'{{ route('asset-categories.index') }}/' + id)
      }
    })()
  </script>
@endsection