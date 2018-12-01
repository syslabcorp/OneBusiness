<?php
    $sumQty = 0;
    $sumServedQty = 0;
?>
@foreach($items as $item)
<?php
  $sumQty += $item->Qty;
  $sumServedQty += $item->ServedQty;
?> 
<tr class="stockRow PO">
    <td class="text-center">
        <input type="hidden" class="item_id" name="stocks[{{ $loop->index+1 }}][item_id]" value="{{ $item->item_id }}" >
        <input type="text" class="form-control text-center item_code" name="stocks[{{ $loop->index+1 }}][item_code]" value="{{ $item->ItemCode }}" >
    </td>
    <td><input data-column="" type="text" name="stocks[{{ $loop->index+1 }}][product_line]" class="form-control text-center showSuggest" value="{{ $item->item->product_line ? $item->item->product_line->Product : '' }}" autocomplete="off"></td>
    <td><input type="text" class="form-control text-center showSuggest" name="stocks[{{ $loop->index+1 }}][brand]" value="{{ $item->brand ? $item->brand->Brand : '' }}" autocomplete="off"></td>
    <td class="text-center"><label>{{ $item->item->Description }}</label></td>
    <td class="text-center"><label>{{ $item->ServedQty }}</label></td>
    <td><input type="text" class="form-control text-center cost" name="stocks[{{ $loop->index+1 }}][cost]" value="{{ $item->cost }}" autocomplete="off"></td>
    <td><input type="number" class="form-control text-center  quantity" name="stocks[{{ $loop->index+1 }}][qty]" value="{{ $item->Qty - $item->ServedQty }}" autocomplete="off"></td>
    <td><input type="text" class="form-control text-center subtotal" name="stocks[{{ $loop->index+1 }}][subtotal]" value="" autocomplete="off"></td>
    <td class="text-center"><label>{{ $item->item->Unit }}</label></td>

    <td style="width: 100px;">
        <button type="button" class="btn btn-danger btn-md btnRemoveRow center-block" >
        <i class="fas fa-trash-alt"></i>
        </button>
    </td>
</tr>
@endforeach
<input type="hidden" class="Qty" name="Qty" value="{{ $sumQty }}" >
<input type="hidden" class="servedQty" name="servedQty" value="{{ $sumServedQty }}" >
