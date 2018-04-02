<table class="table table-bordered table-striped table-subcategory">
  <thead>
    <tr>
      <th>Categories</th>
      <th>ID</th>
      <th>Subcategories</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    @foreach($categories as $category)
      @foreach($category->subcategories()->orderBy('description', 'asc')->get() as $sub)
      <tr data-id="{{ $category->cat_id }}" style="display: none;">
        @if($loop->index == 0)
        <td rowspan="{{ $category->subcategories()->count() }}">{{ $category->description }}</td>
        @endif
        <td>{{ $sub->subcat_id }}</td>
        <td>{{ $sub->description }}</td>
        <td>
          <button class="btn btn-primary btn-sm btn-edit">
            <i class="glyphicon glyphicon-pencil"></i>
          </button>
          <form action="{{ route('pcsubcategories.destroy', $sub) }}" method="POST" style="display: inline-block;">
            {{ csrf_field() }}
            <input type="hidden" name="_method" value="DELETE">
            <input type="hidden" name="corpID" value="{{ $corpID }}">
            <button class="btn btn-danger btn-sm btn-delete" type="button">
              <i class="glyphicon glyphicon-trash"></i>
            </button>
          </form>
        </td>
      </tr>
      @endforeach
    @endforeach
    <tr class="empty">
      <td colspan="4">Please select category</td>
    </tr>
  </tbody>
</table>

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
        <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Category:</label>
                <input type="text" class="form-control" name="cat[description]">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label style="width: 100%">&nbsp;</label>
                <label for="">
                  <input type="checkbox" name="cat[active]" value="1"> Active
                </label>
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

<div class="modal fade" id="modal-new-category">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <form action="{{ route('pccategories.store') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="corpID" value="{{ $corpID }}">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Add Category</h4>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group">
                <label>Category:</label>
                <input type="text" class="form-control" name="cat[description]">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group">
                <label style="width: 100%">&nbsp;</label>
                <label for="">
                  <input type="checkbox" name="cat[active]" value="1"> Active
                </label>
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