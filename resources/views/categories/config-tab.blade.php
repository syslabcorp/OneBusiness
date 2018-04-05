<div class="table-responsive">
  <table class="table table-bordered" id="table-config">
    <thead>
      <tr>
        <th>Categories</th>
        <th>Subcategories</th>
        @foreach($branchs as $branch)
        <th class="text-center">{{ $branch->short_name }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($categories as $category)
        @foreach($category->subcategories()->where('deleted', '=', 0)->orderBy('description', 'asc')->get() as $sub)
        <tr>
          @if($loop->index == 0)
          <td rowspan="{{ $category->subcategories()->where('deleted', '=', 0)->count() }}">{{ $category->description }}</td>
          @endif
          <td>{{ $sub->description }}</td>
          @foreach($branchs as $branch)
          @php $sat = $sub->branches()->where('sat_branch', '=', $branch->sat_branch)->first() @endphp
          <td class="text-center">
            <input type="checkbox" data-branch="{{ $branch->sat_branch }}" onclick="{{ \Auth::user()->checkAccessById(32, "E") ? '' : 'return false;' }}"
              data-cat="{{ $category->cat_id }}" data-subcat="{{ $sub->subcat_id }}" {{ $sat ? 'checked' : '' }} >
          </td>
          @endforeach
        </tr>
        @endforeach
      @endforeach
    </tbody>
  </table>
</div>
<form action="{{ route('pccategories.updateBranch') }}" class="form-update-branch" method="POST">
  {{ csrf_field() }}
  <input type="hidden" name="corpID" value="{{ $corpID }}">
  <input type="hidden" name="sat_branch" value="">
  <input type="hidden" name="cat_id" value="">
  <input type="hidden" name="subcat_id" value="">
</form>