<div class="table-responsive table-purchases" style="display: ;">
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        <th style="width: 130px">Equipment </th>
        <th style="width: 130px">Item #</th>
        <th style="width: 130px">Item </th>
        <th style="width: 130px">Vendor </th>
        <th style="width: 130px">Qty Ordered </th>
        <th style="width: 130px">Cost </th>
        <th style="width: 130px">Total Cost</th>
        <th style="width: 130px">Action </th>
      </tr>
    </thead>
    <tbody>
      @if(count($purchase->details))
      @foreach($purchase->details as $row)
      @php
        $index = count($row->parts);

        $masterModel = new \App\Models\Item\Master;
        
        $masters = $masterModel->orderBy('item_id')->get();
      @endphp
        @foreach($row->parts as $part)
        <tr class="purchaseRow">
          @if($index == count($row->parts))
          <td class="text-center"  rowspan={{ count($row->parts) }} >
            <label class="label-table-min index">{{ $part->equipment() ? $part->equipment()->description : 'NaN' }}</label>
          </td>
          @endif
          <td class="text-center">
            <label class="label-table-min index">{{ $loop->index+1 }}</label>
          </td>
          <td class="text-center"><label for="">{{ $part->getItemAttribute() ? $part->getItemAttribute()->description : 'NaN'}}</label></td>
          <td>
            <select name="" class="form-control"> 
            <option class="" value="" selected>-- select --</option>
            @foreach($masters as $master)
              @if ($master->supplier_id == $part->getItemAttribute()->supplier_id)
              <option class="" value="{{ $master->supplier_id }}" selected>{{ $master->vendor->VendorName }}</option>
              @else 
              <option class="" value="{{ $master->supplier_id }}">{{ $master->vendor->VendorName }}</option>
              @endif
            @endforeach
            </select>
          </td>
          <td>
            <input type="text" class="form-control text-center label-table-min qty quantity" value="{{ $part->qty_to_order }}" autocomplete="off" readonly>
          </td>
          <td>
            <input type="number" class="form-control text-center label-table-min cost quantity" value="{{ $part->getItemAttribute() ? number_format($part->getItemAttribute()->LastCost,2) : 'NaN'}}" autocomplete="off">
          </td>
          <td>
            <input type="text" class="form-control text-center total" name="" value="1" autocomplete="off" readonly>
          </td>
          <td style="width: 100px;">
            <button type="button" class="btn btn-danger btn-md btnRemoveRow center-block">
              <i class="fas fa-trash-alt"></i>
            </button>
          </td>
        </tr>
        @php 
          $index --;
        @endphp
        @endforeach
      @endforeach
      @endif
    </tbody>
  </table>
</div>