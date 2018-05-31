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
              data-branches="{{ $sub->branches->map(function($item){ return $item->sat_branch; }) }}" 
              {{ \Auth::user()->checkAccessById(32, "E") ? '' : 'disabled' }}>
              <i class="fas fa-pencil-alt"></i>
            </button>
            <form action="{{ route('pcsubcategories.destroy', $sub) }}" method="POST" style="display: inline-block;">
              {{ csrf_field() }}
              <input type="hidden" name="_method" value="DELETE">
              <input type="hidden" name="corpID" value="{{ $corpID }}">
              <button class="btn btn-danger btn-delete btn-md" type="button"
                {{ \Auth::user()->checkAccessById(32, "D") ? '' : 'disabled' }}>
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
    </tbody>
  </table>
</div>