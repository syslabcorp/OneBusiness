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
            <input type="checkbox" onclick="return false;" {{ $sat ? 'checked' : '' }} >
          </td>
          @endforeach
        </tr>
        @endforeach
      @endforeach
    </tbody>
  </table>
</div>