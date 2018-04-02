<table class="table table-bordered" id="table-config">
  <thead>
    <tr>
      <th>Categories</th>
      <th>Subcategories</th>
      <th>CEB</th>
      <th>GSC</th>
      <th>CDO</th>
      <th>DVO</th>
    </tr>
  </thead>
  <tbody>
    @foreach($categories as $category)
      @foreach($category->subcategories()->orderBy('description', 'asc')->get() as $sub)
      <tr>
        @if($loop->index == 0)
        <td rowspan="{{ $category->subcategories()->count() }}">{{ $category->description }}</td>
        @endif
        <td>{{ $sub->description }}</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      @endforeach
    @endforeach
  </tbody>
</table>