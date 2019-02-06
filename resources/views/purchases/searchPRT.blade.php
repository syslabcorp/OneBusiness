<tr class="purchaseRow">
    <td class="text-center">
        <label class="label-table-min index">1</label>
    </td>
    <td>
        <select name="" class="form-control parts">
            <option value=""> -- select -- </option>
            @foreach($itemparts as $item)
                <option value="{{ $item->item_id }}">{{ $item->description}}</option>
            @endforeach
        </select>
    </td>
    <td>
        <input type="number" name="" class="form-control text-center quantity" value="1">
    </td>
  
    <td style="width: 100px;" class="rowspan">
        <button type="button" class="btn btn-danger btn-md btnRemoveRow center-block">
        <i class="fas fa-trash-alt"></i>
        </button>
    </td>
</tr>