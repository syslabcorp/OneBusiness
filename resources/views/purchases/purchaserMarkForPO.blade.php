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
          @foreach($purchase->details->where('isVerified', '!=', 1) as $row)
          @if ($purchase->eqp_prt == 'equipment')
            @php
              $index = count($row->parts);
              $hdrModel = new \App\Models\Equip\Hdr;
              $hdrs = $hdrModel->orderBy('asset_id')->get();
              $masterModel = new \App\Models\Item\Master;
              $masters = $masterModel->orderBy('item_id')->get();
            @endphp
      
            @foreach($row->parts->where('isVerified', '!=', 1) as $part)
            <tr class="purchaseRow">
              @if($index == count($row->parts))
              <td class="text-center"  rowspan={{ count($row->parts->where('isVerified', '!=', 1)) }} >
                <label class="label-table-min">{{ $part->equipment() ? $part->equipment()->description : 'NaN' }}</label>
              </td>
              @endif
              <td class="text-center">
                @if ($part->isVerified == 2)
                  <i class="fas fa-exclamation-triangle dropDown">
                    <div class="menuDropDown">
                      <strong class="title">Quantity  Changed</strong>
                      <p class="item">from {{ $part->qty_old }} to {{ $part->qty_to_order }}</p>
                    </div>
                  </i>
                @elseif ($part->isVerified == 1)
                  <i class="fas fa-exclamation-triangle dropDown">
                    <div class="menuDropDown">
                      <strong class="title">Item Deleted</strong>
                      <p class="item">{{ $part->reason }}</p>
                    </div>
                  </i>
                @endif 
                <label class="label-table-min index">{{ $loop->index+1 }}</label>
                <input type="hidden" name="" value="{{ $part->id }}">
              </td>
              <td class="text-center"><label for="">{{ $part->getItemAttribute() ? $part->getItemAttribute()->description : 'NaN'}}</label></td>
              <td>
                <select name="parts[{{ $row->item_id }}][{{ $part->id }}][vendor_id]" class="form-control vendor "> 
                <option class="" value="">-- select --</option>
                @foreach($masters as $master)
                  @if ($master->supplier_id == ($part->vendor_id ? $part->vendor_id : ($part->getItemAttribute() ? $part->getItemAttribute()->supplier_id : '')))
                  <option class="" value="{{ $master->supplier_id }}" selected>{{ $master->vendor->VendorName }}</option>
                  @else 
                  <option class="" value="{{ $master->supplier_id }}">{{ $master->vendor->VendorName }}</option>
                  @endif
                @endforeach
                </select>
              </td>
              <td>
                <input type="text" class="form-control text-center label-table-min qty quantity" name="parts[{{ $row->item_id }}][{{ $part->id }}][qty_to_order]" value="{{ $part->qty_to_order }}" autocomplete="off">
              </td>
              <td>
                <input type="text" class="form-control text-right label-table-min cost quantity cost-mask" name="parts[{{ $row->item_id }}][{{ $part->id  }}][cost]" value="{{ $part->cost }}" autocomplete="off">
              </td>
              <td>
                <input type="text" class="form-control text-right total" name="" autocomplete="off" readonly>
              </td>
              <td style="width: 100px;">
                @if($part->isVerified == 2)
                  <button type="button" class="btn btn-info btn-md center-block">
                    <i class="fa fa-refresh" aria-hidden="true"></i>
                  </button>
                @else 
                  <button type="button" class="btn btn-danger btn-md center-block access_delete" data-toggle="modal" data-target="#myModal">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                @endif
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
            @if ($row->isVerified == 2)
              <i class="fas fa-exclamation-triangle dropDown">
                <div class="menuDropDown">
                  <strong class="title">Quantity  Changed</strong>
                  <p class="item">from {{ $row->qty_old }} to {{ $row->qty_to_order }}</p>
                </div>
              </i>
            @elseif ($row->isVerified == 1)
              <i class="fas fa-exclamation-triangle dropDown">
                <div class="menuDropDown">
                  <strong class="title">Item Deleted</strong>
                  <p class="item">{{ $row->reason }}</p>
                </div>
              </i>
            @endif 
              <label class="label-table-min index">{{ $a++ }}</label>
              <input type="hidden" name="parts[{{ $loop->index+1 }}][part_id]" value="{{ $row->id }}">
            </td>
            <td class="text-center">
              <label class="label-table-min index">{{ $row->purchaseRequest ? $row->purchaseRequest->description : '' }}</label>
            </td>
            
            <td class="text-center"><label for="">{{ $row->getItemAttribute() ? $row->getItemAttribute()->description : ''}}</label></td>
            <td>
              <select name="parts[{{ $loop->index+1 }}][vendor_id]" class="form-control vendor "> 
              <option class="" value="">-- select --</option>
              @foreach($masters as $master)
                @if ($master->supplier_id == ( $row->vendor_id ? $row->vendor_id : ($row->getItemAttribute() ? $row->getItemAttribute()->supplier_id : '')))
                <option class="" value="{{ $master->supplier_id }}" selected>{{ $master->vendor->VendorName }}</option>
                @else 
                <option class="" value="{{ $master->supplier_id }}">{{ $master->vendor->VendorName }}</option>
                @endif
              @endforeach      
              </select>
            </td>
            <td>
              <input type="text" class="form-control text-center label-table-min qty quantity" name="parts[{{ $loop->index+1 }}][qty_to_order]" value="{{ $row->qty_to_order }}" autocomplete="off" >
            </td>
            <td class="text-right">
              <input type="text" class="form-control text-right label-table-min cost quantity cost-mask" name="parts[{{ $loop->index+1 }}][cost]" value="{{ $row->vendor ? number_format($row->vendor->LastCost,2) : ''}}" autocomplete="off">
            </td>
            <td class="text-right">
              <input type="text" class="form-control text-right total" name="" autocomplete="off" readonly>
            </td>
            <td style="width: 100px;">
              @if($row->isVerified == 2)
                <button type="button" class="btn btn-info btn-md center-block">
                  <i class="fa fa-refresh" aria-hidden="true"></i>
                </button>
              @else 
                <button type="button" class="btn btn-danger btn-md center-block access_delete" data-toggle="modal" data-target="#myModal">
                  <i class="fas fa-trash-alt"></i>
                </button>
              @endif
            </td>  
        @endif
      @endforeach 
      @endif
    </tbody>
    </tr>
  </table>
</div>
