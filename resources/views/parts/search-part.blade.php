
<div class="listPart">
    <table>
        <thead>
            <th>Part ID</th>
            <th>Part Name</th>
            <th>Brand</th>
            <th>Category</th>
            <th>Vendor</th>
            <th>Consumable</th>
            <th>W/ Serial#</th>
            <th>Active</th>
            <th>Last Cost</th>
        </thead>
        <tbody>
        @foreach($items as $item)
        <tr>
            <td data-id="{{ $item->item_id }}">{{ $item->item_id }}</td>
            <td>{{ $item->description }}</td>
            <td data-id="{{ $item->Brand ? $item->Brand->description : 'Null' }}">{{ $item->Brand ? $item->Brand->description : 'Data Null'}}</td>
            <td data-id="{{ $item->Category ? $item->Category->description : 'Null' }}">{{ $item->Category ? $item->Category->description : 'Data Null' }}</td>
            <td data-id="{{ $item->Vendor ? $item->Vendor->VendorName : 'Null' }}">{{ $item->Vendor ? $item->Vendor->VendorName : 'Data Null' }}</td>
            <td class="text-center"><input type="checkbox" value="1" {{ $item->consumable == 1 ? 'checked' : '' }}></td>
            <td class="text-center"><input type="checkbox" value="1" {{ $item->with_serialno == 1 ? 'checked' : '' }}></td>
            <td class="text-center"><input type="checkbox" value="1" {{ $item->isActive == 1 ? 'checked' : '' }}></td>
            <td data-id="{{ $item->LastCost }}">{{ $item->LastCost }}</td>
        </tr>
        @endforeach
        </tbody>
    </table>
</div>
