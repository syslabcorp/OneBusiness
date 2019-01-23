<div class="table-responsive table-purchases" style="display: ;">
  <table class="table table-bordered table-striped">
    <thead>
      <tr>
        @if ($purchase->eqp_prt == 'equipment')
        <th style="width: 12%;">Equipment </th>
        <th style="width: 12%">Item #</th>
        <th style="width: 12%">Item </th>
        <th style="width: 12%">Vendor </th>
        <th style="width: 12%">Qty Ordered </th>
        <th style="width: 12%">Cost </th>
        <th style="width: 12%">Total Cost</th>
        <th style="width: 12%">Action </th>
        @elseif($purchase->eqp_prt == 'parts')
        <th style="width: 12%">Item #</th>
        <th style="width: 12%">Item </th>
        <th style="width: 12%">For Equipment </th>
        <th style="width: 12%">Vendor </th>
        <th style="width: 12%">Qty Ordered </th>
        <th style="width: 12%">Cost </th>
        <th style="width: 12%">Total Cost</th>
        <th style="width: 12%">Action </th>
        @endif
      </tr>
    </thead>
    <tbody>
      @if(count($purchase->details))
      @php 
        $a = 1;
      @endphp
          @foreach($purchase->details as $row)
          @if ($purchase->eqp_prt == 'equipment')
            @php
              $index = count($row->parts);
              $hdrModel = new \App\Models\Equip\Hdr;
              $hdrs = $hdrModel->orderBy('asset_id')->get();
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
                  @if ($master->supplier_id == $part->getItemAttribute()['supplier_id'])
                  <option class="" value="{{ $master->supplier_id }}" selected>{{ $master->vendor->VendorName }}</option>
                  @else 
                  <option class="" value="{{ $master->supplier_id }}">{{ $master->vendor->VendorName }}</option>
                  @endif
                @endforeach
                </select>
              </td>
              <td>
                <input type="text" class="form-control text-center label-table-min qty quantity" value="{{ $part->qty_to_order }}" autocomplete="off">
              </td>
              <td>
                <input type="text" class="form-control text-right label-table-min cost quantity cost-mask" value="{{ $part->getItemAttribute() ? $part->getItemAttribute()->LastCost : 'NaN'}}" autocomplete="off">
              </td>
              <td>
                <input type="text" class="form-control text-right total" name="" autocomplete="off" readonly>
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
        @elseif ($purchase->eqp_prt == 'parts')
          @php
            $hdrModel = new \App\Models\Equip\Hdr;
            $hdrs = $hdrModel->orderBy('asset_id')->get();
            $masterModel = new \App\Models\Item\Master;
            $masters = $masterModel->orderBy('item_id')->get();
          @endphp
          <tr class="purchaseRow">
            <td class="text-center">
              <label class="label-table-min index">{{ $a++ }}</label>
            </td>
            <td class="text-center">
              <label class="label-table-min index">{{ $row->purchaseRequest ? $row->purchaseRequest->description : '' }}</label>
            </td>
            
            <td class="text-center"><label for="">{{ $row->getItemAttribute() ? $row->getItemAttribute()->description : ''}}</label></td>
            <td>
              <select name="" class="form-control"> 
              <option class="" value="" selected>-- select --</option>
              @foreach($masters as $master)
                @if ($master->supplier_id == $row->vendor_id)
                <option class="" value="{{ $master->supplier_id }}" selected>{{ $master->vendor->VendorName }}</option>
                @else 
                <option class="" value="{{ $master->supplier_id }}">{{ $master->vendor->VendorName }}</option>
                @endif
              @endforeach      
      
              </select>
            </td>
            <td>
              <input type="text" class="form-control text-center label-table-min qty quantity" value="{{ $row->qty_to_order }}" autocomplete="off" >
            </td>
            <td class="text-right">
              <input type="text" class="form-control text-right label-table-min cost quantity cost-mask" value="{{ $row->vendor ? number_format($row->vendor->LastCost,2) : ''}}" autocomplete="off">
            </td>
            <td class="text-right">
              <input type="text" class="form-control text-right total" name="" autocomplete="off" readonly>
            </td>
            <td style="width: 100px;">
              <button type="button" class="btn btn-danger btn-md btnRemoveRow center-block">
                <i class="fas fa-trash-alt"></i>
              </button>
            </td>
          </tr>
        @endif
      @endforeach
        
      @endif
    </tbody>
  </table>
</div>