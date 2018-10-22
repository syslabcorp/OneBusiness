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
      <tr class="partRow">
        <td class="text-center">
          {{ $row->item->item_id }}
          <input type="hidden" name="parts[{{ $row->item_id }}][item_id]" value="{{ $row->item_id }}">
        </td>
        <td><input data-column="description" type="text" name="parts[{{ $row->item_id }}][desc]" class="form-control text-center label-table-min showSuggest" value="{{ $row->item->description }}"></td>
        <td><input type="text" class="form-control"></td>
        <td>
          <input type="text" class="form-control text-center label-table-max" name="brand_name" value="{{ $row->item->Brand->description }}">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-max" name="category_name" value="{{ $row->item->Category->description }}">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-max" name="vendor_name" value="{{ $row->item->Vendor ? $row->item->Vendor->VendorName : 'Data Null'  }}">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min" name="last_cost" value="{{ $row->item ? $row->item->LastCost : 'Data Null'  }}">
        </td>
        <td>
          <input type="number" class="form-control text-center label-table-min quantity" name="parts[{{ $row->item_id }}][qty]" value="{{ $row->qty }}">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min" name="total_cost" value="{{ $row->qty*$row->item->LastCost }}">
        </td>

        
        <td style="width: 100px;">
          <button type="button" class="btn btn-primary btn-md btnSaveRow" style="display: none;">
            <i class="fas fa-check"></i>
          </button>
          <button type="button" class="btn btn-danger btn-md btnRemoveRow center-block" disabled>
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
      @endforeach
      @endif
      <tr class="newPart" style="display: {{ $equipment->asset_id ? 'none' : 'table-row' }};">
        <td class="text-center">
          <label class="label-table-min"></label>
          <input type="hidden" class="form-control" name="item_id">
        </td>
        <td><input type="text" name="desc" data-column="description" class="form-control text-center showSuggest"></td>
        <td><input type="text" class="form-control"></td>
        <td>
          <input type="text" data-column="brand" class="form-control text-center label-table-max showSuggest">
        </td>
        <td>
          <input type="text" data-column="category" class="form-control text-center label-table-max showSuggest">
        </td>
        <td>
          <input type="text" data-column="vendor" class="form-control text-center label-table-max showSuggest">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min" name="lastcost">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min quantity" name="qty" value="1">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min" name="totalcost">
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