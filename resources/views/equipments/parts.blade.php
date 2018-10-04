<div class="table-responsive table-parts" style="{{ count($partItems) ? '' : 'display: none;'}}">
  <div class="text-right">
    <button {{ $equipment->asset_id ? 'disabled' : '' }} type="button" onclick="addNewPart()" 
      class="btn btn-success btn-sm btnAddRow" style="margin-bottom: 10px;">Add Row (F2)</button>
  </div>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th style="width: 80px">Part No.</th>
        <th style="width: 150px">Part Name</th>
        <th>Warranty Status</th>
        <th style="width: 150px">Brand</th>
        <th style="width: 150px">Category</th>
        <th>P.O No.</th>
        <th>Quantity</th>
        <th>Cost</th>
        <th>Vendor</th>
        <th>Consumable</th>
        <th>Active</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @if(count($partItems))
      @foreach($partItems as $row)
      <tr class="partRow">
        <td class="text-center">
          {{ $row->item->item_id }}
          <input type="hidden" name="parts[{{ $row->item_id }}][item_id]" value="{{ $row->item_id }}">
        </td>
        <td><input type="text" name="parts[{{ $row->item_id }}][desc]" class="form-control" value="{{ $row->item->description }}"></td>
        <td><input type="text" class="form-control"></td>
        <td>
          <select name="parts[{{ $row->item_id }}][brand_id]" class="form-control">
            @foreach($brands as $item)
              <option value="{{ $item->brand_id }}" {{ $item->brand_id == $row->item->brand_id ? 'selected' : '' }}>{{ $item->description }}</option>
            @endforeach
          </select>
        </td>
        <td>
          <select name="parts[{{ $row->item_id }}][cat_id]" class="form-control">
            @foreach($categories as $item)
              <option value="{{ $item->cat_id }}" {{ $item->cat_id == $row->item->cat_id ? 'selected' : '' }}>{{ $item->description }}</option>
            @endforeach
          </select>
        </td>
        <td><input type="text" class="form-control"></td>
        <td><input type="number" class="form-control" name="parts[{{ $row->item_id }}][qty]" value="{{ $row->qty }}"></td>
        <td><input type="text" class="form-control"></td>
        <td>
          <select name="parts[{{ $row->item_id }}][supplier_id]" class="form-control">
            @foreach($vendors as $item)
            <option value="{{ $item->Supp_ID }}" {{ $item->Supp_ID == $row->item->supplier_id ? 'selected' : '' }}>{{ $item->VendorName }}</option>
            @endforeach
          </select>
        </td>
        <td class="text-center"><input {{ $row->item->consumable ? 'checked' : '' }} type="checkbox" value="1" name="parts[{{ $row->item_id }}][consumable]"></td>
        <td class="text-center"><input {{ $row->item->isActive ? 'checked' : '' }} type="checkbox" value="1" name="parts[{{ $row->item_id }}][isActive]"></td>
        <td style="width: 100px;">
          <button type="button" class="btn btn-info btn-md btnEditRow" disabled>
            <i class="fas fa-pencil-alt"></i>
          </button>
          <button type="button" class="btn btn-primary btn-md btnSaveRow" style="display: none;">
            <i class="fas fa-check"></i>
          </button>
          <button type="button" class="btn btn-danger btn-md btnRemoveRow" disabled>
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
      @endforeach
      @endif
      <tr class="newPart" style="display: none;">
        <td class="text-center"></td>
        <td><input type="text" name="desc" class="form-control"></td>
        <td><input type="text" class="form-control"></td>
        <td>
          <select name="brand_id" class="form-control">
            @foreach($brands as $item)
              <option value="{{ $item->brand_id }}">{{ $item->description }}</option>
            @endforeach
          </select>
        </td>
        <td>
          <select name="cat_id" class="form-control">
            @foreach($categories as $item)
              <option value="{{ $item->cat_id }}">{{ $item->description }}</option>
            @endforeach
          </select>
        </td>
        <td><input type="text" class="form-control"></td>
        <td><input type="number" class="form-control" name="qty"></td>
        <td><input type="text" class="form-control"></td>
        <td>
          <select name="supplier_id" class="form-control">
            @foreach($vendors as $item)
            <option value="{{ $item->Supp_ID }}">{{ $item->VendorName }}</option>
            @endforeach
          </select>
        </td>
        <td class="text-center"><input value="1" type="checkbox" name="consumable"></td>
        <td class="text-center"><input checked value="1" type="checkbox" name="isActive"></td>
        <td style="width: 100px;">
          <button type="button" class="btn btn-info btn-md btnEditRow" style="display: none;">
            <i class="fas fa-pencil-alt"></i>
          </button>
          <button type="button" class="btn btn-primary btn-md btnSaveRow">
            <i class="fas fa-check"></i>
          </button>
          <button type="button" class="btn btn-danger btn-md btnRemoveRow">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</div>