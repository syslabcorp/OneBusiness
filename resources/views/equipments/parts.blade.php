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
        <th>Quantity</th>
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
        <td><input data-column="description" type="text" name="parts[{{ $row->item_id }}][desc]" class="form-control label-table-min showSuggest" value="{{ $row->item->description }}"></td>
        <td><input type="text" class="form-control"></td>
        <td>
          <input type="text" class="form-control text-center label-table-max" name="brand_name" value="{{ $row->item->Brand->description }}">
        </td>
        <td>
          <label class="form-control text-center label-table-max">{{ $row->item->Category->description }}</label>
        </td>
        <td>
          <label class="form-control text-center label-table-max">{{ $row->item->Vendor->VendorName }}</label>
        </td>
        <td><input type="number" class="form-control label-table-min" name="parts[{{ $row->item_id }}][qty]" value="{{ $row->qty }}"></td>
        
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
      <tr class="newPart" style="display: table-row;">
        <td class="text-center">
          <label class="label-table-min"></label>
          <input type="hidden" class="form-control" name="item_id">
        </td>
        <td><input type="text" name="desc" data-column="description" class="form-control showSuggest"></td>
        <td><input type="text" class="form-control"></td>
        <td>
          <input type="text" data-column="brand" class="form-control label-table-max showSuggest">
        </td>
        <td>
          <input type="text" data-column="category" class="form-control label-table-max showSuggest">
        </td>
        <td>
        <input type="text" data-column="vendor" class="form-control label-table-max showSuggest">
        </td>
        <td>
          <input type="text" class="form-control" name="qty">
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