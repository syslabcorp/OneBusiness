@foreach($category->subcategories()->where('Deleted', '=', 0)->orderBy('description', 'asc')->get() as $cat)
<tr data-delete="{{ route('subcategories.destroy', $cat) }}" 
  class="{{ $subcategoryId && $subcategoryId == $cat->subcat_id || !$subcategoryId && $loop->index == 0 ? 'selected' : '' }}">
  <td>{{ $cat->description }}</td>
  <td class="text-center">
      <input type="checkbox" onclick="return false;" {{ $cat->expires == 1 ? 'checked' : '' }} >
  </td>
  <td class="text-center">
      <input type="checkbox" onclick="return false;" {{ $cat->multi_doc == 1 ? 'checked' : '' }}>
  </td>
</tr>
@endforeach
@if(!$category->subcategories()->where('Deleted', '=', 0)->count())
  <tr class="empty">
    <td colspan="3">No subcategories</td>
  </tr>
@endif