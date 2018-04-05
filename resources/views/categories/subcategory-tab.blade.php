<div class="table-responsive">
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
        @foreach($category->subcategories()->where('deleted', '=', 0)->orderBy('description', 'asc')->get() as $sub)
        <tr data-id="{{ $category->cat_id }}">
          @if($loop->index == 0)
          <td rowspan="{{ $category->subcategories()->where('deleted', '=', 0)->count() }}">{{ $category->description }}</td>
          @endif
          <td>{{ $sub->subcat_id }}</td>
          <td class="col-name">{{ $sub->description }}</td>
          <td>
            <button class="btn btn-primary btn-edit btn-md" data-active="{{ $sub->active }}" data-url="{{ route('pcsubcategories.update', $sub) }}"
              data-branches="{{ $sub->branches->map(function($item){ return $item->sat_branch; }) }}">
              <i class="glyphicon glyphicon-pencil"></i>
            </button>
            <form action="{{ route('pcsubcategories.destroy', $sub) }}" method="POST" style="display: inline-block;">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="DELETE">
              <input type="hidden" name="corpID" value="{{ $corpID }}">
              <button class="btn btn-danger btn-delete btn-md" type="button">
                <i class="glyphicon glyphicon-trash"></i>
              </button>
            </form>
          </td>
        </tr>
        @endforeach
        @if($category->subcategories()->where('deleted', '=', 0)->count() == 0)
        <tr data-id="{{ $category->cat_id }}" style="display: none;">
          <td>{{ $category->description }}</td>
          <td colspan="3">No subcategories</td>
        </tr>
        @endif
      @endforeach
      <tr class="empty">
        <td colspan="4">Please select category</td>
      </tr>
    </tbody>
  </table>
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