@foreach($items as $item)
<tr class="rowTR" data-parent="{{ $item->asset_id }}">
    <td class="text-center">
        <label for="">{{ $item->item ? $item->item->description : '' }}</label>
        <input type="hidden" class="form-control" name="parts[{{ $item->asset_id }}][item_id][{{ $loop->index+1 }}]" value="{{ $item->item_id }}">
    </td>
    <td class="text-center">
        <input type="number" class="form-control text-center quantity" name="parts[{{ $item->asset_id }}][qty][{{ $loop->index+1 }}]" value="0">
    </td>
</tr>
@endforeach
<input type="hidden" class="count" value="{{ count($items)+1 }}">