<div class="listStock" style="z-index: 1;">
    <table >
        <thead>
            <th class="text-center">ItemCode</th>
            <th class="text-center">Prod_Line</th>
            <th class="text-center">Brand</th>
            <th class="text-center">Description</th>
            <th class="text-center">Unit</th>
            <th class="text-center">Unit Cost</th>
        <tbody>
        @foreach($items as $item)
        <tr>
            <input type="hidden" name="item_id" value="{{ $item->item_id }}">
            <td class="text-center" data-id="{{ $item->ItemCode }}">{{ $item->ItemCode }}</td>
            <td class="text-center" data-id="{{ $item->product_line ? $item->product_line->Product : '' }}">{{ $item->product_line ? $item->product_line->Product : '' }}</td>
            <td class="text-center" data-id="{{ $item->brand ? $item->brand->Brand : '' }}">{{ $item->brand ? $item->brand->Brand : '' }}</td>
            <td class="text-center" data-id="{{ $item->Description }}">{{ $item->Description }}</td>
            <td class="text-center" data-id="{{ $item->Unit }}">{{ $item->Unit }}</td>
            <td class="text-center" data-id="{{ $item->LastCost }}">{{ $item->LastCost }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
