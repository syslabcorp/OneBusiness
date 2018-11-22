<div class="listStock" style="z-index: 1;">
    <table >
        <thead>
            <th class="text-center">Item_ID</th>
            <th class="text-center">ItemCode</th>
            <th class="text-center">Prod_Line</th>
            <th class="text-center">Brand</th>
            <th class="text-center">Description</th>
            <th class="text-center">Unit</th>
            <th class="text-center">Packaging</th>
            <th class="text-center">Threshold</th>
            <th class="text-center">Multiplier</th>
            <th class="text-center">Type</th>
            <th class="text-center">Min_Level</th>
            <th class="text-center">Active</th>
            <th class="text-center">LastCost</th>
            <th class="text-center">Barcode</th>
            <th class="text-center">TrackThis</th>
            <th class="text-center">Print_This</th>
        </thead>
        <tbody>
        @foreach($items as $item)
        <tr>
            <td class="text-center" data-id="{{ $item->item_id }}">{{ $item->item_id }}</td>
            <td class="text-center" data-id="{{ $item->ItemCode }}">{{ $item->ItemCode }}</td>
            <td class="text-center" data-id="{{ $item->product_line ? $item->product_line->Product : '' }}">{{ $item->product_line ? $item->product_line->Product : '' }}</td>
            <td class="text-center" data-id="{{ $item->brand ? $item->brand->Brand : '' }}">{{ $item->brand ? $item->brand->Brand : '' }}</td>
            <td class="text-center" data-id="{{ $item->Description }}">{{ $item->Description }}</td>
            <td class="text-center" data-id="{{ $item->Unit }}">{{ $item->Unit }}</td>
            <td class="text-center" data-id="{{ $item->Packaging }}">{{ $item->Packaging }}</td>
            <td class="text-center" data-id="{{ $item->Threshold }}">{{ $item->Threshold }}</td>
            <td class="text-center" data-id="{{ $item->Multiplier }}">{{ $item->Multiplier }}</td>
            <td class="text-center" data-id="{{ $item->Type }}">{{ $item->Type }}</td>
            <td class="text-center" data-id="{{ $item->Min_Level }}">{{ $item->Min_Level }}</td>
            <td class="text-center" data-id="{{ $item->Active }}">{{ $item->Active }}</td>
            <td class="text-center" data-id="{{ $item->LastCost }}">{{ $item->LastCost }}</td>
            <td class="text-center" data-id="{{ $item->barcode }}">{{ $item->barcode }}</td>
            <td class="text-center" data-id="{{ $item->TrackThis }}">{{ $item->TrackThis }}</td>
            <td class="text-center" data-id="{{ $item->Print_This }}">{{ $item->Print_This }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
