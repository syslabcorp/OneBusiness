@foreach($items as $item)
<tr class="rowTR" data-parent="{{ $item->asset_id }}">
    <td>
        <input type="text" class="form-control" name="parts[{{ $item->asset_id }}][item_id][{{ $loop->index+1 }}]" value="{{ $item->item ? $item->item->description : '' }}" readonly>
    </td>
    <td>
    <input type="number" class="form-control text-center quantity" name="parts[{{ $item->asset_id }}][qty][{{ $loop->index+1 }}]" value="1">
    </td>
</tr>
@endforeach
<input type="hidden" class="count" value="{{ count($items)+1 }}">