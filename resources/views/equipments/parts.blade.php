<div class="table-responsive table-parts" style="{{ count($partItems) ? '' : 'display: none;'}}">
  <div class="text-right">
    <button {{ $equipment->asset_id ? 'disabled' : '' }} type="button"  
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
        <th>Vendor</th>
        <th>Last Cost</th>
        <th>Quantity</th>
        <th>Total Cost</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @if(count($partItems))
      @foreach($partItems as $row)
      @php
        if (is_array($row)) $row = new \App\Models\Equip\Detail($row);
        if (!$row->item_id) continue;
      @endphp
      <tr class="partRow">
        <td class="text-center">
          <label for="">{{ $row->item_id }}</label>
          <input type="hidden" class="item_id" name="parts[{{ $row->item_id }}][item_id]" value="{{ $row->item_id }}" >
        </td>
        <td><input data-column="description" type="text" name="parts[{{ $row->item_id }}][desc]" class="form-control text-center label-table-min showSuggest" value="{{ $row->item->description }}" autocomplete="off"></td>
        <td><input type="text" class="form-control" autocomplete="off"></td>
        <td>
          <input type="text" class="form-control text-center label-table-max" name="brand_name" value="{{ $row->item->Brand->description }}" autocomplete="off">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-max" name="category_name" value="{{ $row->item->Category->description }}" autocomplete="off">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-max" name="vendor_name" value="{{ $row->item->Vendor ? $row->item->Vendor->VendorName : '0.00'  }}" autocomplete="off">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min lastcost" name="parts[{{ $row->item_id }}][lastcost]" value="{{ $row->item ? number_format($row->item->LastCost, 2, '.', '') : '0.00'  }}" autocomplete="off">
        </td>
        <td>
          <input type="number" class="form-control text-center label-table-min quantity" name="parts[{{ $row->item_id }}][qty]" value="{{ $row->qty }}" autocomplete="off">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min totalcost" name="parts[{{ $row->item_id }}][totalcost]" value="{{ number_format($row->qty * $row->item->LastCost, 2, '.', '') }}" autocomplete="off">
        </td>
        <td style="width: 100px;">
          <button type="button" class="btn btn-danger btn-md btnRemoveRow center-block" {{ $equipment->asset_id ? 'disabled' : '' }}>
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
      @endforeach
      @endif
      <tr class="newPart" style="display: none;">
        <td class="text-center">
          <label class="label-table-min"></label>
          <input type="hidden" class="form-control item_id" name="item_id">
        </td>
        <td><input type="text" name="desc" data-column="description" class="form-control text-center showSuggest" autocomplete="off"></td>
        <td><input type="text" class="form-control" autocomplete="off"></td>
        <td>
          <input type="text" data-column="brand" class="form-control text-center label-table-max showSuggest" autocomplete="off">
        </td>
        <td>
          <input type="text" data-column="category" class="form-control text-center label-table-max showSuggest" autocomplete="off">
        </td>
        <td>
          <input type="text" data-column="vendor" class="form-control text-center label-table-max showSuggest" autocomplete="off">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min lastcost" name="lastcost" autocomplete="off">
        </td>
        <td>
          <input type="number" class="form-control text-center label-table-min quantity" name="qty" value="1" autocomplete="off">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min totalcost" name="totalcost" autocomplete="off">
        </td>
        <td style="width: 100px;">
          <button type="button" class="btn btn-danger btn-md btnRemoveRow center-block">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</div>