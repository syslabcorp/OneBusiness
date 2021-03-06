<div class="table-responsive table-stocks" >
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
      @if(isset($stock))
      @foreach($stock->stock_details as $row)
      <tr class="stockRow">
        <td class="text-center">
          <input type="hidden" class="item_id" name="stocks[{{ $loop->index+1 }}][item_id]" value="{{ $row->item_id }}" >
          <input type="text" data-column="item_code" class="form-control text-center item_code showSuggest" name="stocks[{{ $loop->index+1 }}][item_code]" value="{{ $row->item ? $row->item->ItemCode : '' }}" autocomplete="off">
        </td>
        <td><input type="text" data-column="product_line" class="form-control text-center showSuggest" name="stocks[{{ $loop->index+1 }}][product_line]" value="{{ $row->item ? $row->item->product_line->Product : '' }}" autocomplete="off"></td>
        <td><input type="text" data-column="brand" class="form-control text-center showSuggest" name="stocks[{{ $loop->index+1 }}][brand]" value="{{ $row->item ? $row->item->brand->Brand : '' }}" autocomplete="off"></td>
        <td class="text-center"><label >{{ $row->item ? $row->item->Description : '' }}</label></td>
        <td><input type="text"   class="form-control text-center cost" name="stocks[{{ $loop->index+1 }}][cost]" value="{{ $row->Cost ? $row->Cost*100/100 : '' }}" autocomplete="off"></td>
        <td class="text-center"><label >{{ $row->ServedQty }}</label></td>
        <td><input type="number" class="form-control text-center  quantity" name="stocks[{{ $loop->index+1 }}][qty]" value="{{ $row->Qty ? $row->Qty : ''}}" autocomplete="off"></td>
        <td><input type="text"   class="form-control text-center subtotal" name="stocks[{{ $loop->index+1 }}][subtotal]" value="{{ $row->Cost ? ($row->Cost*100/100)*$row->Qty : '' }}" autocomplete="off"></td>
        <td class="text-center"><label >{{ $row->item ? $row->item->Unit : '' }}</label></td>

        <td style="width: 100px;">
          <button type="button" class="btn btn-danger btn-md btnRemoveRow center-block" {{ $stock->check_transfered() ? "disabled" : "" }}>
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
      @endforeach
      @endif
      <tr class="newStock" style="display: none;">
        <td class="text-center">
          <input type="hidden"   class="form-control item_id" name="item_id">
          <input type="text"  data-column="item_code" class="form-control item_code text-center showSuggest" name="item_code" autocomplete="off">
        </td>
        <td><input type="text"   data-column="product_line" name="product_line" class="form-control text-center showSuggest" autocomplete="off"></td>
        <td><input type="text"   data-column="brand" name="brand"  class="form-control text-center showSuggest" autocomplete="off"></td>
        <td class="text-center"><label></label></td>
        <td><input type="text"   name="cost" class="form-control text-center cost" value="0" autocomplete="off"></td>
        <td class="text-center"><label class="label-table-min">0</label></td>
        <td><input type="number" name="qty" class="form-control text-center quantity" value="1" autocomplete="off"></td>
        <td><input type="text"   name="subtotal" class="form-control text-center subtotal" value="0" autocomplete="off"></td>
        <td class="text-center"><label></label></td>
        
        <td style="width: 100px;">
          <button type="button"  class="btn btn-danger btn-md btnRemoveRow center-block">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
    </tbody>
  </table>
</div>