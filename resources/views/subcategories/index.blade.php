@foreach($category->subcategories()->where('Deleted', '=', 0)->orderBy('description', 'asc')->get() as $category)
<tr data-delete="{{ route('subcategories.destroy', $category) }}" class="{{ $loop->index == 0 ? 'selected' : '' }}">
  <td>{{ $category->description }}</td>
  <td class="text-center">
      <input type="checkbox" onclick="return false;" {{ $category->expires == 1 ? 'checked' : '' }} >
  </td>
  <td class="text-center">
      <input type="checkbox" onclick="return false;" {{ $category->mutli_doc == 1 ? 'checked' : '' }}>
  </td>
</tr>
@endforeach
  @if(!$category->subcategories()->where('Deleted', '=', 0)->count())
<tr class="empty">
  <td colspan="3">Not found any subcategories</td>
</tr>
@endif