<div class="table-responsive table-purchases" style="display: ;">
  <div class="text-right">
    <button type="button"  
      class="btn btn-success btn-sm btnAddRow" style="margin-bottom: 10px;">Add Row (F2)</button>
  </div>
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th style="width: 25%">Item #</th>
        <th style="width: 25%">Item Name</th>
        <th style="width: 25%">Qty to Order</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      @if(count($purchase->details))
      @php
        $hdrModel = new \App\Models\Equip\Hdr;
        $hdrs = $hdrModel->orderBy('asset_id')->get();
        $masterModel = new \App\Models\Item\Master;
        $masters = $masterModel->orderBy('item_id')->get();
      @endphp
      @foreach($purchase->details as $row)
      <tr class="purchaseRow" style="" >
        <td class="text-center">
          <label class="label-table-min index">{{ $loop->index+1 }}</label>
        </td>
        <td class="text-center"><input type="radio" class="form-check-input" name="purchases[{{ $loop->index+1 }}][eqp_prt]" value="eqp"  {{ $row->eqp == 1 ? 'checked' : '' }}></td>
        <td class="text-center"><input type="radio" class="form-check-input" name="purchases[{{ $loop->index+1 }}][eqp_prt]" value="prt" {{ $row->prt == 1 ? 'checked' : '' }}></td>
        <td>
          <select name="purchases[{{ $loop->index+1 }}][item_id]" class="form-control">
            @if ($row->eqp == 1)
              @foreach($hdrs as $hdr)
                @if ($hdr->asset_id == $row->item_id)
                <option class="brands" value="{{ $hdr->asset_id }}" selected>{{ $hdr->description }}</option>
                @else 
                <option class="brands" value="{{ $hdr->asset_id }}">{{ $hdr->description }}</option>
                @endif
              @endforeach
            @else if ($row->prt == 1)
              @foreach($masters as $master)
                @if ($master->item_id == $row->item_id)
                <option class="brands" value="{{ $master->item_id }}" selected>{{ $master->description }}</option>
                @else 
                <option class="brands" value="{{ $master->item_id }}">{{ $master->description }}</option>
                @endif
              @endforeach
            @endif
          </select>
        </td>
        <td>
          <input type="number" class="form-control text-center label-table-min quantity" name="purchases[{{ $loop->index+1 }}][qty_to_order]" value="{{ $row->qty_to_order }}" autocomplete="off">
        </td>
        <td style="width: 100px;">
          <button type="button" class="btn btn-danger btn-md btnRemoveRow center-block">
            <i class="fas fa-trash-alt"></i>
          </button>
        </td>
      </tr>
      @endforeach
      @endif
      <tr class="newPurchase" style="display: none;">
        <td class="text-center">
          <label class="label-table-min index">1</label>
        </td>
        <td>
          <select name="item_id" class="form-control">
          </select>
        </td>
        <td>
          <input type="number" class="form-control text-center label-table-min quantity" name="qty_to_order" value="1" autocomplete="off">
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