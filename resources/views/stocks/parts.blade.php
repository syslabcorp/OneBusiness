<div class="table-responsive table-parts" style="">
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th style="max-width: 100px;margin: 0px;box-sizing: border-box;-moz-box-sizing: border-box;-webkit-box-sizing: border-box;">Item Code</th>
        <th>Product Line</th>
        <th>Brand</th>
        <th>Description</th>
        <th>Cost/Unit</th>
        <th>Served</th>
        <th>Qty</th>
        <th>Subtotal</th>
        <th>Unit</th>
        <th style="min-width: 100px;">Action</th>
      </tr>
    </thead>
    <tbody>
      @if(false)
      @foreach($partItems as $row)
      @php
        if (is_array($row)) $row = new \App\Models\Equip\Detail($row);
        if (!$row->item_id) continue;
      @endphp
      <tr class="partRow">
        <td class="text-center">
          <label for="">{{ $row->item_id }}</label>
          <input type="hidden" name="parts[{{ $row->item_id }}][item_id]" value="{{ $row->item_id }}" >
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
          <input type="text" class="form-control text-center label-table-min" name="last_cost" value="{{ $row->item ? number_format($row->item->LastCost, 2, '.', null) : '0.00'  }}" autocomplete="off">
        </td>
        <td>
          <input type="number" class="form-control text-center label-table-min quantity" name="parts[{{ $row->item_id }}][qty]" value="{{ $row->qty }}" autocomplete="off">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min" name="total_cost" value="{{ number_format($row->qty * $row->item->LastCost, 2, '.', null) }}" autocomplete="off">
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
          <input type="hidden" class="form-control" name="item_id">
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
          <input type="text" class="form-control text-center label-table-min" name="lastcost" autocomplete="off">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min quantity" name="qty" value="1" autocomplete="off">
        </td>
        <td>
          <input type="text" class="form-control text-center label-table-min" name="totalcost" autocomplete="off">
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